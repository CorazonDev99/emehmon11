@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.4/css/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6/build/css/tempus-dominus.css">
        <style>
        .container-fluid {
            max-width: 100% !important;
        }

        .line-confirm {
            background: #00a7d0;
        }

        .jconfirm .jconfirm-buttons {
            margin-right: 100px !important;
        }

        .jconfirm .jconfirm-title {
            margin-left: 20px;
            margin-top: 20px;
        }

        .confirm {
            margin-top: 20px !important;
            margin-left: 60px !important;
            width: 90%;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .css-label {
            font-size: 16px;
            margin: 0;
        }

        .css-checkbox {
            margin: 0;
        }

        .no_reviews {
            margin-top: 300px;
        }

        #filter {
            margin-left: 8px !important;

        }

        #checkout {
            margin-left: 8px !important;
        }

        #details {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        #room-index {

            z-index: 1;
        }

        #room-show {
            z-index: 2;
        }

        .viewed {
            background-color: #2f3e66 !important;
            color: #ffffff !important;
        }

        .dataTables_scrollBody {
            overflow-y: auto;
            height: 600px;
        }


        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            display: inline-block;
            vertical-align: middle;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
        }

        .card-nav {
            margin-top: 20px !important;
            margin-left: 20px !important;
            margin-right: 20px !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            display: inline-block;
            margin-right: 15px;
            vertical-align: middle;
        }

        .dataTables_paginate {
            white-space: nowrap;
        }

        .dataTables_length,
        .dataTables_info,
        .dataTables_paginate {
            width: auto;
        }

        #listok-table tbody tr {
            cursor: pointer;
        }

        .modal {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: block;
            opacity: 1;
        }
    </style>
    <style>
        .search-input {
            margin-left: 20px !important;
        }

        .global-search {
            margin-left: 100px !important;
            margin-top: 15px !important
        }

        #search-btn {
            margin-left: 20px !important
        }

        #toggle-filter-btn {
            margin-left: 10px !important;
        }

        #db-click-table {
            margin-left: 30px !important;
            width: 850px !important
        }

        .tab-content {
            margin-top: 25px !important
        }

        .listok_title {
            margin-bottom: 20px
        }

        .rooms {
            margin-left: 20px !important;
        }

        .jconfirm-box {
            width: 600px;
            padding-bottom: 20px;
        }


        #icon-cont {
            margin-right: 10px;
        }

        #context-menu {
            position: absolute;
            display: none;
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            padding: 10px;
            border-radius: 4px;
        }


        #context-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #context-menu ul li {
            padding: 10px;
            cursor: pointer;
        }

        #context-menu ul li:hover {
            background-color: #f0f0f0;
        }
    </style>

    <style>
        #custom-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            display: none;
        }

        /* Вращающийся индикатор */
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Затемнение фона таблицы */
        .loading-overlay {
            position: relative;
        }

        .loading-overlay:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 999;
            display: none;
        }

        .loading-overlay.loading:before {
            display: block;
        }
    </style>
@endsection


