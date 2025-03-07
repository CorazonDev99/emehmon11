@extends('layouts.app')
@section('header_title', 'Поиск гостя')
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.0/dist/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>

        .hidden-section {
            display: none;
        }
        .bg-search {
            background-color: #e6e6e6;
        }
    </style>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>
    <script>
        const startDatePicker = flatpickr("#start_date", {
            dateFormat: "d.m.Y",
            locale: "ru",
            allowInput: true,
            clickOpens: false,
            onChange: function(selectedDates, dateStr, instance) {
                endDatePicker.set("minDate", dateStr);
            }
        });

        const endDatePicker = flatpickr("#end_date", {
            dateFormat: "d.m.Y",
            locale: "ru",
            allowInput: true,
            clickOpens: false,
        });

        document.getElementById("start_date_icon").addEventListener("click", function() {
            startDatePicker.open();
        });

        document.getElementById("end_date_icon").addEventListener("click", function() {
            endDatePicker.open();
        });
    </script>

    <script>
        $(document).ready(function () {
            $(":input").inputmask();
            $('.select2').select2();
        });


        $('#findGuest').click(function (e) {
            e.preventDefault();

            if ($(this).prop('disabled')) {
                return;
            }
            let formData = $('#search-collapse form').serializeArray().reduce(function (acc, curr) {
                acc[curr.name] = curr.value;
                return acc;
            }, {});

            formData._token = '{{ csrf_token() }}';

            $('#findGuest').prop('disabled', true);

            ['#listok-table', '#selflistok-table', '#ovirlists-table'].forEach(function (selector) {
                if ($.fn.DataTable.isDataTable(selector)) {
                    $(selector).DataTable().destroy();
                }
            });



            $.ajax({
                url: "{{ route('find-guest.searchGuest') }}",
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('#listok-collapse').prev().find('h5').html(`Поиск по лискам гостиниц: <span style="background-color: #f0ad4e; padding: 2px 5px; border-radius: 4px; color: white;">${response.listok.length}</span> записей`);
                    $('#selflistok-collapse').prev().find('h5').html(`Поиск по самостоятельным туристам: <span style="background-color: #f0ad4e; padding: 2px 5px; border-radius: 4px; color: white;">${response.selflistok.length}</span> записей`);
                    $('#ovir-collapse').prev().find('h5').html(`Поиск по базе ОВИРа: <span style="background-color: #f0ad4e; padding: 2px 5px; border-radius: 4px; color: white;">${response.ovirlists.length}</span> записей`);
                    $('#listok, #self-listok, #ovir').removeClass('hidden-section');

                    function initDataTable(selector, data, tableName, delay, topOffset, excelTitle) {
                        $(selector).DataTable({
                            data: data,
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excelHtml5',
                                    title: excelTitle,
                                    exportOptions: {
                                        modifier: {
                                            page: 'all'
                                        }
                                    },
                                    init: function(api, node) {
                                        $(node).removeClass('dt-button').addClass('btn btn-outline-success rounded')
                                            .html('<i class="fas fa-file-excel"></i>');
                                    }
                                },
                                {
                                    extend: 'print',
                                    title: 'Результаты поиска',
                                    text: '<i class="fas fa-print"></i>',
                                    autoPrint: true,
                                    init: function(api, node) {
                                        $(node).removeClass('dt-button').addClass('btn btn-outline-primary rounded ms-2');
                                    }
                                }
                            ],
                            columns: [
                                {
                                    data: null,
                                    title: '№',
                                    orderable: false,
                                    searchable: false,
                                    render: function (data, type, row, meta) {
                                        return meta.row + 1;
                                    },
                                    className: 'text-center',
                                },
                                { data: 'regNum', title: 'Рег. №:', className: 'text-center', orderable: false },
                                { data: 'fio', title: 'ФИО ГОСТЯ:', className: 'text-center', orderable: false },
                                { data: 'ctz', title: 'ГРАЖДАНСТВО:', className: 'text-center', orderable: false },
                                { data: 'dateVisitOn', title: 'ЧЕКИН:', className: 'text-center', orderable: false },
                                { data: 'dateVisitOff', title: 'ЧЕКАУТ:', className: 'text-center', orderable: false },
                            ],
                            pageLength: 10,
                            language: {
                                lengthMenu: "Показать _MENU_ записей",
                                info: "Показано с _START_ по _END_ из _TOTAL_ записей",
                                search: "Поиск:",
                                paginate: {
                                    first: "Первый",
                                    last: "Последний",
                                    next: "Следующий",
                                    previous: "Предыдущий"
                                },
                                infoFiltered: "(отфильтровано из _MAX_ записей)",
                                infoEmpty: "Нет доступных записей",
                                zeroRecords: "Записи не найдены"
                            }
                        });

                        setTimeout(function () {
                            const alert = document.createElement('div');
                            alert.className = 'custom-alert';
                            alert.style.cssText = `
                                position: fixed;
                                top: ${topOffset}px;
                                right: 10px;
                                z-index: 1050;
                                padding: 10px 20px;
                                background-color: #28a745;
                                color: white;
                                border-radius: 8px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                                opacity: 0;
                                transition: opacity 1s ease-in;
                            `;
                            alert.innerHTML = `<i class="fas fa-info-circle" style="margin-right: 10px;"></i><strong>${tableName}</strong>:  ${data.length}.`;

                            document.body.appendChild(alert);

                            setTimeout(() => {
                                alert.style.opacity = '1';
                            }, 0);

                            setTimeout(() => {
                                alert.style.opacity = '0';
                                setTimeout(() => alert.remove(), 2000);
                            }, 2000);
                        }, delay);
                    }

                    initDataTable('#listok-table', response.listok, 'Результаты поиска:<br>По базе гостиниц:', 0, 100, 'По базе гостиниц');
                    initDataTable('#selflistok-table', response.selflistok, 'Результаты поиска:<br>По базе самостоятельных туристов:', 1000, 170, 'По базе самостоятельных туристов');
                    initDataTable('#ovirlists-table', response.ovirlists, 'Результаты поиска:<br>По базе ОВИР:', 2000, 240, 'По базе ОВИР');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка!',
                        text: 'Произошла ошибка при выполнении поиска.',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        toast: true,
                    });
                },
                complete: function() {
                    $('#findGuest').prop('disabled', false);
                }
            });
        });



    </script>
