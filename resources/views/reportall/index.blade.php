@extends('layouts.app')
@section('header_title', 'Отчёты')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.0/dist/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <style>
        .ajaxLoading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: .3em;
        }

        #error-container{
            margin: 20px;

        }
        #report-content{
            margin: 20px;
        }
        .modal-body{
            width: 80%;
            margin-left: 40px;
        }
        .pull-right{
            float: right;
        }
        .tips{
            background:#ffcc00;
            color:#000
        }
        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-left: none;
            transition: background-color 0.3s ease;
        }

        .input-group-text:hover {
            background-color: #e9ecef;
        }

        .input-group {
            width: auto;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            position: relative;
            font-family: 'Open Sans', sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .alert .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 21px;
            color: #721c24;
            opacity: 0.7;
        }

        .alert i {
            margin-right: 10px;
            color: #721c24;
        }

        .alert small {
            font-size: 14px;
            color: #444;
        }

        .alert span {
            font-size: 14px;
            color: #000;
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        document.getElementById('reportAll').addEventListener('click', function() {
            const reportType = document.getElementById('reportType').value;
            const region = document.getElementById('region').value;
            const hotels = document.getElementById('hotels').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const year = document.getElementById('year_num').value;
            const month_num = document.getElementById('month_num').value;
            const alert = document.querySelector('.alert');
            if (alert) {
                closeAlert(alert);
            }

            let requestData = {};

            switch (reportType) {
                case '777':
                    requestData = {
                        id_reports: reportType
                    };
                    break;

                case '950':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels
                    };
                    break;

                case '210':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        year_num: year
                    };
                    break;

                case '801':
                    requestData = {
                        id_reports: reportType,
                        id_region: region
                    };
                    break;

                case '200':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        year_num: year
                    };
                    break;

                case '205':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        year_num: year
                    };
                    break;

                case '10':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '20':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '30':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '40':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '50':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '60':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '70':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '80':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate,
                        year_num: year,
                        month_num: month_num
                    };
                    break;
                case '90':
                    requestData = {
                        id_reports: reportType,
                        year_num: year,
                        month_num: month_num
                    };
                    break;
                case '100':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        year_num: year
                    };
                    break;
                case '110':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;

                case '111':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '120':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '130':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '140':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '150':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
                case '800':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;

                case '900':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        month_num: month_num,
                        year_num: year
                    };
                    break;

                case '5010':
                case '5015':
                    requestData = {
                        id_reports: reportType,
                        dateFrom: startDate,
                        dateTo: endDate,
                        id_region: region,
                        id_hotel: hotels
                    };
                    break;

                case '5000':
                    requestData = {
                        id_reports: reportType,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;

                case '6103':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        year_num: year
                    };
                    break;

                case '2000': // Отчёт 2000
                    requestData = {
                        id_reports: reportType,
                        id_region: region || "", // Если регион не выбран, передаём пустую строку
                        id_hotel: hotels || ""   // Если отель не выбран, передаём пустую строку
                    };
                    break;

                case '1000':
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;

                default:
                    requestData = {
                        id_reports: reportType,
                        id_region: region,
                        id_hotel: hotels,
                        dateFrom: startDate,
                        dateTo: endDate
                    };
                    break;
            }

            document.getElementById('showLoading2').style.display = 'block';
            document.getElementById('report-content').innerHTML = '';
            $('#settingsModal').modal('hide');
            fetch('/get-report-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(requestData)
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.error || 'Ошибка сети или сервера');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('showLoading2').style.display = 'none';
                    buildTable(data, reportType);
                    resetModalFields()


                })
                .catch(error => {
                    const errorMessage = error.message || 'Произошла ошибка при загрузке данных';
                    const alertHtml = `
                        <div class="alert alert-danger" style="margin-top:10px;">
                            <button data-dismiss="alert" class="close" type="button" style="background: transparent; border: none; color: #000; font-size: 24px; font-weight: bold; cursor: pointer; padding: 0; position: absolute; top: 10px; right: 10px; transition: color 0.3s ease;" onclick="closeAlert(this)">×</button>
                            <i class="fas fa-exclamation-circle" style="font-weight:700; font-size:20px;"></i>
                            <small>
                                <span style="font-weight:600; color:#444;font-style:italic;">
                                    <strong>&nbsp;&nbsp;Результат: </strong>
                                </span>
                            </small>
                            <span style="font-weight:700; color:#000;font-size:12px;font-family: 'Open+Sans', sans-serif;">
                                ${errorMessage}
                            </span>
                        </div>
                    `;

                    const errorContainer = document.getElementById('error-container');
                    errorContainer.innerHTML = alertHtml;
                    document.getElementById('showLoading2').style.display = 'none';
                    $('#settingsModal').modal('hide');

                });



        });
        function closeAlert(button) {
            const alert = button.closest('.alert');
            alert.style.display = 'none';
        }

        function resetModalFields() {
            document.getElementById('reportType').value = '';
            document.getElementById('region').value = '';
            document.getElementById('hotels').value = '';
        }


        function buildTable(data, reportType) {
            const reportContent = document.getElementById('report-content');
            reportContent.innerHTML = '<table id="reportTable" class="display" style="width:100%"></table>';

            let columns = [];
            let excelTitle = '';

            switch (reportType) {
                case '777':
                    if (data.hotels && data.hotels.length > 0) {
                        reportContent.innerHTML += '<h3>Гостиницы</h3>';
                        reportContent.innerHTML += '<table id="reportTableHotels" class="display" style="width:100%"></table>';
                        $('#reportTableHotels').DataTable({
                            data: data.hotels,
                            columns: Object.keys(data.hotels[0]).map(key => ({ title: key, data: key })),
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excel',
                                    title: 'Отчёт 777: Гостиницы'
                                }
                            ]
                        });
                    }

                    // Создаем таблицу для местных туристов
                    if (data.local && data.local.length > 0) {
                        reportContent.innerHTML += '<h3>Местные туристы</h3>';
                        reportContent.innerHTML += '<table id="reportTableLocal" class="display" style="width:100%"></table>';
                        $('#reportTableLocal').DataTable({
                            data: data.local,
                            columns: Object.keys(data.local[0]).map(key => ({ title: key, data: key })),
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excel',
                                    title: 'Отчёт 777: Местные туристы'
                                }
                            ]
                        });
                    }

                    // Создаем таблицу для не местных туристов
                    if (data.non_local && data.non_local.length > 0) {
                        reportContent.innerHTML += '<h3>Не местные туристы</h3>';
                        reportContent.innerHTML += '<table id="reportTableNonLocal" class="display" style="width:100%"></table>';
                        $('#reportTableNonLocal').DataTable({
                            data: data.non_local,
                            columns: Object.keys(data.non_local[0]).map(key => ({ title: key, data: key })),
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excel',
                                    title: 'Отчёт 777: Не местные туристы'
                                }
                            ]
                        });
                    }

                    // Создаем таблицу для самостоятельных туристов
                    if (data.st && data.st.length > 0) {
                        reportContent.innerHTML += '<h3>Самостоятельные туристы</h3>';
                        reportContent.innerHTML += '<table id="reportTableST" class="display" style="width:100%"></table>';
                        $('#reportTableST').DataTable({
                            data: data.st,
                            columns: Object.keys(data.st[0]).map(key => ({ title: key, data: key })),
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excel',
                                    title: 'Отчёт 777: Самостоятельные туристы'
                                }
                            ]
                        });
                    }
                    break;

                case '800':
                    columns = [
                        { title: 'Название региона', data: 'НАЗВАНИЕ РЕГИОНА' },
                        { title: 'Кол-во иностранцев', data: 'КОЛ-ВО ИНОСТРАНЦЕВ' }
                    ];
                    excelTitle = 'Отчёт 800: Количество иностранцев по регионам';
                    break;

                case '110':
                    columns = [
                        { title: 'Название района', data: 'НАЗВАНИЕ РАЙОНА' },
                        { title: 'Всего', data: 'ВСЕГО' },
                        { title: 'Работа', data: 'РАБОТА' },
                        { title: 'Учёба', data: 'УЧЁБА' },
                        { title: 'Турист', data: 'ТУРИСТ' },
                        { title: 'Частный', data: 'ЧАСТНЫЙ' },
                        { title: 'Другое', data: 'ДРУГОЕ' },
                        { title: 'Кол-во гостиниц', data: 'КОЛ-ВО ГОСТИНИЦ' }
                    ];
                    excelTitle = 'Отчёт 12: Статистика по районам';
                    break;
                case '950':
                    columns = [
                        { title: 'Регион', data: 'rgn' },
                        { title: 'Район', data: 'dst' },
                        { title: 'Гостиница', data: 'hotel' },
                        { title: 'Тип', data: 'htl_tp' },
                        { title: 'ИНН', data: 'inn' },
                        { title: 'Номера', data: 'rooms' },
                        { title: 'Койки', data: 'beds' }
                    ];
                    excelTitle = 'Отчёт 950: Список гостиниц';
                    break;

                case '210':
                    columns = [
                        { title: 'Средство размещения', data: 'СРЕДСТВА РАЗМЕЩЕНИЯ' },
                        { title: 'Тип', data: 'ТИП' },
                        { title: 'Категории', data: 'КАТЕГОРИИ' },
                        { title: 'Всего', data: 'ВСЕГО:' },
                        { title: 'Ночи: 1-3', data: 'НОЧИ 1-3' },
                        { title: 'Ночи: 4-7', data: 'НОЧИ 4-7' },
                        { title: 'Ночи: 8-28', data: 'НОЧИ 8-28' },
                        { title: 'Ночи: 29-91', data: 'НОЧИ 29-91' },
                        { title: 'Ночи: 92-182', data: 'НОЧИ 92-182' },
                        { title: 'Ночи: 183+', data: 'НОЧИ 183 >' }
                    ];
                    excelTitle = 'Отчёт 210: Статистика по ночам';
                    break;

                case '801':
                    columns = [
                        { title: 'Гражданство', data: 'CITIZENS' },
                        { title: 'Страна', data: 'ГРАЖДАНСТВО' },
                        { title: 'Количество', data: 'КОЛ-ВО' }
                    ];
                    excelTitle = 'Отчёт 801: Иностранцы по гражданству';
                    break;

                case '200':
                    columns = [
                        { title: 'Наименование', data: 'НАИМЕНОВАНИЕ' },
                        { title: 'Тип средства', data: 'ТИП СРЕДСТВО' },
                        { title: 'НФ', data: 'НФ' },
                        { title: 'Койки', data: 'КОЙКИ' },
                        { title: 'Всего гостей', data: 'ВСЕГО ГОСТЕЙ' },
                        { title: 'Всего ночей', data: 'ВСЕГО НОЧЕЙ' },
                        { title: 'Гости Узб', data: 'ГОСТИ УЗБ' },
                        { title: 'Ночи Узб', data: 'НОЧИ УЗБ' },
                        { title: 'Гости СНГ', data: 'ГОСТИ СНГ' },
                        { title: 'Ночи СНГ', data: 'НОЧИ СНГ' },
                        { title: 'Гости Заруб', data: 'ГОСТИ ЗАРУБ' },
                        { title: 'Ночи Заруб', data: 'НОЧИ ЗАРУБ' }
                    ];
                    excelTitle = 'Отчёт 200: Статистика по гостям и ночам';
                    break;

                case '205':
                    columns = [
                        { title: 'Средство размещения', data: 'СРЕДСТВА РАЗМЕЩЕНИЯ' },
                        { title: 'Категории', data: 'КАТЕГОРИИ' },
                        { title: 'Всего', data: 'ВСЕГО' },
                        { title: 'Турист', data: 'ТУРИСТ' },
                        { title: 'Посещ. родств.', data: 'ПОСЕЩ РОДСТВ' },
                        { title: 'Учёба', data: 'УЧЁБА' },
                        { title: 'Лечеб.', data: 'ЛЕЧЕБ' },
                        { title: 'Паломнич.', data: 'ПАЛОМНИЧ.' },
                        { title: 'Шопинг', data: 'ШОПИНГ' },
                        { title: 'Транзит', data: 'ТРАНЗИТ' },
                        { title: 'Прочие', data: 'ПРОЧИЕ' },
                        { title: 'Работа', data: 'РАБОТА' },
                        { title: 'Частный', data: 'ЧАСТНЫЙ' },
                        { title: 'Спорт и культура', data: 'СПОРТ И КУЛЬТУРА' },
                        { title: 'На отдых', data: 'НА ОТДЫХ' },
                        { title: 'Гостевая', data: 'ГОСТЕВАЯ' },
                        { title: 'Служебная', data: 'СЛУЖЕБНАЯ' },
                        { title: 'Соотечественник', data: 'СООТЕЧЕСТВЕННИК' },
                        { title: 'Почётный', data: 'ПОЧЁТНЫЙ' },
                        { title: 'Инвестор', data: 'ИНВЕРСТОР' }
                    ];
                    excelTitle = 'Отчёт 205: Статистика по целям визита';
                    break;


                case '10':
                    columns = [
                        { title: 'Название региона', data: 'Название региона:' },
                        { title: 'Гостиница', data: 'Гостиница' },
                        { title: 'Всего иностранцев', data: 'Всего иностранцев' },
                        { title: 'Мужчин (иностр.)', data: 'Мужчин (иностр)' },
                        { title: 'Женщин (иностр.)', data: 'Женщин (иностр)' },
                        { title: 'Всего местных', data: 'Всего местных' },
                        { title: 'Мужчин (мест.)', data: 'Мужчин (мест)' },
                        { title: 'Женщин (мест.)', data: 'Женщин (мест)' }
                    ];
                    excelTitle = 'Отчёт 01: Отчёт по регионам и гостиницам';
                    break;
                case '20':
                    columns = [
                        { title: '№', data: '№' },
                        { title: 'Страна', data: 'СТРАНА' },
                        { title: 'A1', data: 'A1' },
                        { title: 'A2', data: 'A2' },
                        { title: 'B1', data: 'B1' },
                        { title: 'B2', data: 'B2' },
                        { title: 'C1', data: 'C1' },
                        { title: 'C2', data: 'C2' },
                        { title: 'D1', data: 'D1' },
                        { title: 'D2', data: 'D2' },
                        { title: 'E', data: 'E' },
                        { title: 'J1', data: 'J1' },
                        { title: 'J2', data: 'J2' },
                        { title: 'PV1', data: 'PV1' },
                        { title: 'PV2', data: 'PV2' },
                        { title: 'S1', data: 'S1' },
                        { title: 'S2', data: 'S2' },
                        { title: 'S3', data: 'S3' },
                        { title: 'T', data: 'T' },
                        { title: 'TG', data: 'TG' },
                        { title: 'EX', data: 'EX' },
                        { title: 'Транзит', data: 'ТРАНЗИТ' },
                        { title: 'Без визы', data: 'БЕЗ_ВИЗЫ' },
                        { title: 'Итого', data: 'ИТОГО:' }
                    ];
                    excelTitle = 'Отчёт 02: Отчёт по визам';
                    break;
                case '30':
                    columns = [
                        { title: 'Название региона', data: 'Название региона' },
                        { title: 'Район', data: 'Район' },
                        { title: 'Гостиница', data: 'Гостиница' },
                        { title: 'Тип сред.разм.', data: 'Тип сред разм' },
                        { title: 'Н/Ф', data: 'Н/Ф' },
                        { title: 'Кол-во коек', data: 'Кол-во коек' },
                        { title: 'Всего местных', data: 'Всего местных' },
                        { title: 'Местных (прожито дней)', data: 'Местных (прожито дней)' },
                        { title: 'Всего иностранцев', data: 'Всего иностранцев' },
                        { title: 'Иностранцы (прожито дней)', data: 'Иностранцы (прожито дней)' }
                    ];
                    excelTitle = 'Отчёт 03: Отчёт по проживанию';
                    break;
                case '40':
                    columns = [
                        { title: '№', data: '№' },
                        { title: 'Гражданство', data: 'ГРАЖДАНСТВО' },
                        { title: 'Пенсионер', data: 'Пенсионер' },
                        { title: 'Учащийся', data: 'Учащийся' },
                        { title: 'Иждивенец', data: 'Иждивенец' },
                        { title: 'Другие', data: 'Другие' },
                        { title: 'Итого', data: 'Итого' }
                    ];
                    excelTitle = 'Отчёт 04: Отчёт по категориям гостей';
                    break;
                case '50':
                    columns = [
                        { title: 'Регион', data: 'Регион' },
                        { title: 'Гостиница', data: 'Гостиница' },
                        { title: 'Администратор', data: 'Администратор' },
                        { title: 'Местные', data: 'Местные' },
                        { title: 'Иностранцы', data: 'Иностранцы' },
                        { title: 'Дата последней активности', data: 'Дата последней активности' },
                        { title: 'Прошло дней', data: 'Прошло дней' }
                    ];
                    excelTitle = 'Отчёт 05: Отчёт по администраторам';
                    break;
                case '60':
                    columns = [
                        { title: 'Регион', data: 'Регион' },
                        { title: 'Гостиница', data: 'Гостиница' },
                        { title: 'Дата последней активности', data: 'Дата последней активности' },
                        { title: 'Прошло дней', data: 'Прошло дней' }
                    ];
                    excelTitle = 'Отчёт 06: Отчёт по активности гостиниц';
                    break;

                case '70':
                    columns = [
                        { title: 'Гражданство', data: 'Гражданство' },
                        { title: 'Имя', data: 'Имя' },
                        { title: 'Фамилия', data: 'Фамилия' },
                        { title: 'Отчество', data: 'Отчество' },
                        { title: 'Пол', data: 'Пол' },
                        { title: 'Паспорт', data: 'Паспорт' },
                        { title: 'Дата выдачи', data: 'Дата_выдачи' },
                        { title: 'Выдан', data: 'Выдан' },
                        { title: 'Виза', data: 'Виза' },
                        { title: 'Кол-во визитов', data: 'Кол_визитов' }
                    ];
                    excelTitle = 'Отчёт 08: Отчёт по иностранцам';
                    break;
                case '80':
                    columns = [
                        { title: 'Страна', data: 'Страна' },
                        { title: 'Кол-во гостей', data: 'Кол-во гостей' },
                        { title: 'Сравнение с декабрём (кол-во)', data: 'Сравнение декабрь кол-во' },
                        { title: 'Сравнение с декабрём (%)', data: 'Сравнение декабрь %' },
                        { title: 'Прошлый год (кол-во)', data: 'Прошлый год кол-во' },
                        { title: 'Прошлый год (%)', data: 'Прошлый год %' }
                    ];
                    excelTitle = 'Отчёт 09: Сравнение с прошлым годом';
                    break;

                case '90':
                    columns = [
                        { title: 'Страна', data: 'Страна' },
                        { title: 'Кол-во гостей', data: 'Кол-во гостей' },
                        { title: 'Сравнение с декабрём (кол-во)', data: 'Сравнение декабрь кол-во' },
                        { title: 'Сравнение с декабрём (%)', data: 'Сравнение декабрь %' },
                        { title: 'Прошлый год (кол-во)', data: 'Прошлый год кол-во' },
                        { title: 'Прошлый год (%)', data: 'Прошлый год %' }
                    ];
                    excelTitle = 'Отчёт 10: Сравнение с прошлым годом';
                    break;

                case '100':
                    columns = [
                        { title: 'Страна', data: 'COUNTRY' },
                        { title: 'Январь', data: 'JAN' },
                        { title: 'Февраль', data: 'FEB' },
                        { title: 'Март', data: 'MAR' },
                        { title: 'Апрель', data: 'APR' },
                        { title: 'Май', data: 'MAY' },
                        { title: 'Июнь', data: 'JUN' },
                        { title: 'Июль', data: 'JUL' },
                        { title: 'Август', data: 'AUG' },
                        { title: 'Сентябрь', data: 'SEP' },
                        { title: 'Октябрь', data: 'OCT' },
                        { title: 'Ноябрь', data: 'NOV' },
                        { title: 'Декабрь', data: 'DEC' },
                        { title: 'Итого', data: year }
                    ];
                    excelTitle = 'Отчёт 11: Отчёт по месяцам';
                    break;
                case '111':
                    columns = [
                        { title: 'Название района', data: 'НАЗВАНИЕ РАЙОНА' },
                        { title: 'Всего', data: 'ВСЕГО' },
                        { title: 'Работа', data: 'РАБОТА' },
                        { title: 'Учёба', data: 'УЧЁБА' },
                        { title: 'Турист', data: 'ТУРИСТ' },
                        { title: 'Частный', data: 'ЧАСТНЫЙ' },
                        { title: 'Другое', data: 'ДРУГОЕ' },
                        { title: 'Кол-во гостиниц', data: 'КОЛ-ВО ГОСТИНИЦ' }
                    ];
                    excelTitle = 'Отчёт 12: Отчёт по районам';
                    break;
                case '120':
                    columns = [
                        { title: 'Название региона', data: 'Название региона:' },
                        { title: 'Гостиницы', data: 'Гостиницы' },
                        { title: 'Апартотели', data: 'Апартотели' },
                        { title: 'Резиденции', data: 'Резиденции' },
                        { title: 'SPA отели', data: 'SPA отели' },
                        { title: 'Бутик отели', data: 'Бутик отели' },
                        { title: 'Мотели', data: 'Мотели' },
                        { title: 'Санатории', data: 'Санатории' },
                        { title: 'Пансионаты', data: 'Пансионаты' },
                        { title: 'Лечебницы', data: 'Лечебницы' },
                        { title: 'Зона отдыха', data: 'Зона отдыха' },
                        { title: 'Спорт базы', data: 'Спорт базы' },
                        { title: 'Лагерь туристский', data: 'Лагерь туристский' },
                        { title: 'Лагерь палаточный', data: 'Лагерь палаточный' },
                        { title: 'Лагерь юртовый', data: 'Лагерь юртовый' },
                        { title: 'Кемпинги', data: 'Кемпинги' },
                        { title: 'Дом гостевой', data: 'Дом гостевой' },
                        { title: 'Юрта', data: 'Юрта' },
                        { title: 'Без категории', data: 'Без категории' }
                    ];
                    excelTitle = 'Отчёт 13: Отчёт по типам гостиниц';
                    break;
                case '130':
                    columns = [
                        { title: 'Название региона', data: 'Название региона:' },
                        { title: 'Гостиница', data: 'Гостиница' },
                        { title: 'Тип сред.разм.', data: 'Тип сред.разм.' },
                        { title: 'Кол-во номеров', data: 'Кол-во номеров' },
                        { title: 'Кол-во коек', data: 'Кол-во коек' },
                        { title: 'Кол-во гостей', data: 'Кол-во гостей' },
                        { title: 'Из них иностранцы', data: 'Из них иностранцы' },
                        { title: 'Средняя загруж. к номерам (%)', data: 'Средняя загруж. к номерам (%)' },
                        { title: 'Средняя загруж. к койкам (%)', data: 'Средняя загруж. к койкам (%)' }
                    ];
                    excelTitle = 'Отчёт 14: Отчёт по загрузке гостиниц';
                    break;
                case '140':
                    columns = [
                        { title: 'Дата', data: 'Дата: ' },
                        { title: 'Гостиница', data: 'Гостиница:' },
                        { title: 'Кол-во номеров', data: 'Кол-во номеров' },
                        { title: 'Кол-во коек', data: 'Кол-во коек' },
                        { title: 'Кол-во гостей', data: 'Кол-во гостей' },
                        { title: 'Из них иностранцы', data: 'Из них иностранцы' },
                        { title: 'Средняя загруж. к номерам (%)', data: 'Средняя загруж к номерам (%)' },
                        { title: 'Средняя загруж. к койкам (%)', data: 'Средняя загруж к койкам (%)' }
                    ];
                    excelTitle = 'Отчёт 15: Отчёт по загрузке гостиниц';
                    break;
                case '150':
                    columns = [
                        { title: 'Регион', data: 'РЕГИОН' },
                        { title: 'Гостиница', data: 'ГОСТИНИЦА' },
                        { title: 'Звёзды', data: 'ЗВЕЗДЫ' },
                        { title: 'Сертифицированные номера', data: 'СЕРТНФ' },
                        { title: 'Сертифицированные койки', data: 'СЕРТКОЙКИ' },
                        { title: 'Тип номера', data: 'ТИП НОМЕРА' },
                        { title: 'Этаж', data: 'ЭТАЖ' },
                        { title: '№ комнаты', data: '№КОМНАТЫ' },
                        { title: 'Кол-во коек в номере', data: 'КОЛ-ВО КОЕК В НОМЕРЕ' },
                        { title: 'Проживают', data: 'ПРОЖИВАЮТ' },
                        { title: 'Из них женщин', data: 'ИЗ НИХ ЖЕН' },
                        { title: 'Из них мужчин', data: 'ИЗ НИХ МУЖ' }
                    ];
                    excelTitle = 'Отчёт 16: Отчёт по номерам';
                    break;
                case '5010':
                    columns = [
                        { title: 'Гражданство', data: 'DAVLATLAR' },
                        { title: 'D-1', data: 'D-1' },
                        { title: 'D-2', data: 'D-2' },
                        { title: 'DT', data: 'DT' },
                        { title: 'S-1', data: 'S-1' },
                        { title: 'S-2', data: 'S-2' },
                        { title: 'S-3', data: 'S-3' },
                        { title: 'O', data: 'O' },
                        { title: 'B-1', data: 'B-1' },
                        { title: 'B-2', data: 'B-2' },
                        { title: 'T', data: 'T' },
                        { title: 'TG', data: 'TG' },
                        { title: 'E', data: 'E' },
                        { title: 'J-1', data: 'J-1' },
                        { title: 'J-2', data: 'J-2' },
                        { title: 'PV-1', data: 'PV-1' },
                        { title: 'PV-2', data: 'PV-2' },
                        { title: 'A-1', data: 'A-1' },
                        { title: 'A-2', data: 'A-2' },
                        { title: 'A-2', data: 'C-1' },
                        { title: 'A-2', data: 'C-2' },
                        { title: 'A-2', data: 'TRAN' },
                        { title: 'A-2', data: 'EXIT' },
                        { title: 'A-2', data: 'MED' },
                        { title: 'A-2', data: 'INV' },
                        { title: 'A-2', data: 'VTD' },
                        { title: 'A-2', data: 'A-3' },
                        { title: 'A-2', data: 'PLG' },
                        { title: 'A-2', data: 'EV' },
                        { title: 'A-2', data: 'STD' },
                        { title: 'A-2', data: 'PZ' },
                        { title: 'Итого', data: 'Jami:' }

                    ];
                    excelTitle = 'Отчёт 5010: Сводный отчет выдачи виз по региональным ГУМиОГ';
                    break;



                case '5015':
                    columns = [
                        { title: 'Гражданство', data: 'DAVLATLAR' },
                        { title: 'Андижан', data: 'Andijon' },
                        { title: 'Фергана', data: 'Fargona' },
                        { title: 'Наманган', data: 'Namangan' },
                        { title: 'Ташкентская область', data: 'Toshkent viloyati' },
                        { title: 'Бекабад', data: 'Bekabad' },
                        { title: 'Ташкент город', data: 'Toshkent shahri' },
                        { title: 'ОВВиОГ-аэропорт', data: 'OVViOG-aeroport' },
                        { title: 'IIV MvaFRBB', data: 'IIV MvaFRBB' },
                        { title: 'Гулистан', data: 'Gulistan' },
                        { title: 'Джизак', data: 'Jizzax' },
                        { title: 'Самарканд', data: 'Samarqand' },
                        { title: 'Карши', data: 'Qarshi' },
                        { title: 'Термез', data: 'Termez' },
                        { title: 'Навои', data: 'Navoi' },
                        { title: 'Бухара', data: 'Buxoro' },
                        { title: 'Ургенч', data: 'Urgench' },
                        { title: 'Нукус', data: 'Nukus' },
                        { title: 'Итого', data: 'Jami:' }
                    ];
                    excelTitle = 'Отчёт 5015: Отчёт по визам';
                    break;

                case '5000':
                    columns = [
                        { title: 'Гражданство', data: 'ГРАЖДАНСТВО' },
                        { title: 'ГУМиОГ Времення прописка', data: 'ГУМиОГ Времення прописка' },
                        { title: 'Гостиницы', data: 'Гостиницы' },
                        { title: 'Самостоятельный туристы', data: 'Самостоятельный туристы' },
                        { title: 'Физ лица', data: 'Физ лица' }
                    ];
                    excelTitle = 'Отчёт 5000: Отчёт по гражданству';
                    break;

                case '6103':
                    const only_year = document.getElementById('year_num').value;

                    columns = [
                        { title: 'Регион', data: 'РЕГИОН' },
                        { title: 'Гостиница', data: 'ГОСТИНИЦА' },
                        { title: 'Статус', data: 'СТАТУС' },
                        { title: 'Дог №', data: 'Дог №' },
                        { title: 'ИНН', data: 'ИНН' },
                        { title: 'Янв', data: 'ЯНВ' },
                        { title: 'Фев', data: 'ФЕВ' },
                        { title: 'Мар', data: 'МАР' },
                        { title: 'Апр', data: 'АПР' },
                        { title: 'Май', data: 'МАЙ' },
                        { title: 'Июн', data: 'ИЮН' },
                        { title: 'Июл', data: 'ИЮЛ' },
                        { title: 'Авг', data: 'АВГ' },
                        { title: 'Сен', data: 'СЕН' },
                        { title: 'Окт', data: 'ОКТ' },
                        { title: 'Ноя', data: 'НОЯ' },
                        { title: 'Дек', data: 'ДЕК' },
                        { title: 'ВСЕГО ЗА ' + only_year, data: 'ВСЕГО ЗА ' + only_year }
                    ];
                    excelTitle = 'Отчёт 6103: Количество регистраций по месяцам';
                    break;

                case '900':
                    columns = [
                        { title: 'Название региона', data: 'region_name' },
                        { title: 'Тип средств размещения', data: 'hotel_type' },
                        { title: 'Название средств размещения', data: 'hotel_name' },
                        { title: 'Номерной фонд', data: 'room_fund' },
                        { title: 'Кол-во коек', data: 'beds_count' },
                        { title: 'Период', data: 'period' },
                        { title: 'Сумма', data: 'total_amount' }
                    ];
                    excelTitle = 'Отчёт 900: Суммарный отчёт по проживанию гостей';
                    break;

                case '1000':
                    columns = [
                        { title: 'Регион', data: 'region' },
                        { title: 'Район', data: 'district' },
                        { title: 'Локальные регистрации', data: 'loreg' },
                        { title: 'Иностранные регистрации', data: 'foreg' },
                        { title: 'GMDC', data: 'gmdc' },
                        { title: 'Физические лица', data: 'phys' }
                    ];
                    excelTitle = 'Отчёт 1000: Специальный отчёт для ГУМиОГ';
                    break;

                case '2000':
                    columns = [
                        { title: 'Регион', data: 'region' },
                        { title: 'Название средства размещения', data: 'hotel_name' },
                        { title: 'ИНН', data: 'inn' },
                        { title: 'Год', data: 'year' },
                        { title: 'Начислено', data: 'accrued' },
                        { title: 'Оплачено', data: 'paid' },
                        { title: 'Баланс', data: 'balance' }
                    ];
                    excelTitle = 'Отчёт 2000: Отчёт по депозиту средств размещения (KABUS)';
                    break;

                default:
                    alert('Неизвестный тип отчёта');
                    return;
            }

            $('#reportTable').DataTable({
                data: data,
                columns: columns,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: excelTitle,
                        exportOptions: { modifier: { page: 'all' } },
                        init: function(api, node) {
                            $(node).removeClass('dt-button').addClass('btn btn-outline-success rounded')
                                .html('<i class="fas fa-file-excel"></i>');
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: excelTitle,
                        exportOptions: { modifier: { page: 'all' } },
                        init: function(api, node) {
                            $(node).removeClass('dt-button').addClass('btn btn-outline-danger rounded ms-2')
                                .html('<i class="fas fa-file-pdf"></i>');
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
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/ru.json'
                },
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });
        }
    </script>
   <script>
       document.addEventListener("DOMContentLoaded", function () {
           const reportType = document.getElementById('reportType');
           const yearNumField = document.getElementById('yearNumField');
           const periodField = document.getElementById('periodField');
           const regionField = document.getElementById('region').closest('.mb-3');
           const monthNumField = document.getElementById('monthNumField');
           const hotelField = document.getElementById('hotels').closest('.mb-3');

           reportType.addEventListener('change', function () {
               const selectedValue = reportType.value;

               // Скрываем все поля по умолчанию
               yearNumField.style.display = 'none';
               monthNumField.style.display = 'none';
               periodField.style.display = 'none';
               regionField.style.display = 'none';
               hotelField.style.display = 'none';

               switch (selectedValue) {
                   case '900':
                       regionField.style.display = 'flex';
                       hotelField.style.display = 'flex';
                       yearNumField.style.display = 'flex';
                       monthNumField.style.display = 'flex';
                       break;

                   case '6103':
                       regionField.style.display = 'flex';
                       hotelField.style.display = 'flex';
                       yearNumField.style.display = 'flex';
                       break;

                   case '1000':
                       regionField.style.display = 'flex';
                       periodField.style.display = 'flex';
                       break;

                   case '2000':
                       regionField.style.display = 'flex';
                       hotelField.style.display = 'flex';
                       break;
                   case '100':
                       yearNumField.style.display = 'flex';
                       break;
                   case '200':
                   case '205':
                   case '210':
                       yearNumField.style.display = 'flex';
                       regionField.style.display = 'flex';
                       break;

                   case '5000':
                       periodField.style.display = 'flex';
                       break;

                   case '800':
                       break;

                   case '801':
                   case '150':
                   case '120':
                       regionField.style.display = 'flex';
                       break;

                   case '60':
                       regionField.style.display = 'flex';
                       hotelField.style.display = 'flex';
                       periodField.style.display = 'none';
                       break;

                   case '90':
                   case '80':
                       monthNumField.style.display = 'flex';
                       yearNumField.style.display = 'flex';
                       break;

                   default:
                       regionField.style.display = 'flex';
                       hotelField.style.display = 'flex';
                       periodField.style.display = 'flex';
                       break;
               }
           });

           // Обработка клика по кнопке "Справка"
           document.querySelector(".reference").addEventListener("click", function (event) {
               event.preventDefault();
               const helpModal = document.getElementById("helpModal");
               const modal = bootstrap.Modal.getOrCreateInstance(helpModal);
               modal.show();
           });
       });





       const settingsModal = new bootstrap.Modal(document.getElementById('settingsModal'));

        document.getElementById('openSettingsModal').addEventListener('click', function () {
            settingsModal.show();
        });


        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function (el) {
            el.addEventListener('click', function () {
                settingsModal.hide();
            });
        });

        $(document).ready(function () {
            $(":input").inputmask();
            $('#hotels').select2({
                dropdownParent: $('#settingsModal'),
                width: '100%'
            });

        });

   </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();

            function formatDate(date) {
                return date.toLocaleDateString('ru-RU', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            document.getElementById('start_date').value = formatDate(startOfMonth);
            document.getElementById('end_date').value = formatDate(endOfMonth);


            document.getElementById("reportType").addEventListener("change", function() {
                var selectedOption = this.options[this.selectedIndex];

                // Изменяем текст в span
                document.getElementById("report_name").textContent = selectedOption.text;
            });

        });
    </script>
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
@endsection

