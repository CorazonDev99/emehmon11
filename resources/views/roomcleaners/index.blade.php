@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" rel="stylesheet">
@endsection

@section('script')
    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


    <script>
        $(document).ready(function() {

            // load settings/hotelimages/data url to datatable
            let table = $('#roomcleaners-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/settings/room-cleaners/data',
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
                        name: 'h.name',
                        sClass: 'dt-center',
                        searchable: true
                    },

                    {
                        data: 'cleaner',
                        name: 'cleaner',
                        sClass: 'dt-center',
                        width: '200px'
                    },

                    {
                        data: 'work_start',
                        name: 'work_start',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'work_stop',
                        name: 'work_stop',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'active',
                        name: 'active',
                        render: function(data, type, row) {
                            if (data === 1 || data === '1') {
                                return `<span class="p-1 rounded text-light bold bg-success">Active</span>`
                            } else {
                                return `<span class="p-1 rounded text-light bold bg-warning">Not Active</span>`
                            }
                        }
                    },
                    {
                        data: 'entry_by',
                        name: 'entry_by',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        sClass: 'dt-center',
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        sClass: 'dt-center',
                        render: function(data, type, row) {
                            return `
                <a onclick="showData(${row.id})" class="btn btn-sm btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
</svg>
                    </a>
                <a onclick="updateData(${row.id})" class="btn btn-sm btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                    </svg>
                </a>
                <a onclick="destroyCleaner(${row.id})" class="btn btn-sm btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
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

        })

        // ================================
        function addCleaner() {
            $.confirm({
                title: '',
                content: 'url:/settings/room-cleaners/form',
                columnClass: 'col-md-6',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    formSubmit: {
                        text: 'Saqlash',
                        btnClass: 'btn-blue',
                        action: function() {
                            const jc = this;
                            const form = jc.$content.find('form');

                            $.confirm({
                                title: 'Подтверждение',
                                content: 'Вы уверены, что хотите сохранить?',
                                type: 'orange',
                                buttons: {
                                    confirm: {
                                        text: 'Да',
                                        btnClass: 'btn-success',
                                        action: function() {
                                            handleFormSubmit(form, jc);
                                        }
                                    },
                                    cancel: {
                                        text: 'Нет',
                                        btnClass: 'btn-red',
                                        action: function() {}
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
                    const jc = this;
                    this.$content.find('form').on('submit', function(e) {
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click');
                    });
                }
            });
        }

        function handleFormSubmit(form, jc) {
            const formData = form.serialize(); // Serialize form data for string and date fields

            $.ajax({
                url: '{{ url('settings/room-cleaners/store') }}',
                method: 'POST',
                data: formData,
                success: function(response) {
                    showAlert('Success', response.message || 'Record added successfully!', 'green', true);
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors || 'Произошла ошибка при сохранении данных.';
                    showAlert('Ошибка!!!', errors, 'red');
                },
                complete: function() {
                    jc.$$formSubmit.prop('disabled', false).text('Saqlash');
                }
            });
        }

        function showAlert(title, content, type, reload = false) {
            $.alert({
                title: title,
                content: content,
                type: type,
                buttons: {
                    ok: {
                        text: 'OK',
                        btnClass: type === 'green' ? 'btn-success' : 'btn-danger',
                        action: function() {
                            if (reload) location.reload();
                        }
                    }
                }
            });
        }

        function showData(id) {
            $.confirm({
                title: '',
                content: `url:/settings/room-cleaners/show/${id}`,
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

        function updateData(id) {
            $.confirm({
                title: '',
                content: `url:/settings/room-cleaners/edit/${id}`,
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
                                                url: '{{ url('settings/room-cleaners/update') }}/' +
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
                                                    $.alert(
                                                        error.message
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

        function destroyCleaner(id) {
            $.confirm({
                title: 'Подтверждение',
                content: 'Вы уверены, что хотите удалить это итем ?',
                type: 'orange',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: 'Да',
                        btnClass: 'btn-success',
                        action: function() {
                            $.ajax({
                                url: `/settings/room-cleaners/destroy/${id}`,
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    $.alert({
                                        title: 'Успешно!',
                                        content: response.success,
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
                                error: function(xhr) {
                                    $.alert({
                                        title: 'Ошибка!',
                                        content: 'Не удалось удалить итем. Попробуйте позже.',
                                        type: 'red',
                                        buttons: {
                                            ok: {
                                                text: 'OK',
                                                btnClass: 'btn-danger'
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
                        action: function() {}
                    }
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
                                <h5><i class="fas fa-image px-2"></i>Room Cleaners</h5>
                            </div>
                            <div class="d-flex">
                                <a class="btn btn-primary mr-3" onclick="addCleaner()"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table" id="roomcleaners-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>NAME OF THE HOTEL</th>
                                        <th>FULL NAME</th>
                                        <th>HIRING DATE</th>
                                        <th>FIRING DATE</th>
                                        <th>STATUS</th>
                                        <th>REGISTERD BY</th>
                                        <th>CREATED AT</th>
                                        <th style="min-width: 100px">#</th>
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
