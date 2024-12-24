@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>


    <script>
        $(document).ready(function() {

            // load rooms/data url to datatable
            let table = $('#roomprices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/settings/roomprices/data',
                    data: function(d) {
                        d.region = $('#region-filter').val();
                        d.hotel = $('#hotel-filter').val();
                        d.tip = $('#tip-filter').val();
                    }
                },

                columns: [

                    {
                        data: 'id',
                        name: 'id',
                        searchable: false,
                        visible: false
                    },
                    {
                        data: 'hotel_name',
                        name: 'hotel_name',
                        sClass: 'dt-center',
                        width: '200px'
                    },

                    {
                        data: 'dt',
                        name: 'dt',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'room_type',
                        name: 'room_type',
                        sClass: 'dt-center'
                    },
                    {
                        data: 'beds',
                        name: 'beds',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'capacity',
                        name: 'capacity',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'uzs',
                        name: 'uzs',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'usd',
                        name: 'usd',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'breakfast',
                        name: 'breakfast',
                        sClass: 'dt-center',
                        render: function(data, type, row) {
                            if (data === 1 || data === '1') {
                                return "YES"
                            } else if (data === 0 || data === "0") {
                                return "NO"
                            } else {
                                return "Undefined"
                            }
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        sClass: 'dt-center',
                        render: function(data, type, row) {
                            return `
                            <a class="btn btn-sm btn-primary" onclick="showRoom(${row.id})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                </svg>
                                </a>
                            <a onclick="editRoom(${row.id})" class="btn btn-sm btn-primary">

                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                </svg>
                            </a>
                            `;
                        }
                    }

                ],
                order: [
                    [0, 'asc']
                ]
            });

            // Initialize Select2 for searchable dropdowns
            $('#region-filter').select2({
                placeholder: 'Select region',
                allowClear: true
            });

            $('#hotel-filter').select2({
                placeholder: 'Select hotel',
                allowClear: true
            });
            $('#tip-filter').select2({
                placeholder: '***Тип номеров***',
                allowClear: true
            });

            // Toggle the collapsible filter section
            $('#toggle-filter-btn').on('click', function() {
                $('#filter-section').collapse('toggle');
            });
            // Ajax search
            $('#region-filter, #hotel-filter, #tip-filter').on('change', function() {
                table.ajax.reload();
            });

        })

        function addPriceList() {
            $.confirm({
                title: '',
                content: 'url:/settings/roomprices/form',
                columnClass: 'col-md-12',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    formSubmit: {
                        text: 'Saqlash',
                        btnClass: 'btn-blue',
                        action: function() {
                            var jc = this;
                            $.confirm({
                                title: 'Подтверждение',
                                content: 'Вы уверены, что хотите сохраненя?',
                                type: 'orange',
                                buttons: {
                                    confirm: {
                                        text: 'Да',
                                        btnClass: 'btn-success',
                                        action: function() {
                                            var form = jc.$content.find('form');
                                            var formData = form.serialize();
                                            $.ajax({
                                                url: '{{ url('settings/roomprices/store') }}',
                                                method: 'POST',
                                                data: formData,
                                                success: function(response) {
                                                    $.alert({
                                                        title: 'Success',
                                                        content: response
                                                            .message,
                                                        type: 'green',
                                                        buttons: {
                                                            ok: {
                                                                text: 'OK',
                                                                btnClass: 'btn-success',
                                                                action: function() {
                                                                    location
                                                                        .reload();
                                                                }
                                                            }
                                                        }
                                                    });
                                                },
                                                error: function(xhr, status, error) {
                                                    $.alert({
                                                        title: 'Ошибка!!!',
                                                        content: 'Произошла ошибка при сохранении данных.',
                                                        type: 'red',
                                                        buttons: {
                                                            ok: {
                                                                text: 'OK',
                                                                btnClass: 'btn-danger',
                                                                action: function() {
                                                                    location
                                                                        .reload();
                                                                }
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    },
                                    cancel: {
                                        text: 'Нет',
                                        btnClass: 'btn-red',
                                        action: function() {
                                            jc.close();
                                        }
                                    }
                                }
                            });
                        }
                    },
                    close: {
                        text: 'Yopish',
                        btnClass: 'btn-red',
                        action: function() {}
                    },
                },
                onContentReady: function() {
                    var jc = this;
                    this.$content.find('form').on('submit', function(e) {
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click');
                    });
                }
            });
        }


        function showRoom(id) {
            $.confirm({
                title: '',
                content: `url:/settings/roomprices/show/${id}`,
                columnClass: 'col-md-6',
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

        function editRoom(id) {
            $.confirm({
                title: '',
                content: `url:/settings/roomprices/edit/${id}`,
                columnClass: 'col-md-6',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    formSubmit: {
                        text: 'Обновить',
                        btnClass: 'btn-blue',
                        action: function() {
                            var jc = this;
                            $.confirm({
                                title: 'Подтверждение',
                                content: 'Вы уверены, что хотите обновить данные?',
                                type: 'orange',
                                buttons: {
                                    confirm: {
                                        text: 'Да',
                                        btnClass: 'btn-success',
                                        action: function() {
                                            var form = jc.$content.find('form');
                                            var formData = form.serialize();
                                            $.ajax({
                                                url: '{{ url('settings/roomprices/update') }}/' +
                                                    id,
                                                method: 'POST',
                                                data: formData,
                                                success: function(response) {
                                                    $.alert({
                                                        title: 'Обновлено',
                                                        content: response
                                                            .message,
                                                        type: 'green',
                                                        buttons: {
                                                            ok: {
                                                                text: 'OK',
                                                                btnClass: 'btn-success',
                                                                action: function() {
                                                                    location
                                                                        .reload();
                                                                }
                                                            }
                                                        }
                                                    });
                                                },
                                                error: function(xhr, status, error) {
                                                    console.log(error);

                                                    $.alert(
                                                        'Произошла ошибка при обновлении данных.'
                                                    );
                                                }
                                            });
                                        }
                                    },
                                    cancel: {
                                        text: 'Нет',
                                        btnClass: 'btn-red',
                                        action: function() {
                                            jc.close();
                                        }
                                    }
                                }
                            });
                        }
                    },
                    close: {
                        text: 'Yopish',
                        btnClass: 'btn-red',
                        action: function() {}
                    }
                },
                onContentReady: function() {
                    var jc = this;
                    this.$content.find('form').on('submit', function(e) {
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click');
                    });
                }
            });
        }
    </script>
@endsection

@section('content')
    <div id="room-index">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="user">
                        <div class="card-header d-flex justify-content-between">
                            <div class="text-uppercase w-100">
                                <h5><i class="fas fa-table px-2"></i>Room Prices</h5>
                            </div>
                            <div class="d-flex">
                                <a class="btn btn-primary mr-3" onclick="addPriceList()"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- =============== filter part ============== --}}
                            <div class="d-flex justify-content-between align-items-center mb-3 bg-light">
                                <!-- Toggle Button to Show/Hide Filters -->
                                <button class="btn btn-info" id="toggle-filter-btn">
                                    <i class="fa fa-filter"></i> Filters
                                </button>
                            </div>

                            <!-- Collapsible Filter Inputs -->
                            <div class="collapse" id="filter-section">
                                <div class="d-flex justify-content-between align-items-center mb-3 ">
                                    <div class="d-flex">
                                        <!-- region Filter -->
                                        <select class="form-control" id="region-filter">
                                            <option value="">Select region</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- hotel Filter -->
                                        <select class="form-control" id="hotel-filter">
                                            <option value="">Select hotel</option>
                                            @foreach ($hotels as $hotel)
                                                <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- Tip Filter -->
                                        <select class="form-control" id="tip-filter">
                                            <option value="">Select type</option>
                                            @foreach ($roomTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            {{-- =============== filter part end============== --}}


                            <table class="table" id="roomprices-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>HOTELNAME</th>
                                        <th>DATE</th>
                                        <th>TYPE</th>
                                        <th>BEDS</th>
                                        <th>CAPACITY</th>
                                        <th>PRICE UZS</th>
                                        <th>PRICE USD</th>
                                        <th>BREAKFAST</th>
                                        <th>#</th>
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
    #roomprices-table_filter {
        display: none !important;
    }

    .select2-container {
        margin-right: 10px !important;
    }

    .select2.select2-container {
        width: 100% !important;
    }

    .select2.select2-container .select2-selection {
        padding-right: 50px !important;
        border: 1px solid #ccc;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        height: 35px;
        margin-bottom: 0px;
        outline: none !important;
        transition: all .15s ease-in-out;
    }

    .select2.select2-container .select2-selection .select2-selection__rendered {
        color: #333;
        line-height: 32px;
        padding-right: 33px;
    }

    .select2.select2-container .select2-selection .select2-selection__arrow {
        background: #f8f8f8;
        border-left: 1px solid #ccc;
        -webkit-border-radius: 0 3px 3px 0;
        -moz-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;
        height: 32px;
        width: 33px;
    }
</style>
