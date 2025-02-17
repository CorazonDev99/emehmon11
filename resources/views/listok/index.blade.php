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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.default.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.contextMenu.min.css">
    <style>
        #info {
            overflow-x: hidden;
        }

    </style>
    <style>

        .admin{
            background-color: #fff6ee;
            padding: 10px;
        }
        #guestInfoTabs{
            margin-top: 20px !important;
            margin-left: 20px !important;
        }
        #reviews{
            width: 100%;

        }
        .btb-feedback {
            margin-left: 555px;
        }

        .line-confirm {
            background: #00a7d0;
        }

        /*.jconfirm .jconfirm-buttons {*/
        /*    margin-right: 100px !important;*/
        /*}*/

        /*.jconfirm .jconfirm-title {*/
        /*    margin-left: 20px;*/
        /*    margin-top: 20px;*/
        /*}*/

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
            width: 85%;
            margin-left: 100px !important;
            margin-top: 15px !important
        }

        #search-btn {
            margin-left: 20px !important
        }


        #db-click-table {
            width: 98%;
            margin-left: 10px !important;
        }

        .tab-content {
            width: 100% !important;
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
            border-radius: 4px;
        }

        #context-menu.show {
            opacity: 1;
            pointer-events: auto;
        }

        #context-menu .menu-action {
            display: block;
            padding: 4px;
            color: #333;
            font-size: 14px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.2s, color 0.2s;
        }

        #context-menu .menu-action:hover {
            background-color: #007bff;
            color: #fff;
        }
         </style>

    <style>
        #listok-table tbody tr.selected {
            background-color: #c2eeff;
            font-weight: bold;
        }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.9.2/jquery.ui.position.min.js"></script>
    <script>
        var childrenData = @json($childrens);
        var feedbacks = @json($feedbacks);
        var roomhistory = @json($room_history);
        var bronroom = @json($bron_room);
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
                        name: 'tb_listok.regNum',
                        sClass: 'dt-start',
                        render: function(data, type, row) {
                            return `<a class="btn btn-sm btn-outline-primary p-1 text-bold open-menu-btn" data-id="${row.id}">${data}</a>`;
                        }

                    },
                    {
                        data: 'guest',
                        name: 'guest',
                        sClass: 'dt-start',

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
                        name: 'tb_listok.dateVisitOn',
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
                        name: 'tb_listok.visaNumber',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'tb_visafrom',
                        name: 'tb_listok.dateVisaOn',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'tb_visato',
                        name: 'tb_listok.dateVisaOff',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'kppNumber',
                        name: 'tb_listok.kppNumber',
                        visible: false,
                        orderable: false,
                    },
                    {
                        data: 'dateKPP',
                        name: 'tb_listok.dateKPP',
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
                    [0, 'asc']
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
                $('#global-search-form input').val('');
                table.ajax.reload();
            });
        });

    </script>

    {{-- Кнопка удаление --}}
    <script>
        $('#deleteButton').on('click', function() {
            let table = $('#listok-table').DataTable();
            let selectedRows = table.rows('.selected').data();
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
            @php
                $successMessage = session()->pull('success');
            @endphp

            @if (!empty($successMessage))
            Swal.fire('Сохранено!', '{{ $successMessage }}', 'success');
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
                    <td class="text-uppercase bold"><input class="form-control" id="payment" type="text" value="${row.amount}" style=";border-color: #2b95ff" required></td>
                    <td>
                       <select id="paytype" name="paytype" class="form-control paytp" title="Choose payment type" style="border-color: #0090d9" required>
                            <option value="0" ${row.paytp === 0 ? 'selected' : ''}>*****</option>
                            <option value="2" ${row.paytp === '2' ? 'selected' : ''}>CASH (Наличные)</option>
                            <option value="3" ${row.paytp === '3' ? 'selected' : ''}>HUMO CARD</option>
                            <option value="4" ${row.paytp === '4' ? 'selected' : ''}>UZCARD</option>
                            <option value="5" ${row.paytp === '5' ? 'selected' : ''}>ПЕРЕЧИСЛЕНИЕ</option>
                            <option value="6" ${row.paytp === '6' ? 'selected' : ''}>CONTRACT (Договор)</option>
                            <option value="1" ${row.paytp === '1' ? 'selected' : ''}>OTHER (Другое)</option>
                        </select>
                    </td>
                    <td class="text-uppercase bold"><input type="text" id="comment" name="comment" maxlength="400" class="form-control feedbackz" value="" placeholder="comments" style=";border-color: #2b95ff"/></td>
                    <td style="vertical-align: middle!important; font-size: 0.8em!important;" class="text-center">
                        <input
                            type="checkbox"
                            id="inblack"
                            name="inblack"
                            class="inblackz"
                            value="1"
                            title="Add to Black-List"
                            style="border-color: #ff0084"
                        />
                    </td>
                </tr>
            `;
                });

                modalContent += `
                </tbody>
            </table>
            <div class="row col-md-12">
                    <div class="checkbox-container">
                        <input type="checkbox" name="printAll" id="printAll" class="css-checkbox" checked/>
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
                                const payment = $('#payment').val().replace(/ /g, '');
                                const paytype = $('#paytype').val();
                                const comment = $('#comment').val();
                                const inBlack = $('#inblack').prop('checked') ? 1 : 0;

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
                                             table {
                                                margin-top:10px !important;
                                                width: 100%;
                                                border-collapse: collapse;
                                                table-layout: fixed;
                                            }
                                            .qrcode{
                                                margin-top:10px !important;
                                                margin-left: 27px !important;
                                                }

                                            table, th, td {

                                                border: 1px solid #000;
                                            }

                                            th, td {
                                                padding: 5px;
                                                text-align: left;
                                                height: 15px;
                                                vertical-align: middle;
                                            }

                                            th:nth-child(1), td:nth-child(1) { width: 30%; }
                                            th:nth-child(2), td:nth-child(2) { width: 50%; }
                                            th:nth-child(3), td:nth-child(3) { width: 20%;}

                                            </style>

                                        </head>
                                        <body>
                                    `;

                                    let currentCount = 0;
                                    $.each(selectedRows, function(index, row) {
                                        if (currentCount === 0) {
                                            printContent += `<div class="page">`;
                                        }

                                        printContent += `
                                            <table id="childrenTable" style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
                                                <tr>
                                                    <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 50px;">
                                                    </td>
                                                    <td colspan="2" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                        <strong>Гостиница:</strong> ${row.htl || '&nbsp;'}<br>
                                                        <strong>Регион:</strong> ${row.region || '&nbsp;'}<br>
                                                        <strong>Адрес:</strong> ${row.htl_address || '&nbsp;'}<br>
                                                        <strong>№ ком.:</strong> ${row.room || '&nbsp;'}
                                                    </td>
                                                    <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                                                        <div class="qrcode" id="qrcode-${row.regNum}"></div>
                                                        <p>${row.regNum || '&nbsp;'}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>1. ФАМИЛИЯ:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.surname || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>2. ИМЯ:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.firstname || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>3. ОТЧЕСТВО:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.lastname || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>4. ДАТА РОЖДЕНИЯ:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.datebirth || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>5. ГРАЖДАНСТВО:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.ctzn || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>6. ДОКУМЕНТ:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.passportType || '&nbsp;'}: ${row.passport_full || ''}; Выдан:${formatDate(row.datePassport)} / ${row.PassportIssuedBy || 'Не указано'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>7. ВИЗА:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">Тип: ${row.tb_visa || '&nbsp;'}; № ${row.tb_visanm || '&nbsp;'}; ${row.tb_visafrom || '&nbsp;'} - ${row.tb_visato || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>8. ОТКУДА ПРИБЫЛ:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.ctzn || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>9. КПП:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">№ ${row.kppNumber || '&nbsp;'}; Дата: ${row.dateKPP || '&nbsp;'} </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="border: 1px solid black; padding: 5px;"><strong>10. Вместе с ним/ней прибыли дети до 16 лет</strong></td>
                                                </tr>`;
                                        const filteredChildren = childrenData.filter(child => child.id === row.id);
                                        if (filteredChildren.length > 0) {
                                            printContent += `
                                            <tr>
                                                <td><strong>Имя</strong></td>
                                                <td colspan="2"><strong>Дата рождения</strong></td>
                                                <td><strong>Пол</strong></td>
                                            </tr>
                                        `;

                                                                        filteredChildren.forEach(child => {
                                                                            printContent += `
                                                <tr>
                                                    <td>${child.child_name}</td>
                                                    <td colspan="2">${child.child_dateBirth ? new Date(child.child_dateBirth).toLocaleDateString('ru-RU') : '--/--/----'}</td>
                                                    <td>${child.child_gender}</td>
                                                </tr>
                                            `;
                                                                        });
                                                                    } else {
                                                                        printContent += `
                                            <tr>
                                                <td colspan="5" style="text-align: center;"><strong>--нет--</strong></td>
                                            </tr>
                                        `;
                                        }
                                        printContent += `<tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>11. ДАТА ПРИБЫТИЯ:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dt || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>12. ДАТА УБЫТИЯ:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dateDeparture || '&nbsp;'}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><strong>13. АДМИНИСТРАТОР:</strong></td>
                                                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.adm || '&nbsp;'}</td>
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
                                            var appUrl = '{{ env('APP_URL') }}';
                                            const url = appUrl + `/listok/identify-qr/${row.rowhash}`;
                                            let qrElement = iframe.contentWindow.document.getElementById(`qrcode-${row.regNum}`);
                                            if (qrElement) {
                                                new QRCode(qrElement, {
                                                    text: url,
                                                    width: 80,
                                                    height: 80
                                                });
                                            } else {
                                                console.error(`Element qrcode-${row.regNum} not found`);
                                            }
                                        });

                                        setTimeout(function() {
                                            let printWindow = iframe.contentWindow;
                                            printWindow.onafterprint = function() {
                                                document.body.removeChild(iframe);
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
                                        payment:payment,
                                        paytype:paytype,
                                        comment:comment,
                                        inblack:inBlack,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        if (response.status === 'success') {
                                            $('#listok-table').DataTable().ajax.reload();

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

            $('#listok-table tbody').on('click', 'tr', function() {
                $(this).toggleClass('selected');
            });

            $.contextMenu({
                selector: '#listok-table tbody tr',
                callback: function(key, options) {
                    var row = options.$trigger;
                    var data = table.row(row).data();

                    if (data) {
                        handleAction(key, data);
                    } else {
                        console.warn('No data available for this row.');
                    }
                },
                items: {
                    copy: {
                        name: "Копировать (в буфер обмена)",
                        icon: "fas fa-copy",
                        className: "menu-action",
                        data: { action: "copy" }
                    },
                    info: {
                        name: "Сведения о госте",
                        icon: "fas fa-info-circle",
                        className: "menu-action",
                        data: { action: "info" }
                    },
                    checkout: {
                        name: "CheckOut",
                        icon: "fas fa-plane",
                        className: "menu-action",
                        data: { action: "checkout" }
                    },
                    move: {
                        name: "Переместить в другой номер",
                        icon: "fas fa-network-wired",
                        className: "menu-action",
                        data: { action: "move" }
                    },
                    status: {
                        name: "Изменить статус оплаты",
                        icon: "fas fa-calculator",
                        className: "menu-action",
                        data: { action: "status" }
                    },
                    feedback: {
                        name: "Добавить отзыв",
                        icon: "fas fa-comment-dots",
                        className: "menu-action",
                        data: { action: "feedback" }
                    },
                    print: {
                        name: "Печать листка прибытия",
                        icon: "fas fa-print",
                        className: "menu-action",
                        data: { action: "print" }
                    },
                    tag: {
                        name: "Присвоить тег",
                        icon: "fas fa-tag",
                        className: "menu-action",
                        data: { action: "tag" }
                    },
                    deleteTag: {
                        name: "Удалить тег",
                        icon: "fas fa-times",
                        className: "menu-action",
                        data: { action: "deleteTag" }
                    },
                    extendVisa: {
                        name: "Продлить визу",
                        icon: "fas fa-id-card",
                        className: "menu-action",
                        data: { action: "extendVisa" }
                    }
                },

                events: {
                    show: function(options) {
                        var row = options.$trigger;

                        if (!row.hasClass('selected')) {
                            row.addClass('selected');
                        }

                        table.row(row).select();
                    }
                }
            });


            function handleAction(action, rowData) {
                let textToCopy = '';
                for (let i = 0; i < rowData.length; i++) {
                    const row = rowData[i];
                    textToCopy += `
                    Гость: ${row.guest}
                    Регистрационный номер: ${row.regNum}
                    Дата заезда: ${row.datekpp}
                    Гостиница: ${row.htl}
                `;
                    if (i < rowData.length - 1) {
                        textToCopy += '\n---\n';
                    }
                }
                switch (action) {
                    case 'copy':
                        navigator.clipboard.writeText(textToCopy);
                        Swal.fire({
                            icon: 'success',
                            title: 'Данные скопированы!',
                        });
                        break;

                    case 'info':
                        var filteredFeedbacks = feedbacks.filter(feedback => feedback.pspNumber == rowData.passportNumber);
                        const isInBlackList = filteredFeedbacks.some(feedback => feedback.inBlack === 1);

                        const modalHtml = `
                        <div class="nav nav-tabs" id="guestInfoTabs" role="tablist">
                            <ul class="nav nav-tabs col-md-12">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-tab" data-tab="info" href="#" role="tab">Информация</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="reviews-tab" data-tab="reviews" href="#" role="tab">
                                        Отзывы <span class="badge bg-primary">${filteredFeedbacks.length}</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3">
                                <div id="info" class="tab-pane active">
                                    <p><strong>Гражданство:</strong> ${rowData.ctz} - ${rowData.ctzn}</p>
                                    <p><strong>В черном списке нашей гостиницы:</strong>
                                        <span class="badge bg-${isInBlackList ? 'danger' : 'info'}">
                                            ${isInBlackList ? 'ДА' : 'НЕТ'}
                                        </span>
                                    </p>
                                    <p><strong>В глобальном черном списке:</strong> <span class="badge bg-info">НЕТ</span></p>
                                    <p><strong>Последняя активность в системе E-MEHMON:</strong> Нет данных.</p>
                                    <p><strong>Гость останавливался у нас:</strong> Этот гость у нас 1 раз.</p>
                                </div>
                                <div id="reviews" class="tab-pane" style="display: none;">
                                    <table class="table dataTable row-border compact table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Дата</th>
                                                <th>Отзыв</th>
                                                <th>Гостиница</th>
                                                <th>Администратор</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${filteredFeedbacks.length > 0 ? filteredFeedbacks.map(feedback => `
                                                <tr class="text-center">
                                                    <td>${feedback.created_at || 'Не указано'}</td>
                                                    <td>${feedback.text || 'Не указано'}</td>
                                                    <td>${feedback.htl || 'Не указано'}</td>
                                                    <td>${feedback.adm || 'Не указано'}</td>
                                                </tr>
                                            `).join('') : '<tr><td colspan="4" class="text-center">Отзывов нет</td></tr>'}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `;

                        $.confirm({
                            title: `<i class="fa fa-suitcase"></i> Информация о ${rowData.guest}`,
                            content: modalHtml,
                            boxWidth: '800px',
                            useBootstrap: false,
                            type: 'blue',
                            buttons: {
                                ok: {
                                    text: 'ОТМЕНА',
                                    btnClass: 'btn-danger',
                                }
                            },
                            onOpen: function () {
                                $('#guestInfoTabs .nav-link').on('click', function (e) {
                                    e.preventDefault();
                                    const targetTab = $(this).data('tab');
                                    $('#guestInfoTabs .tab-pane').hide();
                                    $(`#${targetTab}`).show();
                                    $('#guestInfoTabs .nav-link').removeClass('active');
                                    $(this).addClass('active');
                                });
                            }
                        });
                        break;

                    case 'checkout':
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
                        break;

                    case 'move':
                        const selectedMoveRows = $('#listok-table').DataTable().rows({
                            selected: true
                        }).data();

                        if (selectedMoveRows.length !== 1) {
                            Swal.fire({
                                icon: 'warning',
                                title: '',
                                text: 'Пожалуйста, выберите только одного гостя для перемещения.',
                                confirmButtonText: 'ОК',
                                confirmButtonColor: '#3085d6',
                            });
                            break;
                        }


                        const selectedGuestId = selectedMoveRows[0].id;
                        const selectedHtlId = selectedMoveRows[0].id_hotel;
                        const selectedAdmId = selectedMoveRows[0].entry_by;
                        const selectedGuest = selectedMoveRows[0].guest;
                        const selectedOldRoomNum = selectedMoveRows[0].room;

                        $.confirm({
                            title: '',
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
                                                        Выбран гость: <span class="badge bg-primary">${selectedGuest}</span>.
                                                        Вы можете перевести выбранного гостя в другой номер (комнату).
                                                    </p>
                                                </div>
                                                <div class="mt-3">
                                                    <label for="id_room">Номер / Комната:</label>
                                                    <select id="id_room" name="id_room" class="select2 form-select" required>
                                                        <option value="">--- НЕ ВЫБРАНО ---</option>
                                                        @foreach ($rooms as $room)
                            <option value="{{ $room->room_number }}">
                                                            {{ $room->room_number }} - {{ $room->room_type }};
                                                            {{ $room->room_floor }} - этаж;
                                                           {{ $room->living_room }}/{{ $room->beds }}
                            @if (!empty($room->tag))
                            ;({{ $room->tag }})
                                                            @endif
                            </option>
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
                                    btnClass: 'btn-green',
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
                                                guest_id: selectedGuestId,
                                                hotel_id: selectedHtlId,
                                                entry_by: selectedAdmId,
                                                guest: selectedGuest,
                                                old_room_number: selectedOldRoomNum,
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
                                                    window.location.reload();
                                                } else {
                                                    $.alert(response.message);
                                                }
                                            },
                                            error: function() {
                                                $.alert('Произошла ошибка при перемещении гостя.');
                                            }
                                        });
                                    }
                                },
                                cancel: {
                                    text: 'Отмена',
                                    btnClass: 'btn-danger'
                                }
                            },
                            onContentReady: function() {
                                const roomSelect = document.getElementById("id_room");
                                const modalBox = this.$el;

                                if (!roomSelect) {
                                    console.error("Элемент #id_room не найден!");
                                    return;
                                }

                                roomSelect.addEventListener("change", function () {
                                    const selectedOption = roomSelect.options[roomSelect.selectedIndex].text;
                                    const match = selectedOption.match(/(\d+)\/(\d+)/);

                                    if (match) {
                                        const livingRoom = parseInt(match[1]);
                                        const beds = parseInt(match[2]);

                                        if (livingRoom === beds) {
                                            modalBox.css("z-index", "1000");

                                            Swal.fire({
                                                icon: "warning",
                                                title: "Комната заполнена!",
                                                text: "Выберите другую комнату.",
                                                confirmButtonColor: "#d33"
                                            }).then(() => {
                                                modalBox.css("z-index", "99999");
                                            });

                                            roomSelect.value = "";
                                        }
                                    } else {
                                        console.warn("Не удалось извлечь данные о комнате из выбранной опции.");
                                    }
                                });
                            }

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
                                                <label for="СТАТУС ОПЛАТЫ:" class=" control-label text-left"> СТАТУС ОПЛАТЫ: </label>
                                                <select id="paymentStatus" class="form-select">
                                                    <option value="0">--- Выберите статус ---</option>
                                                    <option value="1">Оплачен частично</option>
                                                    <option value="2">Оплачен полностью</option>
                                                    <option value="3">Не оплачен</option>
                                                </select>
                                            </div>
                                            <div class="mt-3">
                                                <label for="СУММА ОПЛАТЫ:" class=" control-label text-left"> СУММА ОПЛАТЫ: </label>
                                                <input id="paymentAmount" name="amount" style="text-align: right" class="form-control" type="number" max="9999999999" min="0" step="0.01" placeholder="0.00 UZS" value="0"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `,
                            buttons: {
                                confirm: {
                                    text: 'Установить',
                                    btnClass: 'btn-green',
                                    action: function() {
                                        const paymentStatus = $('#paymentStatus').val();
                                        const payment = $('#paymentAmount').val();
                                        if (!payment || paymentStatus == '0') {
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
                                                paymentStatus: paymentStatus,
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
                                    text: 'Отмена',
                                    btnClass: 'btn-danger',
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
                table {
                    margin-top: 10px !important;
                    width: 100%;
                    border-collapse: collapse;
                    table-layout: fixed;
                }
                .qrcode {
                    margin-top: 10px !important;
                    margin-left: 27px !important;
                }
                table, th, td {
                    border: 1px solid #000;
                }
                th, td {
                    padding: 5px;
                    text-align: left;
                    height: 15px;
                    vertical-align: middle;
                }
                th:nth-child(1), td:nth-child(1) { width: 30%; }
                th:nth-child(2), td:nth-child(2) { width: 50%; }
                th:nth-child(3), td:nth-child(3) { width: 20%; }
            </style>
        </head>
        <body>
    `;

                        let currentCount = 0;
                        $.each(selectedPrintRows, function(index, row) {
                            if (currentCount === 0) {
                                printContent += `<div class="page">`;
                            }

                            printContent += `
            <table id="childrenTable" style="width: 100%; border: 1px solid black; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">
                <tr>
                    <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height: 50px;">
                    </td>
                    <td colspan="2" style="border: 1px solid black; text-align: center; padding: 5px;">
                        <strong>Гостиница:</strong> ${row.htl || '&nbsp;'}<br>
                        <strong>Регион:</strong> ${row.region || '&nbsp;'}<br>
                        <strong>Адрес:</strong> ${row.htl_address || '&nbsp;'}<br>
                        <strong>№ ком.:</strong> ${row.room || '&nbsp;'}
                    </td>
                    <td colspan="1" style="border: 1px solid black; text-align: center; padding: 5px;">
                        <div class="qrcode" id="qrcode-${row.regNum}"></div>
                        <p>${row.regNum || '&nbsp;'}</p>
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>1. ФАМИЛИЯ:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.surname || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>2. ИМЯ:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.firstname || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>3. ОТЧЕСТВО:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.lastname || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>4. ДАТА РОЖДЕНИЯ:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.datebirth || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>5. ГРАЖДАНСТВО:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.ctzn || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>6. ДОКУМЕНТ:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.passportType || '&nbsp;'}: ${row.passport_full || ''}; Выдан:${formatDate(row.datePassport)} / ${row.PassportIssuedBy || 'Не указано'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>7. ВИЗА:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">Тип: ${row.tb_visa || '&nbsp;'}; № ${row.tb_visanm || '&nbsp;'}; ${row.tb_visafrom || '&nbsp;'} - ${row.tb_visato || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>8. ОТКУДА ПРИБЫЛ:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.ctzn || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>9. КПП:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">№ ${row.kppNumber || '&nbsp;'}; Дата: ${row.dateKPP || '&nbsp;'} </td>
                </tr>
                <tr>
                    <td colspan="5" style="border: 1px solid black; padding: 5px;"><strong>10. Вместе с ним/ней прибыли дети до 16 лет</strong></td>
                </tr>
        `;

                            const filteredChildren = childrenData.filter(child => child.id === row.id);

                            if (filteredChildren.length > 0) {
                                printContent += `
                <tr>
                    <td><strong>Имя</strong></td>
                    <td colspan="2"><strong>Дата рождения</strong></td>
                    <td><strong>Пол</strong></td>
                </tr>
            `;

                                filteredChildren.forEach(child => {
                                    printContent += `
                    <tr>
                        <td>${child.child_name}</td>
                        <td colspan="2">${child.child_dateBirth ? new Date(child.child_dateBirth).toLocaleDateString('ru-RU') : '--/--/----'}</td>
                        <td>${child.child_gender}</td>
                    </tr>
                `;
                                });
                            } else {
                                printContent += `
                <tr>
                    <td colspan="5" style="text-align: center;"><strong>--нет--</strong></td>
                </tr>
            `;
                            }

                            printContent += `
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>11. ДАТА ПРИБЫТИЯ:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dt || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>12. ДАТА УБЫТИЯ:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.dateDeparture || '&nbsp;'}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px;"><strong>13. АДМИНИСТРАТОР:</strong></td>
                    <td colspan="4" style="border: 1px solid black; padding: 5px;">${row.adm || '&nbsp;'}</td>
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
                                var appUrl = '{{ env('APP_URL') }}';
                                const url = appUrl + `/listok/identify-qr/${row.rowhash}`;
                                let qrElement = iframe.contentWindow.document.getElementById(`qrcode-${row.regNum}`);
                                if (qrElement) {
                                    new QRCode(qrElement, {
                                        text: url,
                                        width: 80,
                                        height: 80
                                    });
                                } else {
                                    console.error(`Element qrcode-${row.regNum} not found`);
                                }
                            });

                            setTimeout(function() {
                                let printWindow = iframe.contentWindow;
                                printWindow.onafterprint = function() {
                                    document.body.removeChild(iframe);
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
                            Swal.fire({
                                icon: 'warning',
                                title: '',
                                text: 'Пожалуйста, выберите только одного гостя для отзыва!',
                                confirmButtonText: 'ОК',
                                confirmButtonColor: '#3085d6',
                            });
                            break;
                        }
                        const feedbackdata = selectedFeedbackRows[0];

                        $.confirm({
                            title: 'Добавить Отзыв',
                            type: "blue",
                            content: `
                            <p>${feedbackdata.ctz} ${feedbackdata.guest}</p>
                            <textarea class="form-control" id="feedbackText" rows="4" placeholder="Введите отзыв"></textarea>
                            <div class="mt-3">
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
                                    btnClass: 'btn-green',
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
                                                    window.location.reload();
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
                                ОТМЕНА: {
                                    btnClass: 'btn-danger',
                                }
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
                                    text: 'ДОБАВИТЬ',
                                    btnClass: 'btn-green',
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
                                            url: '{{route('listok.deleteTag')}}',
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
                                    btnClass: 'btn-danger'
                                }
                            }
                        });
                        break;

                    case 'deleteTag':
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
                                    btnClass: 'btn-blue'
                                }
                            }
                        });
                        break;

                    case 'extendVisa':
                        $.confirm({
                            title: 'Продление визы.',
                            type: 'blue',
                            content: `
                            <div>
                                <p>${rowData.ctz} ${rowData.guest}</p>
                                <form id="visa-form">
                                    <div style="margin-bottom: 10px;">
                                        <label for="dateVisaOn">Срок визы с:</label>
                                        <input placeholder='dd.mm.yyyy' id="dateVisaOn" class='form-control input-mask-date' data-inputmask=\"'alias': 'datetime'\" data-inputmask-inputformat='dd.mm.yyyy' inputmode='numeric' name='dateVisaOn' value=''>
                                    </div>
                                    <div>
                                        <label for="dateVisaOff">Срок визы до:</label>
                                        <input placeholder='dd.mm.yyyy' id="dateVisaOff" class='form-control input-mask-date' data-inputmask=\"'alias': 'datetime'\" data-inputmask-inputformat='dd.mm.yyyy' inputmode='numeric' name='dateVisaOff' value=''>
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
                                    btnClass: 'btn-danger'
                                }
                            },
                            onContentReady: function() {
                                $(":input").inputmask();
                                const today = new Date();
                                const threeMonthsAgo = new Date();
                                threeMonthsAgo.setMonth(today.getMonth() - 3);

                                const formatDate = (date) => {
                                    const day = String(date.getDate()).padStart(2, '0');
                                    const month = String(date.getMonth() + 1).padStart(2, '0');
                                    const year = date.getFullYear();
                                    return `${day}.${month}.${year}`;
                                };

                                const arrivalFromInput = $('input[name="dateVisaOn"]');
                                const arrivalToInput = $('input[name="dateVisaOff"]');

                                arrivalFromInput.attr('min', formatDate(threeMonthsAgo));
                                arrivalFromInput.attr('max', formatDate(today));

                                arrivalFromInput.on('change', function() {
                                    const selectedDate = new Date($(this).val().split('.').reverse().join('-'));
                                    if (selectedDate < threeMonthsAgo || selectedDate > today) {
                                        $.alert('Дата должна быть в диапазоне от ' + formatDate(threeMonthsAgo) + ' до ' + formatDate(today));
                                        $(this).val('');
                                    }
                                });

                                arrivalFromInput.add(arrivalToInput).on('change', function() {
                                    const fromDate = new Date(arrivalFromInput.val().split('.').reverse().join('-'));
                                    const toDate = new Date(arrivalToInput.val().split('.').reverse().join('-'));

                                    if (arrivalFromInput.val() && arrivalToInput.val() && toDate <= fromDate) {
                                        $.alert('Дата прибытия не должна быть больше, чем дата убытия');
                                        arrivalToInput.val('');
                                    }
                                });

                            }
                        });
                        break;

                    default:
                        $.alert('Действие не реализовано.');
                }
            }

            $(document).on('click', function(e) {
                $.contextMenu('hide');
            });
        });


    </script>

    {{-- dbclick --}}
    <script>
        $(document).ready(function() {
            $('#listok-table tbody').on('click', '.open-menu-btn', function(event) {
                event.stopPropagation();
                var table = $('#listok-table').DataTable();
                var data = table.row($(this).closest('tr')).data();
                var children = childrenData.filter(child => child.id === data.id);
                var filteredFeedbacks = feedbacks.filter(feedback => feedback.pspNumber == data.passportNumber);
                var roomHistory = roomhistory.filter(room => room.id_reg == data.id);
                var bronRoom = bronroom.filter(bron => bron.id == data.book_id);
                $.confirm({
                    title: `Регистрационный номер: <span class="badge bg-primary">${data.regNum}</span>`,
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
                            <a class="nav-link" data-bs-toggle="tab" href="#feedback" role="tab">Отзывы  <span class="badge bg-primary">${filteredFeedbacks.length}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#children-info" role="tab">Информация о детях <span class="badge bg-primary">${children.length}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#change-room" role="tab">Номера <span class="badge bg-primary">${roomHistory.length}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#bron-room" role="tab">Бронирования <span class="badge bg-primary">${bronRoom.length}</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#audit" role="tab">Аудит <span class="badge bg-primary">0</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-10">
                                    <table class="table dataTable row-border compact table-hover" id="db-click-table">
                                        <tbody>
                                            <tr>
                                                <th>Рег. №:</th>
                                                <td>${data.regNum || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>ПИНФЛ:</th>
                                                <td>${data.pinfl || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Фамилия, Имя, Отчество:</th>
                                                <td>${data.guest || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Дата рождения:</th>
                                                <td>${formatDate(data.datebirth) || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Страна рождения:</th>
                                                <td>${data.ctz || 'Не указано'} ${data.ctzn || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Гражданство:</th>
                                                <td>${data.ctz || 'Не указано'} ${data.ctzn || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Откуда:</th>
                                                <td>${data.ctz || 'Не указано'} ${data.ctzn || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Проживает в номере:</th>
                                                <td>${data.room || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Сколько дней будет проживать:</th>
                                                <td>${data.wdays || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Статус оплаты:</th>
                                                <td>
                                                    ${data.payed === 1
                                                ? 'Оплачен частично'
                                                : data.payed === 2
                                                    ? 'Оплачен полностью'
                                                    : data.payed === 3
                                                        ? 'Неоплачен'
                                                        : 'Не указано'}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Сумма (UZS):</th>
                                                <td>${data.amount || '0,00'}</td>
                                            </tr>
                                            <tr>
                                                <th>Пол:</th>
                                                <td>${data.sex === 'M' ? 'Мужчина' : 'Женщина'}</td>
                                            </tr>
                                            <tr>
                                                <th>Прибыл:</th>
                                                <td>${data.dt || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Гостиница:</th>
                                                <td>${data.htl || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Зарегистрировал:</th>
                                                <td>${data.adm || 'Не указано'}</td>
                                            </tr>
                                            <tr>
                                                <th>Обновлено:</th>
                                                <td>${data.updated_at || 'Не указано'}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-2">
                                    <div id="qrcode-${data.regNum}"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="additional-info" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover" id="db-click-table">
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
                                        <td>№ ${data.kppNumber || 'Не указано'}; Дата: ${formatDate(data.dateKPP)}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                       <div class="tab-pane fade" id="feedback" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Дата</th>
                                            <th>Отзыв</th>
                                            <th>Гостиница</th>
                                            <th>Администратор</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                        ${filteredFeedbacks.length > 0 ? filteredFeedbacks.map(feedback => `
                                            <tr class="text-center">
                                                <td>${feedback.created_at || 'Не указано'}</td>
                                                <td>${feedback.text || 'Не указано'}</td>
                                                <td>${feedback.htl || 'Не указано'}</td>
                                                <td>${feedback.adm || 'Не указано'}</td>
                                            </tr>
                                        `).join('') : '<tr><td colspan="4" class="text-center">Отзывов нет</td></tr>'}
                                    </tbody>
                            </table>
                            <div class="d-flex justify-content-end mt-3">
                                <button id="add-review-btn" class="btn btn-outline-warning rounded">
                                    <i class="fas fa-comment"></i> Добавить отзыв
                                </button>
                            </div>
                        </div>


                       <div class="tab-pane fade" id="children-info" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>ФИО ребёнка</th>
                                        <th>Дата рождения</th>
                                        <th>Пол</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${children.length > 0 ? children.map(child => `
                                        <tr class="text-center">
                                            <td>${child.child_name || 'Не указано'}</td>
                                            <td>${formatDate(child.child_dateBirth) || 'Не указано'}</td>
                                            <td>${child.child_gender === 'M' ? 'Мальчик' : child.child_gender === 'F' ? 'Девочка' : 'Не указано'}</td>
                                        </tr>
                                    `).join('') : '<tr><td colspan="3" class="text-center">Нет информации о детях</td></tr>'}
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="change-room" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Дата</th>
                                            <th>Комната</th>
                                            <th>Гостиница</th>
                                            <th>Администратор</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${roomHistory.length > 0 ? roomHistory.map(room => `
                                            <tr class="text-center">
                                                <td>${room.created_at || 'Не указано'}</td>
                                                <td>${room.events || 'Не указано'}</td>
                                                <td>${room.htl || 'Не указано'}</td>
                                                <td>${room.adm || 'Не указано'}</td>
                                            </tr>
                                        `).join('') : '<tr><td colspan="4" class="text-center">Нет данных! Гость ни разу не менял номер (комнату)!</td></tr>'}
                                    </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="bron-room" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover">
                                    <thead>
                                        <tr class="text-center">
                                        <tr class="text-center">
                                            <th>Дата бронирования</th>
                                            <th>Номер телефона</th>
                                            <th>Гостиница</th>
                                            <th>Организация</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${bronRoom.length > 0 ? bronRoom.map(bron => `
                                            <tr class="text-center">
                                                <td>${bron.bron_date || 'Не указано'}</td>
                                                <td>${bron.phone_number || 'Не указано'}</td>
                                                <td>${bron.htl || 'Не указано'}</td>
                                                <td>${bron.org_name || 'Не указано'}</td>
                                            </tr>
                                        `).join('') : '<tr><td colspan="4" class="text-center">Нет данных! Гость ни разу не бронировал номер (комнату)!</td></tr>'}
                                    </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="audit" role="tabpanel">
                            <table class="table dataTable row-border compact table-hover">
                                <thead>
                                    <tr class="text-center">
                                        <th>Тип события</th>
                                        <th>Гостиница</th>
                                        <th>Администратор</th>
                                        <th>Дата</th>
                                        <th>Изменения</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="7" class="text-center">Загрузка...</td></tr>
                                </tbody>
                            </table>
                        </div>
                </div>
            `,
                    type: 'blue',
                    boxWidth: '75%',
                    useBootstrap: false,
                    buttons: false,
                    onContentReady: function() {
                        $.ajax({
                            url: '{{ route("listok.getAuditLogs") }}',
                            method: 'GET',
                            data: {
                                entity_id: data.id,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success && response.data.length > 0) {
                                    var rows = response.data.map(function(log) {
                                        return `
                                            <tr class="text-center">
                                                <td>${log.event_type}</td>
                                                <td>${log.hotel_name}</td>
                                                <td>${log.user_name}</td>
                                                <td>${log.event_time}</td>
                                                <td>${log.changes}</td>
                                            </tr>
                                        `;
                                    }).join('');
                                    $('#audit tbody').html(rows);
                                    $('a[href="#audit"] .badge').text(response.count);
                                } else {
                                    $('#audit tbody').html('<tr><td colspan="7" class="text-center">Нет данных!</td></tr>');
                                    $('a[href="#audit"] .badge').text(0);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Ошибка при загрузке данных:', error); // Отладка
                                $('#audit tbody').html('<tr><td colspan="7" class="text-center text-danger">Ошибка загрузки данных!</td></tr>');
                                $('a[href="#audit"] .badge').text(0);
                            }
                        });



                        const qrCodeContainer = document.getElementById(`qrcode-${data.regNum}`);
                        var appUrl = '{{ env('APP_URL') }}';
                        const url = appUrl + `/listok/identify-qr/${data.rowhash}`;
                        if (qrCodeContainer) {
                            new QRCode(qrCodeContainer, {
                                text: url,
                                width: 200,
                                height: 200
                            });
                        } else {
                            console.error("QR-код контейнер не найден.");
                        }




                        this.$content.find('#add-review-btn').on('click', function() {
                            $.confirm({
                                title: 'Добавить Отзыв',
                                type: "blue",
                                content: `
                                    <p>${data.ctz} ${data.guest}</p>
                                    <textarea class="form-control" id="feedbackText" rows="4" placeholder="Введите отзыв"></textarea>
                                    <div class="mt-3">
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
                                        btnClass: 'btn-green',
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
                                                    id_citizen: data.id_citizen,
                                                    passportSerial: data.passportSerial,
                                                    passportNumber: data.passportNumber,
                                                    entry_by: data.entry_by,
                                                    person_id: data.id_person,
                                                    feedback: feedback,
                                                    blacklist: blacklistStatus,
                                                    _token: $('meta[name="csrf-token"]').attr('content')
                                                },
                                                success: function(response) {
                                                    if (response.success) {
                                                        const newFeedback = {
                                                            created_at: response.data.created_at || 'Не указано',
                                                            text: response.data.feedback || 'Не указано',
                                                            htl: data.htl || 'Не указано',
                                                            adm: data.adm || 'Не указано',
                                                        };
                                                        filteredFeedbacks.push(newFeedback);

                                                        const tbodyContent = filteredFeedbacks.length > 0
                                                            ? filteredFeedbacks.map(feedback => `
                                                                <tr class="text-center">
                                                                    <td>${feedback.created_at}</td>
                                                                    <td>${feedback.text}</td>
                                                                    <td>${feedback.htl}</td>
                                                                    <td>${feedback.adm}</td>
                                                                </tr>
                                                            `).join('')
                                                            : '<tr><td colspan="4" class="text-center">Отзывов нет</td></tr>';
                                                        $('#feedback tbody').html(tbodyContent);

                                                        $('a[href="#feedback"] .badge').text(filteredFeedbacks.length);

                                                    } else {
                                                        $.alert({
                                                            title: 'Ошибка!',
                                                            content: response.message || 'Ошибка добавления отзыва!',
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
                                    ОТМЕНА: {
                                        btnClass: 'btn-danger',
                                    }
                                }
                            });
                        })

                    }
                });
            });
        });
    </script>

    {{-- Global search --}}
    <script>
        $(document).ready(function() {
            if (localStorage.getItem('globalFilters')) {
                let filters = JSON.parse(localStorage.getItem('globalFilters'));
                for (let key in filters) {
                    $('[name="' + key + '"]').val(filters[key]);
                }
            }

            $('#toggle-filter-btn').on('click', function() {
                let savedFilters = localStorage.getItem('globalFilters');
                let filterValues = savedFilters ? JSON.parse(savedFilters) : {};

                $.confirm({
                    title: 'Глобальный поиск',
                    boxWidth: '900px',
                    useBootstrap: false,
                    type: "blue",
                    typeAnimated: true,
                    closeIcon: true,
                    content: "<div class='global-search'>" +
                        "<form id='global-search-form'>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ФАМИЛИЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' id='surname' class='form-control' placeholder='ФАМИЛИЯ' name='surname' value='" + (filterValues.surname || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ИМЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' id='first_name' class='form-control' placeholder='ИМЯ' name='firstname' value='" + (filterValues.firstname || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ОТЧЕСТВО:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' id='last_name' class='form-control' placeholder='ОТЧЕСТВО' name='lastname' value='" + (filterValues.lastname || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ДАТА РОЖДЕНИЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input placeholder='dd.mm.yyyy' class='form-control input-mask-date' " +
                        "data-inputmask=\"'alias': 'datetime'\" data-inputmask-inputformat='dd.mm.yyyy' inputmode='numeric' " +
                        "name='datebirth' id='input-birthdate' value='" + (filterValues.datebirth || '') + "'>" +
                        "</div>" +
                        "</div>"+
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ГРАЖДАНСТВО:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<select class='form-control' name='citizenship' id='citizenship'>" +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        "@foreach ($ctzns as $ctzn)" +
                        "<option value='{{ $ctzn->id }}' " + (filterValues.citizenship == '{{ $ctzn->id }}' ? 'selected' : '') + ">{{ $ctzn->name }}</option>" +
                        "@endforeach" +
                        "</select>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ПЕРИОД ПРИБЫТИЯ:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<div class='d-flex'>" +
                        "<span style='margin-top: 6px; margin-bottom: 6px; margin-right: 12px; font-size: 10px; color: #333; background-color: #9af3ec ; padding: 8px 12px; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);'>С</span> " + "<input placeholder='dd.mm.yyyy' class='form-control input-mask-date me-2' data-inputmask=\"'alias': 'datetime'\" data-inputmask-inputformat='dd.mm.yyyy' inputmode='numeric' name='arrival_from' value='" + (filterValues.arrival_from || '') + "'>" +
                        "<span style='margin-top: 6px; margin-bottom: 6px; margin-right: 12px; font-size: 10px; color: #333; background-color: #9af3ec ; padding: 8px 12px; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);'>ПО</span>" + "<input placeholder='dd.mm.yyyy' class='form-control input-mask-date' data-inputmask=\"'alias': 'datetime'\" data-inputmask-inputformat='dd.mm.yyyy' inputmode='numeric' name='arrival_to' value='" + (filterValues.arrival_to || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ПАСПОРТ СЕРИЯ №:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' placeholder='СЕРИЯ/НОМЕР' id='passport_number' name='passport_number' value='" + (filterValues.passport_number || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='admin'>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>РЕГИОН:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<select class='form-control' name='region' id='region'>" +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        "@foreach ($regions as $region)" +
                        "<option value='{{ $region->id }}' " + (filterValues.region == '{{ $region->id }}' ? 'selected' : '') + ">{{ $region->name }}</option>" +
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
                        "<option value='{{ $hotel->id }}' " + (filterValues.hotel == '{{ $hotel->id }}' ? 'selected' : '') + ">{{ $hotel->name }}</option>" +
                        "@endforeach" +
                        "</select>" +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "</form>" +
                        "</div>",
                    buttons: {
                        ПОИСК: {
                            btnClass: 'btn-green',
                            action: function() {
                                var filters = $('#global-search-form').serializeArray();
                                var filterObject = {};
                                filters.forEach(function(field) {
                                    filterObject[field.name] = field.value;
                                });

                                localStorage.setItem('globalFilters', JSON.stringify(filterObject));

                                $('#regNum-filter').val('');
                                $('#room-filter').val('');
                                $('#tag-filter').val('');
                                $('#listok-table').DataTable().ajax.reload(null, false);
                            },
                        },
                        Очистить: {
                            text: '<i class="fas fa-filter"></i> <i class="fas fa-times"></i>',
                            btnClass: 'btn-danger me-3',
                            action: function() {
                                $('#regNum-filter').val('');
                                $('#room-filter').val('');
                                $('#tag-filter').val('');
                                $('#global-search-form input').val('');
                                $('#global-search-form select').val('').trigger('change');

                                var table = $('#listok-table').DataTable();
                                table.ajax.params = {};
                                table.search('').columns().search('');
                                table.ajax.reload();

                                localStorage.removeItem('globalFilters');
                            }
                        },

                    },
                    onContentReady: function() {
                        const savedFilters = JSON.parse(localStorage.getItem('globalFilters') || '{}');
                        if (savedFilters) {
                            Object.keys(savedFilters).forEach(key => {
                                $(`[name="${key}"]`).val(savedFilters[key]);
                            });
                        }

                        $(":input").inputmask();

                        $('#citizenship, #region, #hotel').selectize({
                            create: true,
                            sortField: 'text',
                            closeAfterSelect: true,
                            highlight: true,
                        });

                        $('#passport_number').on('input', function() {
                            var maxLength = 20;
                            $(this).val($(this).val().replace(/[^a-zA-Z0-9\s]/g, '').substring(0, maxLength)
                                .toUpperCase()
                            );
                        });

                        $('#surname, #last_name, #first_name').on('input', function() {
                            var maxLength = 30;
                            $(this).val($(this).val().replace(/[^a-zA-Z\s]/g, '').substring(0, maxLength)
                                .toUpperCase()
                            );
                        });

                        const today = new Date();
                        const threeMonthsAgo = new Date();
                        threeMonthsAgo.setMonth(today.getMonth() - 3);


                        const arrivalFromInput = $('input[name="arrival_from"]');
                        const arrivalToInput = $('input[name="arrival_to"]');

                        const birthdateInput = $('#input-birthdate');
                        birthdateInput.on('change', function() {
                            const selectedDate = new Date($(this).val().split('.').reverse().join('-'));
                            if (selectedDate > today) {
                                $.alert('Дата рождения не может быть в будущем');
                                $(this).val('');
                            }
                        });


                        arrivalFromInput.add(arrivalToInput).on('change', function() {
                            const fromDate = new Date(arrivalFromInput.val().split('.').reverse().join('-'));
                            const toDate = new Date(arrivalToInput.val().split('.').reverse().join('-'));

                            if (arrivalFromInput.val() && arrivalToInput.val() && toDate <= fromDate) {
                                $.alert('Дата прибытия "По" должна быть больше, чем "С"');
                                arrivalToInput.val('');
                            }
                        });

                    }
                });
            });

            $('#clear-filter-btn').on('click', function () {
                $('#regNum-filter').val('');
                $('#room-filter').val('');
                $('#tag-filter').val('');
                $('#global-search-form input').val('');
                $('#global-search-form select').val('').trigger('change');

                var table = $('#listok-table').DataTable();
                table.ajax.params = {};
                table.search('').columns().search('');
                table.ajax.reload();

                localStorage.removeItem('globalFilters');
            });
        });


    </script>

    {{-- Convert excel --}}
    <script>
        $('#excelButton').on('click', function () {
            const table = $('#listok-table').DataTable();

            const selectedData = table.rows({ selected: true }).data().toArray();

            const headers = [
                "РЕГ.№",
                "Ф.И.О ГОСТЯ",
                "ГРАЖДАНСТВО",
                "ПАСПОРТ",
                "НОМЕР",
                "ПРИБЫЛ",
                "ПРИБЫЛ_НА",
                "ГОСТИНИЦА",
                "РЕГИОН",
                "ОПЛАТА",
                "ТЕГ",
                "АДМИНИСТРАТОР",
            ];

            const ws_data = [headers];
            selectedData.forEach(row => {
                ws_data.push([
                    row.regNum || "",
                    row.guest || "",
                    row.ctzn || "",
                    row.passport_full || "",
                    row.room || "",
                    row.dt || "",
                    row.wdays || "",
                    row.htl || "",
                    row.region || "",
                    row.amount || "",
                    row.tag || "",
                    row.adm || "",
                ]);
            });

            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(ws_data);

            const colWidths = headers.map((header, index) => {
                let maxLength = header.length;
                ws_data.forEach(row => {
                    const value = row[index] || "";
                    const length = value.toString().length;
                    if (length > maxLength) {
                        maxLength = length;
                    }
                });
                return { wch: maxLength + 2 };
            });

            ws['!cols'] = colWidths;

            XLSX.utils.book_append_sheet(wb, ws, 'listok');

            XLSX.writeFile(wb, 'UZBEKTURIZM.xlsx');
        });

    </script>

@endsection
@section('header_title', 'Листок прибития')
@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <div class="card" id="user">
                    <div class="card-nav d-flex justify-content-between align-items-center">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <div class="container mb-3">
                                <div class="col-md-12">
                                    <a href="/listok/create" class="btn btn-outline-primary rounded me-2">
                                        <i class="fas fa-plus"></i>
                                    </a>

                                    <button id="deleteButton" type="button" class="btn btn-outline-danger rounded me-2">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <button id="checkout" type="button" class="btn btn-outline-danger rounded me-2">
                                        <i class="fa fa-plane"></i>
                                    </button>

                                    <button type="button" class="btn btn-outline-info rounded me-2" id="toggle-filter-btn">
                                        <i class="fa fa-filter"></i>
                                    </button>

                                    <button type="button" class="btn btn-outline-info rounded me-2" id="toggle-fast-filter-btn">
                                        <i class="fa fa-search-plus"></i>
                                    </button>

                                    <button type="button" class="btn btn-outline-danger rounded me-2" id="clear-filter-btn">
                                        <i class="fas fa-filter"></i> <i class="fas fa-times"></i>
                                    </button>

                                    <button id="excelButton" type="button" class="btn btn-outline-success rounded me-2">
                                        <i class="fas fa-file-excel"></i>
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
                            <li><a href="#" class="menu-action" data-action="deleteTag"><i id="icon-cont"
                                        class="fas fa-times"></i>Удалить тег</a></li>
                            <li><a href="#" class="menu-action" data-action="extendVisa"><i id="icon-cont"
                                        class="fas fa-id-card"></i>Продлить визу</a></li>
                        </ul>
                    </div>

                    <div id="listok-table-container" class="loading-overlay">
                        <div id="custom-loading">
                            <div class="spinner"></div>
                        </div>
                        <table class="table bg-gradient-info dataTable row-border table-hover" id="listok-table" style="width: 100%">
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