@endsection

@section('content')
    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">FIND GUEST</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div id="addproduct-accordion" class="custom-accordion">
                        <div class="card">
                            <a href="#search-collapse" class="bg-search text-dark" data-bs-toggle="collapse" aria-expanded="true" aria-controls="search-collapse">
                                <div class="p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-1">
                                            <div class="avatar-xs">
                                                <i class="fa fa-search font-size-24"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Критерий поиска</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div id="search-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                <div class="p-4 border-top">
                                    <form>
                                        <div class="row g-3">
                                            <!-- Фамилия -->
                                            <div class="col-md-6">
                                                <label for="last_name" class="form-label">Фамилия:</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Фамилия ...">
                                            </div>

                                            <!-- Имя -->
                                            <div class="col-md-6">
                                                <label for="first_name" class="form-label">Имя:</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Имя ...">
                                            </div>

                                            <!-- Дата рождения -->
                                            <div class="col-md-6">
                                                <label for="birth_date" class="form-label">Дата рождения:</label>
                                                <input type="text" class="form-control"
                                                       data-inputmask="'alias': 'datetime'"
                                                       data-inputmask-inputformat="dd.mm.yyyy"
                                                       inputmode="numeric"
                                                       name="birth_date"
                                                       id="input-birthdate"
                                                       placeholder="dd.mm.yyyy">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="citizenship" class="form-label">Гражданство:</label>
                                                <select class="form-select select2" id="citizenship" name="citizenship">
                                                    <option value="">— Не выбрано —</option>
                                                    @foreach ($citizens as $citizen)
                                                        <option value="{{ $citizen->id }}">{{ $citizen->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Пол -->
                                            <div class="col-md-6">
                                                <label for="gender" class="form-label">Пол:</label>
                                                <select class="form-select" id="gender" name="gender">
                                                    <option value="">*** Все ***</option>
                                                    <option value="M">Мужской</option>
                                                    <option value="W">Женский</option>
                                                </select>
                                            </div>

                                            <!-- Регион -->
                                            <div class="col-md-6">
                                                <label for="region" class="form-label">Регион:</label>
                                                <select class="form-select" id="region" name="region">
                                                    <option value="">*** Все регионы ***</option>
                                                    @foreach ($regions as $region)
                                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Паспорт -->
                                            <div class="col-md-6">
                                                <label for="passport" class="form-label">Паспорт Серия №:</label>
                                                <input type="text" class="form-control" id="passport" name="passport" placeholder="AA1234567">
                                            </div>

                                            <!-- Период регистрации -->
                                            <div class="col-md-6">
                                                <label for="registration_period" class="form-label">Период регистрации:</label>
                                                <div class="d-flex">
                                                    <div class="col-sm-4 d-flex align-items-center me-4">
                                                        <span style="margin-top: 6px; margin-bottom: 6px; margin-right: 12px; font-size: 10px; color: #333; background-color: #ddd; padding: 8px 12px; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">С</span>
                                                        <div class="input-group">
                                                            <input type="text" id="start_date" class="form-control"
                                                                   data-inputmask="'alias': 'datetime'"
                                                                   data-inputmask-inputformat="dd.mm.yyyy"
                                                                   inputmode="numeric"
                                                                   name="start_date"
                                                                   placeholder="dd.mm.yyyy">
                                                            <span class="input-group-text" id="start_date_icon" style="cursor: pointer;">
                                                                <i class="fas fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 d-flex align-items-center">
                                                        <span style="margin-top: 6px; margin-bottom: 6px; margin-right: 12px; font-size: 10px; color: #333; background-color: #ddd; padding: 8px 12px; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);">ПО</span>
                                                        <div class="input-group">
                                                            <input type="text" id="end_date" class="form-control"
                                                                   data-inputmask="'alias': 'datetime'"
                                                                   data-inputmask-inputformat="dd.mm.yyyy"
                                                                   inputmode="numeric"
                                                                   name="end_date"
                                                                   placeholder="dd.mm.yyyy">
                                                            <span class="input-group-text" id="end_date_icon" style="cursor: pointer;">
                                                                <i class="fas fa-calendar-alt"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Кнопка поиска -->
                                            <div class="col-12 text-end">
                                                <button type="submit" id="findGuest" class="btn btn-success">
                                                    <i class="fa fa-search"></i> Поиск
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <a href="#listok-collapse" id="listok" class="text-dark bg-search collapsed hidden-section" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-haspopup="true" aria-controls="listok-collapse">
                                <div class="p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-1">
                                            <div class="avatar-xs">
                                                <i class="fa fa-exclamation-circle font-size-24"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Поиск по лискам гостиниц ...</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div id="listok-collapse" class="collapse" data-bs-parent="#addproduct-accordion">

                                <div class="p-4 border-top">
                                    <form>
                                        <table id="listok-table" class="display" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <a href="#selflistok-collapse" id="self-listok" class="text-dark bg-search collapsed hidden-section" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-haspopup="true" aria-controls="selflistok-collapse">
                                <div class="p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-1">
                                            <div class="avatar-xs">
                                                <i class="fa fa-exclamation-circle font-size-24"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Поиск по самостоятельным туристам ...</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div id="selflistok-collapse" class="collapse" data-bs-parent="#addproduct-accordion">
                                <div class="p-4 border-top">
                                    <form>
                                        <table id="selflistok-table" class="display" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <a href="#ovir-collapse" id="ovir" class="text-dark bg-search collapsed hidden-section" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-haspopup="true" aria-controls="ovir-collapse">
                                <div class="p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-1">
                                            <div class="avatar-xs">
                                                <i class="fa fa-exclamation-circle font-size-24"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Поиск по базе ОВИРа ...</h5>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <div id="ovir-collapse" class="collapse" data-bs-parent="#addproduct-accordion">

                                <div class="p-4 border-top">
                                    <form>
                                        <table id="ovirlists-table" class="display" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
