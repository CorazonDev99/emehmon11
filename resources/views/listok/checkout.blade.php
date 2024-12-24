@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css">

    <style>
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

        .modal {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: block;
            opacity: 1;
        }

        #listok-table tbody tr {
            cursor: pointer;
        }
    </style>
@endsection

@section('script')

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>

    <script>
        $(document).ready(function () {
            var table = $('#listok-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/listok/data-checkout',
                columns: [
                    {data: null, orderable: false, className: 'select', searchable: false, width: '10px', defaultContent: ''},
                    {data:'id',name:'tb_listok_checkout.id',searchable:false,visible:false},
                    {data:'regNum',name:'tb_listok_checkout.regnum',sClass:'dt-center',width:'45px'},
                    {data:'guest',name:'guest'},
                    {data:'ctz',name:'tb_listok_checkout.id_citizen',searchable:false,sClass:'dt-left'},
                    {data:'room',name:'tb_listok_checkout.propiska',width:'40px',sClass:'dt-center'},
                    {data:'dt',name:'tb_listok_checkout.datevisiton',searchable:false},
                    {data:'wdays',name:'tb_listok_checkout.wdays',width:'40px',sClass:'dt-center'},
                    {data: 'htl', name: 'tb_hotels.name',width:'40px',sClass:'dt-center'},
                    {data:'amount',name:'tb_listok_checkout.amount',searchable:false,'sClass':'dt-right'},
                    {data:'tag',name:'tag',searchable:false},{data:'adm',name:'adm',visible:false,orderable:false,searchable:false},
                    {data:'datebirth',name:'tb_listok_checkout.datebirth',visible:false,orderable: false,searchable:false},
                    {data:'tb_visa',name:'tb_visa.name',visible:false,orderable: false,searchable:false},
                    {data:'tb_visanm',name:'tb_listok_checkout.visanumber',visible:false,orderable: false,searchable:false},
                    {data:'tb_visafrom',name:'tb_listok_checkout.datevisaon',visible:false,orderable: false,searchable:false},
                    {data:'tb_visato',name:'tb_listok_checkout.datevisaoff',visible:false,orderable: false,searchable:false},
                    {data:'kppnumber',name:'tb_listok_checkout.kppnumber',visible:false,orderable: false,searchable:false},
                    {data:'datekpp',name:'tb_listok_checkout.datekpp',visible:false,orderable: false,searchable:false}
                ],
                select: {
                    style: 'multi',
                    info: true
                },
                responsive: true,
                pageLength: 25,
                autoWidth: false,
                scrollX: true,
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

            $('#listok-table tbody').on('dblclick', 'tr', function () {
                var data = table.row(this).data();

                $('#details').html(`
                    <p><strong>Регистрационный номер:</strong> ${data.regNum}</p>
                    <p><strong>Гость:</strong> ${data.guest}</p>
                    <p><strong>Гражданство:</strong> ${data.ctz}</p>
                    <p><strong>Дата визита:</strong> ${data.dt}</p>
                    <p><strong>Дней пребывания:</strong> ${data.wdays}</p>
                    <p><strong>Отель:</strong> ${data.htl}</p>
                    <p><strong>Сумма:</strong> ${data.amount}</p>
                `);

                var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                myModal.show();
            });

            $('#deleteButton').on('click', function () {
                let selectedRows = $('#listok-table').DataTable().rows('.selected').data();

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
                        selectedRows.each(function (row) {
                            ids.push(row.id);
                        });

                        $.ajax({
                            url: '/listok/destroy',
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
                                table.ajax.reload();
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
        });
    </script>
@endsection
@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <h4 class="m-3">Листок убытия</h4>
                <div class="col-md-12">
                    <div class="card" id="user">
                        <div class="card-nav align-items-center">
                            <form id="quick-search-form" class="d-flex align-items-center">
                                <label for="regNum" class="me-2 bold-label" style="white-space: nowrap;">Рег. №</label>
                                <input type="text" id="regNum" name="regNum" class="form-control me-4" placeholder="">

                                <label for="room" class="me-2 bold-label">Комната</label>
                                <input type="text" id="room" name="room" class="form-control me-4" placeholder="">

                                <label for="tag" class="me-2 bold-label">Тег</label>
                                <input type="text" id="tag" name="tag" class="form-control me-4" placeholder="">

                                <label for="payment" class="me-2 bold-label">Оплата</label>
                                <select id="payment" name="payment" class="form-control me-4">
                                    <option value="">--- НЕ ВЫБРАНО ---</option>
                                </select>
                                <button type="button" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="card-body">
                            <table class="table" id="listok-table" style="width: 100%;">
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
                        <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailsModalLabel">Детали гостя</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="details"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="room-show"></div>
@endsection