@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/libs/inputmask/inputmask.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.4/js/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>

    <script>
        var childrenData = @json($children);
    </script>

    {{-- Data Table and Filter --}}
    <script>
        $(document).ready(function() {
            var table = $('#listok-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/listok/data',
                    data: function(d) {
                        d.regNum = $('#regNum-filter').val();
                        d.room = $('#room-filter').val();
                        d.tag = $('#tag-filter').val();

                        let globalFilters = $('#global-search-form').serializeArray();
                        globalFilters.forEach(function(filter) {
                            d[filter.name] = filter.value;
                        });
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        className: 'select-checkbox',
                        width: '10px',
                        defaultContent: '',

                    },
                    {
                        data: 'id',
                        name: 'tb_listok.id',
                        searchable: false,
                        visible: false
                    },
                    {
                        data: 'regNum',
                        name: 'tb_listok.regnum',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'guest',
                        name: 'guest',
                        sClass: 'dt-center',

                    },
                    {
                        data: 'ctz',
                        name: 'tb_listok.id_citizen',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'room',
                        name: 'tb_listok.propiska',
                        sClass: 'dt-center',

                    },
                    {
                        data: 'dt',
                        name: 'tb_listok.datevisiton',
                        sClass: 'dt-center',

                    },
                    {
                        data: 'wdays',
                        name: 'tb_listok.wdays',
                        sClass: 'dt-center',

                    },
                    {
                        data: 'htl',
                        name: 'tb_hotels.name',
                        sClass: 'dt-center',

                    },
                    {
                        data: 'amount',
                        name: 'tb_listok.amount',
                        sClass: 'dt-center',

                    },
                    {
                        data: 'tag',
                        name: 'tag',
                        sClass: 'dt-center',

                    },
                    {
                        data: 'adm',
                        name: 'adm',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'datebirth',
                        name: 'datebirth',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'tb_visa',
                        name: 'tb_visa.name',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'tb_visanm',
                        name: 'tb_listok.visanumber',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'tb_visafrom',
                        name: 'tb_listok.datevisaon',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'tb_visato',
                        name: 'tb_listok.datevisaoff',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'kppnumber',
                        name: 'tb_listok.kppnumber',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'datekpp',
                        name: 'tb_listok.datekpp',
                        visible: false,
                        orderable: false,
                    }
                ],
                select: {
                    style: 'multi',
                    info: true
                },
                responsive: true,
                pageLength: 25,
                autoWidth: false,
                scrollX: true,
                order: [
                    [0, 'desc']
                ],
                dom: 'rt<"bottom"ilp><"clear">',
                "language": {
                    "lengthMenu": "Показать _MENU_ записей",
                    "info": "Показано с _START_ по _END_ из _TOTAL_ записей",
                    "search": "Поиск:",
                    "paginate": {
                        "first": "Первый",
                        "last": "Последний",
                        "next": "Следующий",
                        "previous": "Предыдущий"
                    },
                    "infoFiltered": "(отфильтровано из _MAX_ записей)",
                    "infoEmpty": "Нет доступных записей",
                    "zeroRecords": "Записи не найдены"
                },
                initComplete: function() {
                    $('.dataTables_length').appendTo('.dataTables_wrapper');
                    $('.dataTables_info').appendTo('.dataTables_wrapper');
                }

            });
            table.on('preXhr.dt', function() {
                $('#listok-table-container').addClass('loading');
                $('#custom-loading').fadeIn();
            });

            table.on('xhr.dt', function() {
                $('#listok-table-container').removeClass('loading');
                $('#custom-loading').fadeOut();
            });

            $('#toggle-fast-filter-btn').on('click', function() {
                $('#filter-section').collapse('toggle');
            });
            $('#search-btn').on('click', function() {
                table.ajax.reload();
            });
        });
    </script>

    {{-- Кнопка удаление --}}
    <script>
        $('#deleteButton').on('click', function() {
            let table = $('#listok-table').DataTable();
            let selectedRows = table.rows('.selected').data();
            console.log(selectedRows)
            if (selectedRows.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Пожалуйста, выберите элемент для удаления.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Вы уверены, что хотите удалить выбранные элементы?',
                text: "Эти элементы будут удалены навсегда!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Удалить',
                cancelButtonText: 'Отмена',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let ids = [];
                    selectedRows.each(function(row) {
                        ids.push(row.id);
                    });
                    let bronNum = 0
                    $.ajax({
                        url: '/listok/destroy',
                        type: 'POST',
                        data: {
                            ids: ids,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Элементы успешно удалены.',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                            bronNum += 1
                        },
                        error: function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Произошла ошибка при удалении.',
                                text: error.responseText
                            });
                        }
                    });
                }
            });
        });
    </script>

    {{-- Формат даты --}}
    <script>
        function formatDate(dateStr) {
            if (!dateStr || typeof dateStr !== 'string') return 'нет данных';

            let d;
            if (/^\d{2}-\d{2}-\d{4}$/.test(dateStr)) {
                const [day, month, year] = dateStr.split('-').map(Number);
                d = new Date(year, month - 1, day);
            } else if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                const [year, month, day] = dateStr.split('-').map(Number);
                d = new Date(year, month - 1, day);
            } else if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/.test(dateStr) || /^\d{2}-\d{2}-\d{4} \d{2}:\d{2}$/.test(dateStr)) {
                const [datePart, timePart] = dateStr.split(' ');
                const [year, month, day] = datePart.includes('-') && datePart.split('-').map(Number).length === 3 ?
                    datePart.split('-').map(Number).reverse() :
                    datePart.split('-').map(Number);
                const [hours, minutes] = timePart.split(':').map(Number);
                d = new Date(year, month - 1, day, hours, minutes);
            } else {
                return 'нет данных';
            }

            if (isNaN(d)) return 'нет данных';

            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');

            return d.getHours() || d.getMinutes() ?
                `${day}.${month}.${year} ${hours}:${minutes}` :
                `${day}.${month}.${year}`;
        }
    </script>

    {{-- Swal.fire Сохранено! --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire('Сохранено!', '{{ session('success') }}', 'success');
            @endif
        });
    </script>

    {{-- Checkout --}}
    <script>
        $(document).ready(function() {
            $('#checkout').on('click', function() {
                let table = $('#listok-table').DataTable();
                let selectedRows = table.rows('.selected').data();
                if (selectedRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Пожалуйста, выберите хотя бы один элемент для Checkout.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let modalContent = `
                <div class="confirm">
                <p>Выбрано <span>${selectedRows.length}</span> гостей, если в списке выбранных гостей имеются уже убывшие гости, для них данная операция не применяется еще раз!</p>


            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>CTZ</th>
                        <th>GUESTNAME</th>
                        <th>ROOM</th>
                        <th>CHECK-IN</th>
                        <th>LIVED</th>
                        <th>PAYMENT</th>
                        <th>PAYTYPE</th>
                        <th>COMMENTS</th>
                        <th>InBlack</th>
                    </tr>
                </thead>
                <tbody>
        `;

                $.each(selectedRows, function(index, row) {
                    modalContent += `
                <tr>
                    <td>${row.ctz}</td>
                    <td>${row.guest}</td>
                    <td>${row.room}</td>
                    <td>${row.dt}</td>
                    <td>${row.wdays}</td>
                    <td class="text-uppercase bold"><input class="form-control type="text" value="${row.payed}" style=";border-color: #2b95ff" required></td>
                    <td>
                        <select id="pay_tp" name="pay_tp" class="form-control paytp" title="Choose payment type" style=";border-color: #0090d9" required>
                            <option value="" selected> ***** </option>
                            <option value="2"> CASH (Наличные) </option>
                            <option value="3"> HUMO CARD </option>
                            <option value="4"> UZCARD </option>
                            <option value="5"> CONTRACT (Договор) </option>
                            <option value="1"> OTHER (Другое) </option>
                        </select>
                    </td>
                    <td class="text-uppercase bold"><input type="text" id="id_text" name="id_text" maxlength="400" class="form-control feedbackz" value="" placeholder="comments" style=";border-color: #2b95ff"/></td>
                    <td style="vertical-align: middle!important; font-size: 0.8em!important;" class="text-center"><input type="checkbox" id="id_bl" name="id_bl" class="inblackz" title="Add to Black-List" style=";border-color: #ff0084"/></td>
                </tr>
            `;
                });

                modalContent += `
                </tbody>
            </table>
            <div class="row col-md-12">
                    <div class="checkbox-container">
                        <input type="checkbox" name="printAll" id="printAll" class="css-checkbox"/>
                        <label for="printAll" class="css-label">Распечатать листки убытия?</label>
                    </div>

                    <div class="col-md-12" style="border: #4575d6 1px solid;border-collapse: collapse;padding:10px;margin:12px;">
                        <p align="justify text-info" style="font-size: 1em"><b>Убедителная просьба!</b> если хотите оставить свой отзыв о госте, то, пишите отзыв по существу! Ваш отзыв в дальнейшим будет доступен для других гостиниц!</p>
                        <p align="justify text-info" style="font-size: 1em">Если не хотите оставлять отзыв о госте, то, оставьте это поле пустым при условии что Вы его не включаете в Черный список! Никто Вас не заставляет писать отзывы о госте! <br>Не пишите: <b>"1, ОК, 2, -, ЯХШИ, СУММА ОПЛАТЫ, и т.д. типа таких отзывов"</b>, не мусорьте базу отзывов, данный модуль в скором времени будет доступен правоохранительным органам!</p>
                    </div>
            </div>
           </div>
        `;
                $.confirm({
                    title: 'Check-Out',
                    content: modalContent,
                    boxWidth: '1200px',
                    type: 'blue',
                    useBootstrap: false,
                    boxClass: 'checkout-confirm',
                    buttons: {
                        cancel: {
                            text: 'Отмена',
                            btnClass: 'btn-red',
                        },
                        checkout: {
                            text: 'Check-Out',
                            btnClass: 'btn-green',
                            action: function() {
                                let hasError = false;

                                $('input[required], select[required], textarea[required]').each(
                                    function() {
                                        if (!$(this).val()) {
                                            hasError = true;
                                            $(this).addClass('is-invalid');
                                        } else {
                                            $(this).removeClass('is-invalid');
                                        }
                                    });

                                if (hasError) {
                                    $.alert('Пожалуйста, заполните все обязательные поля!');
                                    return false;
                                }
                                if ($('#printAll').is(':checked')) {
                                    let rowsPerPage = 2;
                                    let printContent = `
                                    <!DOCTYPE html>
                                    <html lang="ru">
                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                        <style>
                                            body {
                                                font-family: Arial, sans-serif;
                                                margin: 0;
                                                padding: 0;
                                            }
                                            .qrcode{
                                            margin-left: 50px !important;
                                                }
                                            .page {
                                                width: 100%;
                                                margin: 0 auto;
                                                padding: 20px;
                                                box-sizing: border-box;
                                                page-break-after: always;
                                            }
                                            .header {
                                                display: flex;
                                                justify-content: space-between;
                                                align-items: center;
                                                margin-bottom: 20px;
                                            }
                                            .header img {
                                                height: 50px;
                                            }
                                            .header .hotel-info {
                                                flex: 1;
                                                margin-left: 60px !important;
                                            }
                                            .header .qr-code {
                                                text-align: right;
                                            }
                                            .qr-code img {
                                                width: 80px;
                                                height: 80px;
                                            }
                                            table {
                                                width: 100%;
                                                border-collapse: collapse;
                                                margin-top: 10px;
                                            }
                                            table, th, td {
                                                border: 1px solid #000;
                                            }
                                            th {
                                                text-align: left;
                                                padding: 5px;
                                                background-color: #f9f9f9;
                                            }
                                            td {
                                                padding: 5px;
                                            }
                                            .children-table th, .children-table td {
                                                text-align: center;
                                            }
                                            .children-table {
                                                margin-top: 10px;
                                            }
                                            .footer {
                                                margin-top: 20px;
                                            }
                                        </style>
                                    </head>
                                    <body>
                                `;

                                    let currentCount = 0;
                                    $.each(selectedRows, function(index, row) {
                                        if (currentCount === 0) {
                                            printContent += `<div class="page">`;
                                        }
                                        const fullname = (row.guest || '').split(' ');
                                        const lastName = fullname[0] || '';
                                        const firstName = fullname[1] || '';
                                        const surname = fullname[2] || '';
                                        printContent += `
                                        <table id="childrenTable" style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
                                            <tr>
                                                <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 50px;">
                                                </td>
                                                <td colspan="2" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                    <strong>Гостиница:</strong> ${row.htl || ''}<br>
                                                    <strong>Регион:</strong> ${row.region || ''}<br>
                                                    <strong>Адрес:</strong> ${row.tag || ''}<br>
                                                    <strong>№ ком.:</strong> ${row.room || ''}
                                                </td>
                                                <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                    <div class="qrcode" id="qrcode-${row.regnum}"></div>
                                                    <p>${row.regnum || ''}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>1. ФАМИЛИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${lastName || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>2. ИМЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${firstName || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>3. ОТЧЕСТВО:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${surname || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>4. ДАТА РОЖДЕНИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.datebirth || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>5. ГРАЖДАНСТВО:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.ctzn || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>6. ДОКУМЕНТ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.document || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>7. ВИЗА:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.visa || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>8. ОТКУДА ПРИБЫЛ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.arrival || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>9. КПП:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.kppnumber || ''}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="border: 1px solid black; padding: 5px;"><strong>10. Вместе с ним/ней прибыли дети до 16 лет</strong></td>
                                            </tr>
                                            <tr>
                                                <th style="border: 1px solid black; padding: 5px;"><strong>Имя</strong></th>
                                                <th style="border: 1px solid black; padding: 5px;"><strong>Пол</strong></th>
                                                <th colspan="3" style="border: 1px solid black; padding: 5px;"><strong>Дата рождения</strong></th>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>11. ДАТА ПРИБЫТИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dateArrival || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>12. ДАТА УБЫТИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dateDeparture || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>13. АДМИНИСТРАТОР:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.adm || ''}</td>
                                            </tr>
                                        </table>
                                    `;

                                        currentCount++;
                                        if (currentCount === rowsPerPage) {
                                            printContent += `</div>`;
                                            currentCount = 0;
                                        }
                                    });

                                    if (currentCount > 0) {
                                        printContent += `</div>`;
                                    }

                                    let iframe = document.createElement('iframe');
                                    iframe.style.position = 'absolute';
                                    iframe.style.width = '0';
                                    iframe.style.height = '0';
                                    iframe.style.border = 'none';
                                    document.body.appendChild(iframe);

                                    iframe.contentWindow.document.open();
                                    iframe.contentWindow.document.write(printContent);
                                    iframe.contentWindow.document.close();

                                    iframe.onload = function() {
                                        $.each(selectedRows, function(index, row) {
                                            new QRCode(iframe.contentWindow.document
                                                .getElementById(
                                                    `qrcode-${row.regnum}`), {
                                                    text: row.regnum,
                                                    width: 80,
                                                    height: 80
                                                });
                                        });

                                        setTimeout(function() {
                                            let printWindow = iframe.contentWindow;

                                            printWindow.onafterprint = function() {
                                                document.body.removeChild(
                                                    iframe);
                                            };

                                            printWindow.focus();
                                            printWindow.print();
                                        }, 500);

                                    };
                                }
                                let ids = [];
                                selectedRows.each(function(row) {
                                    ids.push(row.id);
                                });

                                $.ajax({
                                    url: '/listok/checkout',
                                    type: 'POST',
                                    data: {
                                        ids: ids,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            $('#listok-table').DataTable().ajax
                                                .reload();

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Вы успешно совершили Checkout.',
                                                text: response.message
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error(
                                            "Ошибка при Checkout элементов:",
                                            xhr.responseText);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Произошла ошибка при Checkout элементов.',
                                            text: xhr.responseText ||
                                                'Пожалуйста, попробуйте снова позже.',
                                        });
                                    }
                                });
                            },
                        },
                    },
                });
            });
        });
    </script>

    {{-- Context menu --}}
    <script>
        $(document).ready(function() {
            var table = $('#listok-table').DataTable();
            $('#listok-table tbody').on('contextmenu', 'tr', function(e) {

                e.preventDefault();

                var row = $(this);
                var data = table.row(row).data();

                if (!row.hasClass('selected')) {
                    table.row(row).select();
                }

                const $menu = $('#context-menu');
                const menuWidth = $menu.outerWidth();
                const menuHeight = $menu.outerHeight();
                const windowWidth = $(window).width();
                const windowHeight = $(window).height();

                let top = e.pageY + 5;
                let left = e.pageX + 5;

                if (top + menuHeight > windowHeight) {
                    top = e.pageY - menuHeight - 5;
                }
                if (left + menuWidth > windowWidth) {
                    left = e.pageX - menuWidth - 5;
                }

                $menu.css({
                        top: top + 'px',
                        left: left + 'px',
                        display: 'block',
                    })

                    .show()
                    .data('rowData', data);
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#context-menu').length) {
                    $('#context-menu').hide();
                }
            });

            $('#context-menu').on('click', '.menu-action', function(e) {
                e.preventDefault();

                var action = $(this).data('action');
                var rowData = $('#context-menu').data('rowData');

                handleAction(action, rowData);
                $('#context-menu').hide();
            });
        });

        function handleAction(action, rowData) {
            const selectedRows = $('#listok-table').DataTable().rows({
                selected: true
            }).data();
            let textToCopy = '';
            for (let i = 0; i < selectedRows.length; i++) {
                const row = selectedRows[i];
                textToCopy += `
                    Гость: ${row.guest}
                    Регистрационный номер: ${row.regNum}
                    Дата заезда: ${row.datekpp}
                    Гостиница: ${row.htl}
                `;
                if (i < selectedRows.length - 1) {
                    textToCopy += '\n---\n';
                }
            }
            switch (action) {
                case 'copy':
                    $.confirm({
                        title: 'Копирование',
                        content: `Вы хотите скопировать данные для ${selectedRows.length} строк(и)?`,
                        type: 'blue',
                        buttons: {
                            confirm: {
                                text: 'Да',
                                action: function() {
                                    navigator.clipboard.writeText(textToCopy);
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Данные скопированы!',
                                    });
                                }
                            },
                            cancel: {
                                text: 'Нет'
                            }
                        }
                    });
                    break;

                case 'info':
                    const modalHtml = `
                        <div class="modal fade" id="guestInfoModal" tabindex="-1" aria-labelledby="guestInfoModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: white; color: white;">
                                        <h5 class="modal-title" id="guestInfoModalLabel">
                                            <i class="fa fa-suitcase" style="margin-right: 10px;"></i>
                                            Информация о ${rowData.guest}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="nav nav-tabs" id="guestInfoTabs" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">
                                                    Информация
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">
                                                    Отзывы
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="tab-content mt-3">
                                            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                                <p><strong>Гражданство:</strong> ${rowData.ctz} -  ${rowData.ctzn}</p>
                                                <p><strong>В черном списке нашей гостиницы:</strong> <span class="badge bg-info">НЕТ</span></p>
                                                <p><strong>В глобальном черном списке:</strong> <span class="badge bg-info">НЕТ</span></p>
                                                <p><strong>Последняя активность в системе E-MEHMON:</strong> Нет данных.</p>
                                                <p><strong>Гость останавливался у нас:</strong> Этот гость у нас 1 раз.</p>
                                            </div>
                                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                                ${rowData.text ? `<p>${rowData.text}</p>` : '<p>Нет отзывов.</p>'}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">ОК</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                                `;

                    $('body').append(modalHtml);
                    $('#guestInfoModal').modal('show');
                    $('#guestInfoTabs button').each(function() {
                        var tabTrigger = new bootstrap.Tab(this);
                        $(this).on('click', function(e) {
                            e.preventDefault();
                            tabTrigger.show();
                        });
                    });
                    $('#guestInfoModal').on('hidden.bs.modal', function() {
                        $(this).remove();
                    });
                    break;


                case 'checkout':
                    $.confirm({
                        title: 'Check-Out',
                        type: 'blue',
                        content: `Вы уверены, что хотите совершить Checkout для гостя: ${rowData.guest}?`,
                        buttons: {
                            confirm: {
                                text: 'Да',
                                action: function() {
                                    let table = $('#listok-table').DataTable();
                                    let row = table.row(function(idx, data, node) {
                                        return data.id === rowData.id;
                                    });
                                    if (row.node()) {
                                        $(row.node()).addClass('selected');
                                    } else {
                                        console.warn(`Строка с ID ${rowData.id} не найдена.`);
                                    }
                                    $('#checkout').trigger('click');
                                }

                            },
                            cancel: {
                                text: 'Нет'
                            }
                        }
                    });
                    break;

                case 'move':
                    const selectedRows = $('#listok-table').DataTable().rows({
                        selected: true
                    }).data();

                    if (selectedRows.length === 0) {
                        $.alert('Выберите хотя бы одного гостя для перемещения.');
                        return;
                    }

                    const selectedGuests = Array.from(selectedRows, row => row.id);

                    $.confirm({
                        title: 'Переместить в другой номер',
                        type: 'blue',
                        content: `
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Переместить в другой номер</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-check-circle" style="font-size: 48px; color: forestgreen; margin-right: 15px;"></i>
                                                <p>
                                                    Выбрано <span class="badge bg-primary" id="selectedGuestsCount">${selectedGuests.length}</span> гостей.
                                                    Вы можете перевести выбранных гостей в другой номер (комнату).
                                                    Операция применяется ко всем выбранным гостям!
                                                </p>
                                            </div>
                                            <div class="mt-3">
                                                <label for="id_room">Номер / Комната:</label>
                                                <select id="id_room" name="id_room" class="select2 form-select" required>
                                                    <option value="">--- НЕ ВЫБРАНО ---</option>
                                                    @foreach ($rooms as $room)
                                                    <option value="{{ $room->room_number }}">{{ $room->room_number }} - {{ $room->room_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `,
                        buttons: {
                            confirm: {
                                text: 'Переместить',
                                action: function() {
                                    const room_number = $('#id_room').val();

                                    if (!room_number) {
                                        $.alert('Пожалуйста, выберите номер комнаты.');
                                        return false;
                                    }

                                    $.ajax({
                                        url: '/listok/move-to-room',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            guest_ids: selectedGuests, // Передача массива ID гостей
                                            room_number: room_number
                                        },
                                        success: function(response) {
                                            if (response.status === 'success') {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: response.message,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                                $('#listok-table').DataTable().ajax.reload(null,
                                                    false);
                                            } else {
                                                $.alert(response.message);
                                            }
                                        },
                                        error: function() {
                                            $.alert('Произошла ошибка при перемещении гостей.');
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: 'Отмена'
                            }
                        },
                    });
                    break;
                case 'status':
                    const selectedStatusRows = $('#listok-table').DataTable().rows({
                        selected: true
                    }).data();
                    $.confirm({
                        title: 'Статус оплаты',
                        type: 'blue',
                        content: `
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fa fa-check-circle" style="font-size: 48px; color: forestgreen; margin-right: 15px;"></i>
                                            <p>
                                                Выбрано <span class="badge bg-primary" id="selectedGuestsCount">${selectedStatusRows.length}</span> гостей.
                                                Вы можете установить статус оплаты для выбранных гостей.
                                                Операция применяется ко всем выбранным гостям!
                                            </p>
                                        </div>
                                        <div class="mt-3">
                                            <label for="paymentStatus" class="form-label">Статус оплаты:</label>
                                            <select id="paymentStatus" class="form-select">
                                                <option value="">--- Выберите статус ---</option>
                                                <option value="paid">Оплачено</option>
                                                <option value="pending">В ожидании</option>
                                                <option value="canceled">Отменено</option>
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <label for="paymentAmount" class="form-label">Сумма UZS:</label>
                                            <input type="text" id="paymentAmount" class="form-control" placeholder="ИТОГОВАЯ СУММА">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `,
                        buttons: {
                            confirm: {
                                text: 'Установить',
                                action: function() {
                                    const payment = $('#paymentAmount').val();
                                    if (!payment) {
                                        $.alert('Пожалуйста, заполните все поля.');
                                        return false;
                                    }

                                    const selectedIds = [];
                                    selectedStatusRows.each(function(rowData) {
                                        selectedIds.push(rowData.id);
                                    });

                                    $.ajax({
                                        url: '/listok/status-payment',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            guest_ids: selectedIds,
                                            payment: payment,
                                        },
                                        success: function(response) {
                                            if (response.status === 'success') {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: response.message,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                                $('#listok-table').DataTable().ajax.reload(null,
                                                    false);
                                            } else {
                                                $.alert(response.message);
                                            }
                                        },
                                        error: function() {
                                            $.alert('Произошла ошибка при обновлении данных.');
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: 'Отмена'
                            }
                        }
                    });
                    break;



                case 'print':

                    const selectedPrintRows = $('#listok-table').DataTable().rows({
                        selected: true
                    }).data();
                    let rowsPerPage = 2;
                    let printContent = `
                                    <!DOCTYPE html>
                                    <html lang="ru">
                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                        <style>
                                            body {
                                                font-family: Arial, sans-serif;
                                                margin: 0;
                                                padding: 0;
                                            }
                                            .qrcode{
                                            margin-left: 50px !important;
                                                }
                                            .page {
                                                width: 100%;
                                                margin: 0 auto;
                                                padding: 20px;
                                                box-sizing: border-box;
                                                page-break-after: always;
                                            }
                                            .header {
                                                display: flex;
                                                justify-content: space-between;
                                                align-items: center;
                                                margin-bottom: 20px;
                                            }
                                            .header img {
                                                height: 50px;
                                            }
                                            .header .hotel-info {
                                                flex: 1;
                                                margin-left: 60px !important;
                                            }
                                            .header .qr-code {
                                                text-align: right;
                                            }
                                            .qr-code img {
                                                width: 80px;
                                                height: 80px;
                                            }
                                            table {
                                                width: 100%;
                                                border-collapse: collapse;
                                                margin-top: 10px;
                                            }
                                            table, th, td {
                                                border: 1px solid #000;
                                            }
                                            th {
                                                text-align: left;
                                                padding: 5px;
                                                background-color: #f9f9f9;
                                            }
                                            td {
                                                padding: 5px;
                                            }
                                            .children-table th, .children-table td {
                                                text-align: center;
                                            }
                                            .children-table {
                                                margin-top: 10px;
                                            }
                                            .footer {
                                                margin-top: 20px;
                                            }
                                        </style>
                                    </head>
                                    <body>
                                `;

                    let currentCount = 0;
                    $.each(selectedPrintRows, function(index, row) {
                        if (currentCount === 0) {
                            printContent += `<div class="page">`;
                        }
                        const fullname = (row.guest || '').split(' ');
                        const lastName = fullname[0] || '';
                        const firstName = fullname[1] || '';
                        const surname = fullname[2] || '';
                        printContent += `
                                        <table id="childrenTable" style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
                                            <tr>
                                                <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 50px;">
                                                </td>
                                                <td colspan="2" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                    <strong>Гостиница:</strong> ${row.htl || ''}<br>
                                                    <strong>Регион:</strong> ${row.region || ''}<br>
                                                    <strong>Адрес:</strong> ${row.tag || ''}<br>
                                                    <strong>№ ком.:</strong> ${row.room || ''}
                                                </td>
                                                <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                    <div class="qrcode" id="qrcode-${row.regnum}"></div>
                                                    <p>${row.regnum || ''}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>1. ФАМИЛИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${lastName || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>2. ИМЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${firstName || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>3. ОТЧЕСТВО:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${surname || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>4. ДАТА РОЖДЕНИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.datebirth || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>5. ГРАЖДАНСТВО:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.ctzn || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>6. ДОКУМЕНТ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.document || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>7. ВИЗА:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.visa || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>8. ОТКУДА ПРИБЫЛ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.arrival || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>9. КПП:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.kppnumber || ''}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" style="border: 1px solid black; padding: 5px;"><strong>10. Вместе с ним/ней прибыли дети до 16 лет</strong></td>
                                            </tr>
                                            <tr>
                                                <th style="border: 1px solid black; padding: 5px;"><strong>Имя</strong></th>
                                                <th style="border: 1px solid black; padding: 5px;"><strong>Пол</strong></th>
                                                <th colspan="3" style="border: 1px solid black; padding: 5px;"><strong>Дата рождения</strong></th>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>11. ДАТА ПРИБЫТИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dateArrival || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>12. ДАТА УБЫТИЯ:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dateDeparture || ''}</td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px;"><strong>13. АДМИНИСТРАТОР:</strong></td>
                                                <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.adm || ''}</td>
                                            </tr>
                                        </table>
                                    `;

                        currentCount++;
                        if (currentCount === rowsPerPage) {
                            printContent += `</div>`;
                            currentCount = 0;
                        }
                    });

                    if (currentCount > 0) {
                        printContent += `</div>`;
                    }

                    let iframe = document.createElement('iframe');
                    iframe.style.position = 'absolute';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = 'none';
                    document.body.appendChild(iframe);

                    iframe.contentWindow.document.open();
                    iframe.contentWindow.document.write(printContent);
                    iframe.contentWindow.document.close();

                    iframe.onload = function() {
                        $.each(selectedPrintRows, function(index, row) {
                            new QRCode(iframe.contentWindow.document
                                .getElementById(
                                    `qrcode-${row.regnum}`), {
                                    text: row.regnum,
                                    width: 80,
                                    height: 80
                                });
                        });

                        setTimeout(function() {
                            let printWindow = iframe.contentWindow;

                            printWindow.onafterprint = function() {
                                document.body.removeChild(
                                    iframe);
                            };

                            printWindow.focus();
                            printWindow.print();
                        }, 500);

                    };
                    break;


                case 'feedback':
                    const selectedFeedbackRows = $('#listok-table').DataTable().rows({
                        selected: true
                    }).data();

                    if (selectedFeedbackRows.length !== 1) {
                        $.alert('Выберите только одного гостя для отзыва!');
                        break;
                    }

                    const feedbackdata = selectedFeedbackRows[0];

                    $.confirm({
                        title: 'Добавить Отзыв',
                        type: "blue",
                        content: `
                            <p style="margin-left:20px;">${feedbackdata.ctz} ${feedbackdata.guest}</p>
                            <textarea class="form-control" id="feedbackText" rows="4" placeholder="Введите отзыв"></textarea>
                            <div class="form-check mt-3">
                                <label class="form-label">Черный список:</label>
                                <div>
                                    <input type="radio" name="blacklist" value="yes" id="blacklistYes">
                                    <label for="blacklistYes">ДА</label>
                                    <input type="radio" name="blacklist" value="no" id="blacklistNo" checked>
                                    <label for="blacklistNo">НЕТ</label>
                                </div>
                            </div>
                        `,
                        buttons: {
                            ДОБАВИТЬ: {
                                btnClass: 'btn-primary',
                                action: function() {
                                    const feedback = this.$content.find('#feedbackText').val();
                                    const blacklistStatus = this.$content.find(
                                        'input[name="blacklist"]:checked').val();

                                    if (!feedback.trim()) {
                                        $.alert('Пожалуйста, введите отзыв!');
                                        return false;
                                    }

                                    $.ajax({
                                        url: '/listok/feedback',
                                        method: 'POST',
                                        data: {
                                            id_citizen: rowData.id_citizen,
                                            passportSerial: feedbackdata.passportSerial,
                                            passportNumber: feedbackdata.passportNumber,
                                            entry_by: feedbackdata.entry_by,
                                            person_id: feedbackdata.id_person,
                                            feedback: feedback,
                                            blacklist: blacklistStatus,
                                            _token: $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: response.message,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                                $('#listok-table').DataTable().ajax.reload(null,
                                                    false);
                                            } else {
                                                $.alert({
                                                    title: 'Ошибка!',
                                                    content: response.message ||
                                                        'Ошибка добавления отзыва!',
                                                    type: 'red'
                                                });
                                            }
                                        },
                                        error: function() {
                                            $.alert({
                                                title: 'Ошибка!',
                                                content: 'Ошибка сервера!',
                                                type: 'red'
                                            });
                                        }
                                    });
                                }
                            },
                            ОТМЕНА: function() {}
                        }
                    });
                    break;




                case 'tag':
                    const selectedTagRows = $('#listok-table').DataTable().rows({
                        selected: true
                    }).data();

                    $.confirm({
                        title: 'Присваивание тега для гостей',
                        type: 'blue',
                        content: `
                                <div>
                                    <p>
                                        <i class="fa fa-tag" style="color: #007bff;"></i>
                                        Выбрано <b>${selectedTagRows.length}</b> гостей.
                                    </p>
                                    <input type="text" id="guest-tag" class="form-control" placeholder="Введите название тега">
                                </div>
                            `,
                        boxWidth: '400px',
                        useBootstrap: false,
                        buttons: {
                            confirm: {
                                text: 'OK',
                                btnClass: 'btn-blue',
                                action: function() {
                                    const tag = this.$content.find('#guest-tag').val();

                                    if (!tag) {
                                        $.alert('Пожалуйста, введите тег!');
                                        return false;
                                    }

                                    const guestIds = [];
                                    for (let i = 0; i < selectedTagRows.length; i++) {
                                        guestIds.push(selectedTagRows[i].id);
                                    }

                                    $.ajax({
                                        url: '/listok/tag',
                                        method: 'POST',
                                        data: {
                                            guest_ids: guestIds,
                                            tag: tag,
                                            _token: $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Тег успешно добавлен!',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                                $('#listok-table').DataTable().ajax.reload(null,
                                                    false);
                                            } else {
                                                $.alert(response.message ||
                                                    'Ошибка добавления тега!');
                                            }
                                        },
                                        error: function() {
                                            $.alert('Ошибка сервера!');
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: 'Отмена',
                                btnClass: 'btn-default'
                            }
                        }
                    });
                    break;



                case 'delete-tag':
                    const selectedDeleteTagRows = $('#listok-table').DataTable().rows({
                        selected: true
                    }).data();

                    if (selectedDeleteTagRows.length === 0) {
                        $.alert('Пожалуйста, выберите хотя бы одного гостя.');
                        return;
                    }

                    $.confirm({
                        title: 'Удалить теги',
                        type: 'blue',
                        content: `
                                <div>
                                    <p><i class="fa fa-tag" style="color: #007bff;"></i> Выбрано <b>${selectedDeleteTagRows.length}</b> гостей.</p>
                                </div>
                            `,
                        boxWidth: '400px',
                        useBootstrap: false,
                        buttons: {
                            confirm: {
                                text: 'Очистить',
                                btnClass: 'btn-danger',
                                action: function() {
                                    const guestIds = [];
                                    selectedDeleteTagRows.each(function(rowData) {
                                        guestIds.push(rowData.id);
                                    });

                                    $.ajax({
                                        url: '/listok/delete-tag',
                                        method: 'POST',
                                        data: {
                                            guest_ids: guestIds,
                                            _token: $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: "Теги успешно удалены!",
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                                $('#listok-table').DataTable().ajax.reload(null,
                                                    false);
                                            } else {
                                                $.alert('Ошибка при удалении тегов!');
                                            }
                                        },
                                        error: function() {
                                            $.alert('Ошибка сервера!');
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: 'Отмена',
                                btnClass: 'btn-default'
                            }
                        }
                    });
                    break;

                case 'extend-visa':
                    $.confirm({
                        title: 'Продление визы.',
                        type: 'blue',
                        content: `
            <div>
                <p><i class="fa fa-user" style="color: #007bff;"></i> Выбрано <b>1</b> гостей.</p>
                <form id="visa-form">
                    <div style="margin-bottom: 10px;">
                        <label for="dateVisaOn">Срок визы с:</label>
                        <input type="date" id="dateVisaOn" name="dateVisaOn" class="form-control">
                    </div>
                    <div>
                        <label for="dateVisaOff">Срок визы до:</label>
                        <input type="date" id="dateVisaOff" name="dateVisaOff" class="form-control">
                    </div>
                </form>
            </div>
        `,
                        boxWidth: '400px',
                        useBootstrap: false,
                        buttons: {
                            confirm: {
                                text: 'ИЗМЕНИТЬ',
                                btnClass: 'btn-green',
                                action: function() {
                                    const selectedGuest = rowData.id;

                                    const dateVisaOn = this.$content.find('#dateVisaOn').val();
                                    const dateVisaOff = this.$content.find('#dateVisaOff').val();

                                    if (!dateVisaOn || !dateVisaOff) {
                                        $.alert('Заполните оба поля!');
                                        return false;
                                    }

                                    $.ajax({
                                        url: '/listok/extend-visa',
                                        method: 'POST',
                                        data: {
                                            guest_id: selectedGuest,
                                            dateVisaOn: dateVisaOn,
                                            dateVisaOff: dateVisaOff,
                                            _token: $('meta[name="csrf-token"]').attr('content')
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: "Виза успешно продлено!",
                                                    showConfirmButton: false,
                                                });
                                                $('#listok-table').DataTable().ajax.reload(null,
                                                    false);
                                            } else {
                                                $.alert('Ошибка при обновлении данных!');
                                            }
                                        },
                                        error: function() {
                                            $.alert('Ошибка сервера!');
                                        }
                                    });
                                }
                            },
                            cancel: {
                                text: 'ОТМЕНА',
                                btnClass: 'btn-default'
                            }
                        }
                    });
                    break;

                default:
                    $.alert('Действие не реализовано.');
            }
        }
    </script>

    {{-- dbclick --}}
    <script>
        $(document).ready(function() {
            $('#listok-table tbody').on('dblclick', 'tr', function() {
                var table = $('#listok-table').DataTable();
                var data = table.row(this).data();
                var children = childrenData.filter(child => child.id === data.id);
                var pasport_data = childrenData.filter(pasport => pasport.listok_id === data.id);
                $.confirm({
                    title: `Регистрационный номер: ${data.regNum}`,
                    content: `
                <div class="tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#info" role="tab">Информация</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#additional-info" role="tab">Доп. информация</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#children-info" role="tab">Информация о детях</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover" id="db-click-table">
                                <tbody>
                                    <tr>
                                        <th>Рег. №</th>
                                        <td>${data.regNum || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Фамилия, Имя, Отчество</th>
                                        <td>${data.guest}</td>
                                    </tr>
                                    <tr>
                                        <th>Дата рождения</th>
                                        <td>${formatDate(data.datebirth)}</td>
                                    </tr>
                                    <tr>
                                        <th>Страна рождения</th>
                                        <td>${data.ctz} ${data.ctzn}</td>
                                    </tr>
                                    <tr>
                                        <th>Гражданство</th>
                                        <td>${data.ctz} ${data.ctzn}</td>
                                    </tr>
                                    <tr>
                                        <th>Откуда</th>
                                        <td>${data.ctz} ${data.ctzn}</td>
                                    </tr>
                                    <tr>
                                        <th>Прибыл на (дней)</th>
                                        <td>${data.wdays || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Сумма турсбора</th>
                                        <td>${data.amount || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Пол</th>
                                        <td>${data.sex === 'M' ? 'Мужчина' : 'Женщина'}</td>
                                    </tr>
                                    <tr>
                                        <th>Прибыл</th>
                                        <td>${data.dt || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Гостиница</th>
                                        <td>${data.htl || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Зарегистрировал</th>
                                        <td>${data.adm || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Обновлено</th>
                                        <td>${data.update_at || 'Не указано'}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Вкладка "Доп. информация" -->
                        <div class="tab-pane fade" id="additional-info" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover">
                                <tbody>
                                    <tr>
                                        <th>Тип документа</th>
                                        <td>${data.passportType || 'Не указано'} ${data.passport_full || ''}</td>
                                    </tr>
                                    <tr>
                                        <th>Паспорт дата/Выдан</th>
                                        <td>${formatDate(data.datePassport)} / ${data.PassportIssuedBy || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Визит</th>
                                        <td>${data.visittype || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Тип гостя</th>
                                        <td>${data.guesttype || 'Не указано'}</td>
                                    </tr>
                                    <tr>
                                        <th>Виза № (Кем выдана)</th>
                                        <td>${data.tb_visanm || 'Не указано'} (Видано: ${data.visaIssuedBy || 'Non commodo dolore d'})</td>
                                    </tr>
                                    <tr>
                                        <th>Срок визы</th>
                                        <td>c ${formatDate(data.tb_visafrom)} по ${formatDate(data.tb_visato)}</td>
                                    </tr>

                                    <tr>
                                        <th>КПП и дата</th>
                                        <td>№ ${data.kppnumber || 'Не указано'}; Дата: ${formatDate(data.datekpp)}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Вкладка "Информация о детях" -->
                        <div class="tab-pane fade" id="children-info" role="tabpanel">
                            ${
                                Array.isArray(children) && children.length > 0
                                    ? `
                                                                                                            <table class="table dataTable row-border compact table-hover">
                                                                                                                <thead>
                                                                                                                    <tr>
                                                                                                                        <th>ФИО ребёнка</th>
                                                                                                                        <th>Дата рождения</th>
                                                                                                                        <th>Пол</th>
                                                                                                                    </tr>
                                                                                                                </thead>
                                                                                                                <tbody>
                                                                                                                    ${children.map(child => `
                                                    <tr>
                                                        <td>${child.child_name}</td>
                                                        <td>${formatDate(child.child_dateBirth)}</td>
                                                        <td>${child.child_gender === 'M' ? 'Мальчик' : 'Девушка'}</td>
                                                    </tr>
                                                `).join('')}
                                                                                                                </tbody>
                                                                                                            </table>
                                                                                                        `
                                    : '<p>Нет информации о детях</p>'
                            }
                        </div>
                </div>
            `,
                    type: 'blue',
                    boxWidth: '70%',
                    useBootstrap: false,
                    buttons: {
                        close: {
                            text: 'Закрыть',
                            btnClass: 'btn-blue'
                        }
                    }
                });
            });
        });
    </script>

    {{-- Global search --}}
    <script>
        $(document).ready(function() {
            $('#toggle-filter-btn').on('click', function() {
                $.confirm({
                    title: 'Глобальный поиск',
                    boxWidth: '800px',
                    useBootstrap: false,
                    type: "blue",
                    content: "<div class='global-search'>" +
                        "<form id='global-search-form'>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ФАМИЛИЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' placeholder='ФАМИЛИЯ' name='surname'>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ИМЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' placeholder='ИМЯ' name='firstname'>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ОТЧЕСТВО:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' placeholder='ОТЧЕСТВО' name='lastname'>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ДАТА РОЖДЕНИЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='date' class='form-control' name='datebirth' value='' id='date-birth'>" +
                        "<small id='birth-date-error' class='text-danger' style='display: none;'>Sana noto‘g‘ri kiritilgan!</small>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ГРАЖДАНСТВО:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<select class='form-control' name='citizenship' id='citizenship'>" +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        "@foreach ($ctzns as $ctzn)" +
                        "<option value='{{ $ctzn->id }}'>{{ $ctzn->name }}</option>" +
                        "@endforeach" +
                        "</select>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ПЕРИОД ПРИБЫТИЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<div class='d-flex'>" +
                        "<input type='date' class='form-control me-2' name='arrival_from'>" +
                        "<input type='date' class='form-control' name='arrival_to'>" +
                        "</div>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ПАСПОРТ СЕРИЯ №:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' placeholder='СЕРИЯ/НОМЕР' name='passport_number'>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>РЕГИОН:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<select class='form-control' name='region' id='region'>" +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        "@foreach ($regions as $region)" +
                        "<option value='{{ $region->id }}'>{{ $region->name }}</option>" +
                        "@endforeach" +
                        "</select>" +
                        "</div>" +
                        "</div>" +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ГОСТИНИЦА:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<select class='form-control' name='hotel' id='hotel'>" +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        "@foreach ($hotels as $hotel)" +
                        "<option value='{{ $hotel->id }}'>{{ $hotel->name }}</option>" +
                        "@endforeach" +
                        "</select>" +
                        "</div>" +
                        "</div>" +
                        "</form>" +
                        "</div>",
                    buttons: {
                        Поиск: {
                            btnClass: 'btn-blue',
                            action: function() {
                                $('#listok-table').DataTable().ajax.reload(null, false);
                            },
                        },
                        Отмена: function() {},
                    },
                    onContentReady: function() {

                        $('#citizenship, #region, #hotel').selectize({
                            create: true,
                            sortField: 'text',
                            closeAfterSelect: true,
                            highlight: true,
                        });
                    },
                });
            });
        });
    </script>

    <script>
    $(document).ready(function () {

    });
    </script>

@endsection

@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <h4 class="listok_title">Листок прибытия</h4>
                <div class="card" id="user">
                    <div class="card-nav d-flex justify-content-between align-items-center">
                        <div class="btn-group me-5" role="group" aria-label="Basic example">
                            <div class="container mb-3">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-info rounded" id="toggle-fast-filter-btn">
                                        Быстрый Поиск
                                    </button>
                                </div>
                                <div class="collapse mt-3" id="filter-section">
                                    <div class="p-3 border rounded bg-light shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex flex-wrap gap-2">
                                                <input type="text" class="form-control" id="regNum-filter"
                                                    placeholder="Рег. №" style="width: 150px;">
                                                <input type="text" class="form-control" id="room-filter"
                                                    placeholder="Комната" style="width: 150px;">
                                                <input type="text" class="form-control" id="tag-filter" placeholder="Таг"
                                                    style="width: 150px;">
                                            </div>
                                            <button type="button" class="btn btn-dark" id="search-btn">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <a href="/listok/create" class="btn btn-outline-primary rounded me-2">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <button id="deleteButton" type="button" class="btn btn-outline-danger rounded">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <button id="checkout" type="button" class="btn btn-outline-danger rounded">
                                        <i class="fa fa-plane"></i>
                                    </button>

                                    <button type="button" class="btn btn-outline-info rounded" id="toggle-filter-btn">
                                        <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="context-menu">
                        <ul style="list-style: none; padding: 10px; margin: 0;">
                            <li><a href="#" class="menu-action" data-action="copy"><i id="icon-cont"
                                        class="fas fa-copy"></i>Копировать (в буфер обмена)</a></li>
                            <li><a href="#" class="menu-action" data-action="info"><i id="icon-cont"
                                        class="fas fa-info-circle"></i>Сведения о госте</a></li>
                            <li><a href="#" class="menu-action" data-action="checkout"><i id="icon-cont"
                                        class="fas fa-plane"></i>CheckOut</a></li>
                            <li><a href="#" class="menu-action" data-action="move"><i id="icon-cont"
                                        class="fas fa-network-wired"></i>Переместить в другой номер</a></li>
                            <li><a href="#" class="menu-action" data-action="status"><i id="icon-cont"
                                        class="fas fa-calculator"></i>Изменить статус оплаты</a></li>
                            <li><a href="#" class="menu-action" data-action="feedback"><i id="icon-cont"
                                        class="fas fa-comment-dots"></i>Добавить отзыв</a></li>
                            <li><a href="#" class="menu-action" data-action="print"><i id="icon-cont"
                                        class="fas fa-print"></i>Печать листка прибытия</a></li>
                            <li><a href="#" class="menu-action" data-action="tag"><i id="icon-cont"
                                        class="fas fa-tag"></i>Присвоить тег</a></li>
                            <li><a href="#" class="menu-action" data-action="delete-tag"><i id="icon-cont"
                                        class="fas fa-times"></i>Удалить тег</a></li>
                            <li><a href="#" class="menu-action" data-action="extend-visa"><i id="icon-cont"
                                        class="fas fa-id-card"></i>Продлить визу</a></li>
                        </ul>
                    </div>

                    <div id="listok-table-container" class="loading-overlay">
                        <div id="custom-loading">
                            <div class="spinner"></div>
                        </div>
                        <table class="table dataTable row-border table-hover" id="listok-table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="non_searchable">ID</th>
                                    <th style="white-space: nowrap;">РЕГ.№</th>
                                    <th>Ф.И.О ГОСТЯ</th>
                                    <th class="non_searchable">ГРАЖД.</th>
                                    <th>НОМЕР</th>
                                    <th>ПРИБЫЛ</th>
                                    <th>ПРИБЫЛ_НА</th>
                                    <th>ГОСТИНИЦА</th>
                                    <th>ОПЛАТА</th>
                                    <th>ТЕГ</th>
                                    <th>АДМИНИСТРАТОР</th>
                                    <th>Д/Р</th>
                                    <th>ВИЗА</th>
                                    <th>ВИЗА №</th>
                                    <th>ВИЗА С</th>
                                    <th>ВИЗА ПО</th>
                                    <th>КПП №</th>
                                    <th>ДАТА КПП</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
