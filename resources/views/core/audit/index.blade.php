@extends('layouts.app')
@section('header_title', 'Audits')
@section('style')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.0/dist/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.css" />
<style>
    #auditTable th, #auditTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<style>
    .global-search {
        width: 85%;
        margin-left: 100px !important;
        margin-top: 15px !important
    }

    #custom-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        display: none;
    }

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>



    <script>
        $(document).ready(function () {
            var audit = $('#auditTable')

            var table = audit.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/audit/data',
                    data: function(d) {
                        let globalFilters = $('#global-search-form').serializeArray();
                        globalFilters.forEach(function(filter) {
                            d[filter.name] = filter.value;
                        });
                    }
                },
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
                    { data: 'event_time', sClass: 'dt-center', title: 'Event Time', width: '150px' },
                    { data: 'entity_id', sClass: 'dt-center', title: 'Entity Id', width: '100px' },
                    { data: 'hotel_id', sClass: 'dt-center', title: 'Hotel Id', width: '100px' },
                    { data: 'hotel_name', sClass: 'dt-center', title: 'Hotel Name', width: '200px' },
                    { data: 'user_id', sClass: 'dt-center', title: 'User Id', width: '100px' },
                    { data: 'user_name', sClass: 'dt-center', title: 'Username', width: '150px' },
                    { data: 'event_type', sClass: 'dt-center', title: 'Event Type', width: '120px' },
                    { data: 'entity_type', sClass: 'dt-center', title: 'Entity Type', width: '150px' },
                    { data: 'ip_address', sClass: 'dt-center', title: 'IP Address', width: '130px' },
                    { data: 'changes', sClass: 'dt-start', title: 'Changes', width: '250px' }
                ],


                responsive: false,
                pageLength: 25,
                autoWidth: false,
                scrollX: true,
                scrollY: '500px',
                scrollCollapse: true,
                order: [[0, 'asc']],
                dom: 'rt<"bottom"ilp><"clear">',
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
                },
                initComplete: function () {
                    $('.dataTables_length').appendTo('.dataTables_wrapper');
                    $('.dataTables_info').appendTo('.dataTables_wrapper');
                }
            });

            table.on('preXhr.dt', function() {
                $('#audit-table-container').addClass('loading');
                $('#custom-loading').fadeIn();
            });

            table.on('xhr.dt', function() {
                $('#audit-table-container').removeClass('loading');
                $('#custom-loading').fadeOut();
            });

            $('#clear-filter-btn').on('click', function () {
                $('#global-search-form input').val('');
                $('#global-search-form select').val('').trigger('change');

                table.ajax.params = {};
                table.search('').columns().search('');
                table.ajax.reload();

                localStorage.removeItem('globalFilters');
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

            $('#global-filter').on('click', function() {
                let savedFilters = localStorage.getItem('globalFilters');
                let filterValues = savedFilters ? JSON.parse(savedFilters) : {};

                $.confirm({
                    title: 'Global Search',
                    boxWidth: '900px',
                    useBootstrap: false,
                    type: "blue",
                    typeAnimated: true,
                    closeIcon: true,
                    content: "<div class='global-search'>" +
                        "<form id='global-search-form'>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Username:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' id='username' class='form-control' placeholder='Username' name='user_name' value='" + (filterValues.user_name || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Hotel Name:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<select class='form-control' name='hotel_name' id='hotel'>" +
                        "<option value=''>-- NOT SELECTED --</option>" +
                        "@foreach ($hotels as $hotel)" +
                        "<option value='{{ $hotel['hotel_id'] }}' " + (filterValues.hotel == '{{ $hotel['hotel_id'] }}' ? 'selected' : '') + ">{{ $hotel['hotel_name'] }}</option>" +
                        "@endforeach" +
                        "</select>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>IP Address:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' id='ip_address' class='form-control' placeholder='IP Address' name='ip_address' value='" + (filterValues.ip_address || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Event Time:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<div class='d-flex'>" +
                        "<span style='margin-top: 6px; margin-bottom: 6px; margin-right: 12px; font-size: 10px; color: #333; background-color: #9af3ec ; padding: 8px 12px; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);'>FROM</span> " + "<input placeholder='dd.mm.yyyy' class='form-control input-mask-date me-2' data-inputmask=\"'alias': 'datetime'\" data-inputmask-inputformat='dd.mm.yyyy' inputmode='numeric' name='event_time_from' value='" + (filterValues.event_time_from || '') + "'>" +
                        "<span style='margin-top: 6px; margin-bottom: 6px; margin-right: 12px; font-size: 10px; color: #333; background-color: #9af3ec ; padding: 8px 12px; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);'>TO</span>" + "<input placeholder='dd.mm.yyyy' class='form-control input-mask-date' data-inputmask=\"'alias': 'datetime'\" data-inputmask-inputformat='dd.mm.yyyy' inputmode='numeric' name='event_time_to' value='" + (filterValues.event_time_to || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "</div>" +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Entity Type:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        "<input type='text' class='form-control' placeholder='Entity Type' id='entity_type' name='entity_type' value='" + (filterValues.entity_type || '') + "'>" +
                        "</div>" +
                        "</div>" +
                        "</form>" +
                        "</div>",
                    buttons: {
                        SEARCH: {
                            btnClass: 'btn-green',
                            action: function() {
                                var filters = $('#global-search-form').serializeArray();
                                var filterObject = {};
                                filters.forEach(function(field) {
                                    filterObject[field.name] = field.value;
                                });

                                localStorage.setItem('globalFilters', JSON.stringify(filterObject));

                                $('#auditTable').DataTable().ajax.reload(null, false);
                            },
                        },
                        Очистить: {
                            text: '<i class="fas fa-filter"></i> <i class="fas fa-times"></i>',
                            btnClass: 'btn-danger me-3',
                            action: function() {
                                $('#global-search-form input').val('');
                                $('#global-search-form select').val('').trigger('change');

                                var table = $('#auditTable').DataTable();
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

                        $('#hotel').selectize({
                            create: true,
                            sortField: 'text',
                            closeAfterSelect: true,
                            highlight: true,
                        });

                    }
                });
            });
        });
    </script>

@endsection

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 mb-3">

                    <button type="button" class="btn btn-outline-info rounded me-2" id="global-filter">
                        <i class="fa fa-filter"></i>
                    </button>

                    <button type="button" class="btn btn-outline-danger rounded me-2" id="clear-filter-btn">
                        <i class="fas fa-filter"></i> <i class="fas fa-times"></i>
                    </button>

                </div>

                <div id="audit-table-container" class="loading-overlay">
                    <div id="custom-loading">
                        <div class="spinner"></div>
                    </div>
                    <table id="auditTable" class="table dataTable row-border table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
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
                </div>


            </div>
        </div>
    </div>
@endsection