@section('content')
    <div class="sbox animate__animated animate__fadeInRight" style="margin:5px;padding:0px;">
    <br>
    <div class="sbox-content" style="background: #efefef;color:#000;font-weight: 700; margin:5px;padding: 20px; border-radius: 25px">
        <div class="col-md-12 pull-left rounded" id="report_setting_block">
            <span class="text-black  me-3" id="report_name">Настройка отчёта </span>
            <button id="openSettingsModal" class="btn btn-outline-warning rounded">
                <i class="fas fa-cog"></i> Настройка
            </button>
            <a href="#" title="Справка" class="reference btn btn-outline-info pull-right" data-bs-toggle="modal" data-bs-target="#helpModal">
                <i class="fa fa-question" style="font-size:14px;"></i> Справка
            </a>
        </div>
    </div>


    <div id="report-content">
    </div>

        <div id="showLoading2" class="ajaxLoading" style="display:none;">
            <div class="spinner-border" role="status">
                <span class="sr-only">Загрузка...</span>
            </div>
        </div>

    </div>

    <div id="error-container"></div>


    <div id="report-content"></div>
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">Справка по выводу отчётов</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Для вывода отчётов:</strong></p>
                    <p>Нажмите на кнопку <span class="badge p-1 bg-warning text-dark">НАСТРОЙКА</span></p>
                    <p>Появится модальное окно;</p>
                    <p>Укажите тип отчёта;</p>
                    <p>Укажите интересующий Вас регион/гостиницу;</p>
                    <p>Если гостиница или регион упущен, то, отчёт построится по всем гостиницам/регионам;</p>
                    <p>Нажмите на кнопку <span class="badge p-1 bg-primary">ПОКАЗАТЬ</span></p>
                    <p>Далее, модальное окно закроется и отобразится Ваш отчёт, по желанию Вы можете экспортировать отчёт в MS-EXCELовский файл нажав на кнопку <span class="badge p-1 bg-info">ЭКСПОРТ</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal"><i class="fa fa-smile"></i> ПОНЯТНО</button>
                </div>
            </div>
        </div>
    </div>




    <div id="settingsModal" class="modal fade" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalLabel">Настройка отчёта</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-3 col-form-label">Отчёты</label>
                        <div class="col-sm-9">
                            <select id="reportType" class="form-select">
                                <option value="" selected>Выберите отчёт ...</option>
                                <option value="6103"> 01. Отчет: кол-во регистраций по месяцам</option>
                                <option value="2000"> 02. Отчет по депозиту средств размещения (KABUS)</option>
                                <option value="1000"> 03. Специальный отчет для ГУМиОГ</option>
                                <option value="5000"> 04. Сводный отчет по иностранцам для ГУМиОГ </option>
                                <option value="5010"> 05. Сводный отчет по продлению виз для ГУМиОГ </option>
                                <option value="5015"> 06. Сводный отчет выдачи виз по региональным ГУМиОГ </option>
                                <option value="900"> 07. Суммарный отчет по проживанию гостей</option>
                                <option value="800"> 08. Иностранцы проживающие на данный момент в разрезе регионов</option>
                                <option value="801"> 09. Иностранцы проживающие на данный момент в разрезе стран</option>
                                <option value="200"> 10. Сведения о размещенных лицах</option>
                                <option value="205"> 11. Распределение численности размещенных лиц по целям поездо</option>
                                <option value="210"> 12. Распределение численности размещенных лиц по продолжительности пребывания</option>
                                <option value="150"> 13. Отчет по номерам (комнатам)</option>
                                <option value="10"> 14. Общий отчёт</option>
                                <option value="130"> 15. Отчет по загруженности гостиниц</option>
                                <option value="777"> 16. Сводный отчет по турсбору за весь период</option>
                                <option value="140">17. Детальный отчет по загруженности гостиницы</option>
                                <option value="20"> 18. Отчет по странам прибытия и визам</option>
                                <option value="40"> 19. Отчет по гражданству</option>
                                <option value="30"> 20. Отчет по проживанию</option>
                                <option value="50"> 21. Отчет по активности сотрудников</option>
                                <option value="60"> 22. Отчет по активности гостиниц</option>
                                <option value="70"> 23. Отчет по уникальным посещениям иностранцев</option>
                                <option value="110"> 24. Отчет по типу визитов (по районам)</option>
                                <option value="111"> 25. Отчет по типу визитов иностранцев (по районам)</option>
                                <option value="120"> 26. Сводка по подключенным средствам размещения</option>
                                <option value="80"> 27. Сравнительный анализ по гражданству иностранцев <br/>(для послов, по выбранному месяцу)</option>
                                <option value="90"> 28. Сравнительный анализ по странам прибытия иностранцев <br>(для послов, по выбранному месяцу)</option>
                                <option value="100"> 29. Отчет по прибытию иностранцев по месяцам <br>(для послов, по выбранному году)</option>
                                <option value="950"> 30. Сводный отчёт по турсбору</option>
                                <option value="1748"> 31. Отчет по гражданствам в разрезе средств размещения</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-3 col-form-label">Регион</label>
                        <div class="col-sm-9">
                            <select id="region" class="form-select">
                                <option selected value="">---НЕ ВЫБРАНО---</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-3 col-form-label">Гостиница</label>
                        <div class="col-sm-9">
                            <select id="hotels" class="form-select">
                                <option selected value="">---НЕ ВЫБРАНО---</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="mb-3 row align-items-center" id="monthNumField" style="display: none;">
                        <label class="col-sm-3 col-form-label">Месяц</label>
                        <div class="col-sm-9">
                            <select id="month_num" class="form-select">
                                <option value="1">Январь</option>
                                <option value="2">Февраль</option>
                                <option value="3">Март</option>
                                <option value="4">Апрель</option>
                                <option value="5">Май</option>
                                <option value="6">Июнь</option>
                                <option value="7">Июль</option>
                                <option value="8">Август</option>
                                <option value="9">Сентябрь</option>
                                <option value="10">Октябрь</option>
                                <option value="11">Ноябрь</option>
                                <option value="12">Декабрь</option>
                            </select>
                        </div>
                    </div>


                    <div class="mb-3 row align-items-center" id="yearNumField" style="display: none;">
                        <label class="col-sm-3 col-form-label">Год</label>
                        <div class="col-sm-9">
                            <?php $cur_year = date('Y'); ?>
                            <select name='year_num' id="year_num" class='select2 form-control font-bold' style="width: 130px;">
                                <?php
                                for ($y = 0; $y < 6; $y++) {
                                    $yy = ($cur_year * 1) - $y;
                                    if ($yy < 2017) break;
                                    $selected = '';
                                    if (isset($storedRP['year_num']) && (!empty($storedRP['year_num']))) {
                                        $selected = ($yy == $storedRP['year_num'] * 1) ? 'selected' : '';
                                    } else {
                                        $selected = ($y == 0) ? 'selected' : '';
                                    }
                                    echo "<option value='$yy' $selected>$yy</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row" id="periodField">
                        <label class="col-sm-3 col-form-label">Период</label>
                        <div class="col-sm-4 d-flex align-items-center">
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

                <div class="modal-footer">
                    <button type="button" id="reportAll" class="btn btn-outline-primary me-2 rounded">
                        <i class="fas fa-chart-line"></i> Показать
                    </button>
                </div>
            </div>
        </div>
    </div>


@endsection


