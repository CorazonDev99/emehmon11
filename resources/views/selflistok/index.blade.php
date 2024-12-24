@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>+
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>


    <script>
        $(document).ready(function() {
            var table = $('#selflistok-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/selflistok/data',
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function() {
                            return '<input type="checkbox" class="row-select">';
                        }
                    },
                    {
                        data: 'id',
                        name: 'tb_self_listok.id',
                        searchable: false,
                        visible: false
                    },
                    {
                        data: 'reg_num',
                        name: 'tb_self_listok.regNum',
                        sClass: 'dt-center',
                        width: '45px',
                        render: function(data, type, row) {
                            return `<a onclick="showData(${row.id})" class="btn btn-sm btn-outline-primary p-0 text-bold">(${data})
                                </a>`
                        }
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        render: function(data, type, row) {
                            return `<span style="white-space: pre-line; word-wrap: break-word;">${data}</span>`;
                        }
                    },
                    {
                        data: 'ctz',
                        name: 'tb_self_listok.id_citizen',
                        render: function(data) {
                            return data;
                        }
                    },
                    {
                        data: 'pass_sn',
                        name: 'pass_sn',
                        sClass: 'dt-center'

                    },
                    {
                        data: 'summa',
                        name: 'tb_self_listok.summa',
                        width: '40px',
                        sClass: 'dt-center'
                    },
                    {
                        data: 'visit_date',
                        name: 'tb_self_listok.dateVisitOn',
                    },
                    {
                        data: 'stay_days',
                        name: 'tb_self_listok.wdays',
                        width: '40px',
                        sClass: 'dt-center'
                    },
                    {
                        data: 'hotel_name',
                        name: 'tb_hotels.name',
                        width: '40px',
                        sClass: 'dt-center'
                    },
                    {
                        data: 'hotel_address',
                        name: 'tb_hotels.address',
                        sClass: 'dt-center'

                    },

                ],
                select: {
                    style: 'multi',
                    info: true
                },
                scrollX: true,

                dom: '<"top"f>rt<"bottom"ilp><"clear">',
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
                    $('#selflistok-table_filter').appendTo('#search');

                }
            });
            table.on('select', function(e, dt, type, indexes) {
                indexes.forEach(function(index) {
                    var row = table.row(index).node();
                    $(row).find('input.row-select').prop('checked', true);
                });
            });
            table.on('deselect', function(e, dt, type, indexes) {
                indexes.forEach(function(index) {
                    var row = table.row(index).node();
                    $(row).find('input.row-select').prop('checked', false);
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire('Сохранено!', '{{ session('success') }}', 'success');
            @endif
        });
    </script>

    <script>
        function showData(id) {
            $.confirm({
                title: '',
                content: `url:/selflistok/show/${id}`,
                columnClass: 'col-md-11',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    close: {
                        text: 'Yopish',
                        btnClass: 'btn-red',
                        action: function() {}
                    },
                },

            });

        }
    </script>

    {{-- Add guest --}}
    <script>
        let mainFormModal;

        function addButton() {
            mainFormModal = $.confirm({
                title: '',
                content: `url:/selflistok/form`,
                columnClass: 'col-md-11',
                type: 'blue',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    close: {
                        isHidden: true,
                        text: 'Yopish',
                        btnClass: 'btn-red',
                        action: function() {}
                    },
                },
                onOpen: function() {
                    $('body').addClass('no-scroll');
                },
                onClose: function() {
                    $('body').removeClass('no-scroll');
                }

            });

        }
    </script>


    <script>
        $(document).ready(function() {


            $('#deleteButton').on('click', function() {
                let selectedRows = $('#selflistok-table').DataTable().rows('.selected').data();

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

                        $.ajax({
                            url: '/selflistok/destroy',
                            type: 'POST',
                            data: {
                                ids: ids,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Элементы успешно удалены.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('#selflistok-table').DataTable().ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                console.error("Ошибка при удалении элементов:", xhr
                                    .responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Произошла ошибка при удалении элементов.',
                                    text: xhr.responseText ||
                                        'Пожалуйста, попробуйте снова позже.',
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#editButton').on('click', function() {
                let selectedRows = $('#selflistok-table').DataTable().rows('.selected').data();
                if (selectedRows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Пожалуйста, выберите один элемент для изменения.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                if (selectedRows.length > 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Пожалуйста, выберите только один элемент для изменения.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                $.ajax({
                    url: '/selflistok/datarow',
                    type: 'POST',
                    data: {
                        rowData: selectedRows[0],
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '/selflistok/edit';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Ошибка при изменении элементов:", xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Произошла ошибка при изменении элементов.',
                            text: xhr.responseText ||
                                'Пожалуйста, попробуйте снова позже.',
                        });
                    }
                });
            });
        });
    </script>
@endsection

@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <h4 class="m-3">Самостоятельные туристы</h4>
                <div class="col-md-12">
                    <div class="card" id="user">
                        <div class="card-nav d-flex justify-content-between align-items-center" id="search">
                            <div class="btn-group me-5" role="group" aria-label="Basic example">
                                <div class="btn-group me-3" role="group" aria-label="Basic example">

                                    <button id="addButton" onclick="addButton()" type="button"
                                        class="btn btn-info rounded me-2">
                                        <i class="fas fa-plus"></i>
                                    </button>

                                    <button id="editButton" type="button" class="btn btn-outline-primary rounded me-2">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <button id="deleteButton" type="button" class="btn btn-outline-danger rounded me-2">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <button id="searchButton" type="button" class="btn btn-outline-primary rounded me-2">
                                        <i class="fas fa-search"></i>
                                    </button>

                                    <button id="excelButton" type="button" class="btn btn-outline-success rounded me-2">
                                        <i class="fas fa-file-excel"></i>
                                    </button>

                                    <button id="printButton" type="button" class="btn btn-outline-warning rounded me-2">
                                        <i class="fas fa-print"></i>
                                    </button>

                                </div>

                            </div>

                        </div>
                        <div class="card-body">
                            <table class="table mb-1" id="selflistok-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th></th>
                                        <th style="white-space: nowrap;">РЕГ.№</th>
                                        <th>Ф.И.О ГОСТЯ</th>
                                        <th class="non_searchable">ГРАЖД.</th>
                                        <th>ПАССПОРТ</th>
                                        <th>СУММА</th>
                                        <th>ПРИБЫЛ</th>
                                        <th>ПРИБЫЛ_НА</th>
                                        <th>ГОСТИНИЦА</th>
                                        <th>АДРЕС</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



<style>
    .card-nav {
        margin-top: 20px !important;
        margin-left: 20px !important;
        margin-right: 20px !important;
    }

    #selflistok-table_filter input {
        border: 1px solid #aaa;
        border-radius: 3px;
        padding: 5px;
        background-color: transparent;
        margin-left: 3px;
    }

    tbody>tr {
        cursor: pointer;
    }

    tbody>tr:hover {
        background-color: rgb(128, 247, 154) !important;
    }

    .no-scroll {
        overflow: hidden !important;
        position: fixed;
        width: 100%;
    }

    .jconfirm-closeIcon {
        scale: 1.1;
        top: 20px !important;
        right: 25px !important;
        font-weight: 900 !important;
        color: #c90000 !important;
    }
</style>
