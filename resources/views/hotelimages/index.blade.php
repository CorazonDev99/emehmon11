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
            let table = $('#roomprices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/settings/hotel-images/data',
                },

                columns: [

                    {
                        data: 'id',
                        name: 'id',
                        searchable: false,
                        visible: false
                    },
                    {
                        data: 'region_name',
                        name: 'region_name',
                        sClass: 'dt-center',
                    },

                    {
                        data: 'hotel_name',
                        name: 'hotel_name',
                        sClass: 'dt-center',
                        width: '200px'
                    },

                    {
                        data: 'hoteltype',
                        name: 'hoteltype',
                        sClass: 'dt-center',
                    },
                    {
                        data: 'roomtype',
                        name: 'roomtype',
                        sClass: 'dt-center',
                    },

                    {
                        data: 'photo',
                        name: 'photo',
                        sClass: 'dt-center',
                        render: function(data, type, row) {
                            return `
                            <a href="${row.photo}" target="_blank" rel="noopener noreferrer">
                                <img src="${row.photo}" alt="${row.hotel_name}" width="80" height="80">
                            </a>
                            `;
                        }
                    },
                    {
                        data: 'is_main',
                        name: 'is_main',
                        render: function(data, type, row) {
                            if (data === 1 || data === '1') {
                                return `<i class="fas fa-check-circle px-2"></i>`
                            } else {
                                return ``
                            }
                        }
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
                            <a onclick="updateImage(${row.id})" class="btn btn-sm btn-primary">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                </svg>
                                </a>
                            <a class="btn btn-sm btn-danger" onclick="destroyHotelImage(${row.id})">
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
        function addImages() {
            $.confirm({
                title: '',
                content: 'url:/settings/hotel-images/form',
                columnClass: 'col-md-6',
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    formSubmit: {
                        text: 'Saqlash',
                        btnClass: 'btn-blue',
                        action: function() {
                            var jc = this;
                            var form = jc.$content.find('form');

                            var photoInput = form.find('input[name="photo"]')['0'];
                            var roomTypeInput = form.find('select[name="room_tp"]');
                            var allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                            var maxFileSize = 2 * 1024 * 1024;
                            var isValid = true;
                            var errorMessage = '';


                            if (!photoInput.files || photoInput.files.length === 0) {
                                errorMessage += 'Фото обязательно для загрузки.<br>';
                                isValid = false;
                            } else {
                                var file = photoInput.files[0];

                                // Check file type
                                if (!allowedFileTypes.includes(file.type)) {
                                    errorMessage +=
                                        'Пожалуйста, загрузите изображение в формате JPEG, PNG или GIF.<br>';
                                    isValid = false;
                                }

                                // Check file size
                                if (file.size > maxFileSize) {
                                    errorMessage += 'Размер файла не должен превышать 2 МБ.<br>';
                                    isValid = false;
                                }
                            }

                            if (!isValid) {
                                $.alert({
                                    title: 'Ошибка!',
                                    content: errorMessage,
                                    type: 'red',
                                    buttons: {
                                        ok: {
                                            text: 'OK',
                                            btnClass: 'btn-danger',
                                        }
                                    }
                                });
                                return false;
                            }

                            $.confirm({
                                title: 'Подтверждение',
                                content: 'Вы уверены, что хотите сохранить?',
                                type: 'orange',
                                buttons: {
                                    confirm: {
                                        text: 'Да',
                                        btnClass: 'btn-success',
                                        action: function() {
                                            var formData = new FormData(form[0]);

                                            jc.$$formSubmit.prop('disabled', true);
                                            jc.$$formSubmit.text('Загрузка...');

                                            $.ajax({
                                                url: '{{ url('settings/hotel-images/store') }}',
                                                method: 'POST',
                                                data: formData,
                                                processData: false,
                                                contentType: false,
                                                success: function(response) {
                                                    // This will be triggered only if the backend returns a success status code
                                                    $.alert({
                                                        title: 'Success',
                                                        content: response
                                                            .message ||
                                                            'You have added new image',
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
                                                    // Triggered on error status (like 422, 400, 500)
                                                    var errors = xhr.responseJSON ? xhr
                                                        .responseJSON.errors : null;
                                                    var errorMessages = '';

                                                    if (errors) {
                                                        errorMessages = errors;
                                                    } else {
                                                        errorMessages =
                                                            'Произошла ошибка при сохранении данных.';
                                                    }

                                                    $.alert({
                                                        title: 'Ошибка!!!',
                                                        content: errorMessages,
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
                                                },
                                                complete: function() {
                                                    jc.$$formSubmit.prop('disabled',
                                                        false);
                                                    jc.$$formSubmit.text('Saqlash');
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


        // ===========================

        function destroyHotelImage(id) {
            $.confirm({
                title: 'Подтверждение',
                content: 'Вы уверены, что хотите удалить это изображение?',
                type: 'orange',
                typeAnimated: true,
                buttons: {
                    confirm: {
                        text: 'Да',
                        btnClass: 'btn-success',
                        action: function() {
                            $.ajax({
                                url: `/settings/hotel-images/destroy/${id}`,
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    $.alert({
                                        title: 'Успешно!',
                                        content: 'Изображение было успешно удалено.',
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
                                        content: 'Не удалось удалить изображение. Попробуйте позже.',
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


        function updateImage(id) {
            $.confirm({
                title: '',
                content: `url:/settings/hotel-images/edit/${id}`,
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
                                                url: '{{ url('settings/hotel-images/update') }}/' +
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
                                <h5><i class="fas fa-image px-2"></i>Hotel Images</h5>
                            </div>
                            <div class="d-flex">
                                <a class="btn btn-primary mr-3" onclick="addImages()"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>

                        <div class="card-body">
                            <table class="table" id="roomprices-table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>REGION</th>
                                        <th>NAME OF THE HOTEL</th>
                                        <th>TYPE OF THE HOTEL</th>
                                        <th>TYPE</th>
                                        <th>IMAGE</th>
                                        <th>MAIN</th>
                                        <th>DATE</th>
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
