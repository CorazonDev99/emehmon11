@extends('layouts.app')
@section('header_title', 'Users')
@section('style')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.0/dist/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

<style>
    .card{
        margin-bottom: 0 !important;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1002;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.9);
    }

    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    .modal-content, #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @keyframes zoom {
        from {transform: scale(0)}
        to {transform: scale(1)}
    }

    .close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }



</style>
<style>
    .add-user {
        width: 85%;
        margin-left: 100px !important;
        margin-top: 15px !important
    }
    .userForm{
        margin: 20px;
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

<style>
    .jconfirm {
        z-index: 99999 !important;
    }

    .swal2-container {
        z-index: 150000 !important;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        const roles = @json($roles);
        const rolesEdit = @json($rolesEdit);
        const permissions = @json($permissions);
        const userPermissions = @json($userPermissions);
    </script>
    <script>
        $(document).ready(function () {
            var user = $('#userTable')

            var table = user.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/users/data',
                    data: function(d) {
                        d.name = $('#name-filter').val();
                        d.username = $('#username-filter').val();
                        d.email = $('#email-filter').val();
                        d.hotel_name = $('#hotelName-filter').val();
                        d.region_name = $('#regionName-filter').val();
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
                    { data: 'user_id', title: 'id', searchable: false, visible: false},
                    { data: 'name', title: 'Name' },
                    { data: 'email', title: 'Email' },
                    { data: 'username', title: 'Username' },
                    { data: 'hotel_name', title: 'Гостиница' },
                    { data: 'region_name', title: 'Регион' },
                    { data: 'avatar', title: 'Avatar' },
                    {
                        data: null,
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        width: '100px',
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `
                       <div class="d-flex text-center">
                            <button class="btn btn-sm btn-outline-success show-user me-2" data-id="${row.user_id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning edit-user me-2" data-id="${row.user_id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-user" data-id="${row.user_id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                        }
                    }
                ],
                responsive: true,
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

            $('#userTable tbody').on('dblclick', 'img', function () {
                var modal = document.getElementById("imagePopup");
                var modalImg = document.getElementById("img01");
                modal.style.display = "block";
                modalImg.src = this.src;
            });

            $('.close').on('click', function () {
                $('#imagePopup').hide();
            });

            $(window).on('click', function (event) {
                if (event.target.id === 'imagePopup') {
                    $('#imagePopup').hide();
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
            $('#clear-filter-btn').on('click', function () {
                $('#name-filter').val('');
                $('#username-filter').val('');
                $('#email-filter').val('');
                $('#hotelName-filter').val('').trigger('change');
                $('#regionName-filter').val('').trigger('change');
                table.ajax.params = {};
                table.search('').columns().search('');
                table.ajax.reload();
            });


            // Show User
            user.on('click', '.show-user', function () {
                var rowData = table.row($(this).closest('tr')).data();

                if (!rowData) {
                    $.alert('Не удалось найти данные строки!');
                    return;
                }

                var avatarSrc = (rowData.avatar && rowData.avatar.match(/src="([^"]+)"/)) ? rowData.avatar.match(/src="([^"]+)"/)[1] : '';

                var content = `
        <div class="container">
            <div class="card shadow-sm border-light rounded">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <i class="bi bi-person"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="roles-tab" data-bs-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false">
                                <i class="bi bi-shield"></i> Role
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="permissions-tab" data-bs-toggle="tab" href="#permissions" role="tab" aria-controls="permissions" aria-selected="false">
                                <i class="bi bi-lock"></i> Permissions
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="userTabsContent">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center mb-3">
                                   <img
                                        src="${avatarSrc ? avatarSrc : ''}"
                                        alt="Profile Image"
                                        class="img-fluid border ${avatarSrc ? '' : 'd-none'}"
                                        style="width: 200px; height: 200px; object-fit: cover; border-radius: 50%;">

                                    <i class="fa fa-user ${avatarSrc ? 'd-none' : ''}" style="font-size: 200px;"></i>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group mb-2">
                                        <strong>Name:</strong>
                                        <p class="mb-0">${rowData.name || ''} ${rowData.last_name || ''}</p>
                                    </div>

                                    <div class="form-group mb-2">
                                        <strong>Username:</strong>
                                        <p class="mb-0">${rowData.username || ''}</p>
                                    </div>
                                    <div class="form-group mb-2">
                                        <strong>Email:</strong>
                                        <p class="mb-0">${rowData.email || ''}</p>
                                    </div>
                                    <div class="form-group mb-2">
                                        <strong>Hotel:</strong>
                                        <p class="mb-0">${rowData.hotel_name || ''}</p>
                                    </div>
                                    <div class="form-group mb-2">
                                        <strong>Region:</strong>
                                        <p class="mb-0">${rowData.region_name || ''}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Role:</strong>
                                       <ul class="list-group mt-2">
                                            ${rowData.role_name || 'No role assigned'}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Permissions:</strong>
                                        <ul class="list-group mt-2">
                                           ${
                                                Array.isArray(userPermissions) && userPermissions.length > 0
                                                    ? userPermissions
                                                        .filter(permission => permission.model_id === rowData.user_id)
                                                        .map(permission =>
                                                            `<li class="list-group-item">${permission.name}</li>`
                                                        ).join('')
                                                    : 'No permissions assigned'
                                            }
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

                $.confirm({
                    title: 'Show User',
                    type: 'blue',
                    boxWidth: '900px',
                    useBootstrap: false,
                    content: content,
                    buttons: false
                });
            });



            // Edit User
            user.on('click', '.edit-user', function () {
                var rowData = table.row($(this).closest('tr')).data();
                let croppedImage = null;

                if (!rowData) {
                    $.alert('Не удалось найти данные строки!');
                    return;
                }

                var avatarSrc = (rowData.avatar && rowData.avatar.match(/src="([^"]+)"/)) ? rowData.avatar.match(/src="([^"]+)"/)[1] : '';
                currentAvatar = avatarSrc;
                $.confirm({
                    title: 'Edit User',
                    type: 'blue',
                    boxWidth: '900px',
                    useBootstrap: false,
                    content: '' +
                        "<div class='add-user'>" +
                        '<form id="userForm">' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>First Name:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        `<input type="text" id="first_name" class="form-control" value="${rowData.name || ''}" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Last Name:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        `<input type="text" id="last_name" class="form-control" value="${rowData.last_name || ''}" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Username:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        `<input type="text" id="username" class="form-control" value="${rowData.username || ''}" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Email:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        `<input type="email" id="email" class="form-control" value="${rowData.email || ''}" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Profile Picture:</label>" +
                        "<div class='col-md-6 search-input mb-1'>" +
                        `<input id="avatar" type="file" name="avatar" class="form-control">` +
                        `<div id="preview-container" style="margin-top: 10px; max-width:100%;">
                            ${avatarSrc ? `<img id="old-avatar" src="${avatarSrc}" style="max-width: 100px; border-radius: 5px;">` : ''}
                        </div>` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Password:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="password" id="password" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Confirm Password:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="password" id="confirm-password" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3' id='role-row'>" +
                        "<label class='col-md-3'>Role:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<select id="roles" name="roles[]" class="form-control">' +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        rolesEdit.map(role => {
                            if (rowData.role_id === {{ \Illuminate\Support\Facades\Auth::user()->roles->first()->id }}) {
                                const selected = rowData.role_id === role.id ? 'selected' : '';
                                return `<option value="${role.id}" ${selected}>${role.name}</option>`;
                            } else {
                                if (role.id === {{ \Illuminate\Support\Facades\Auth::user()->roles->first()->id }}) {
                                    return '';
                                }
                                const selected = rowData.role_id === role.id ? 'selected' : '';
                                return `<option value="${role.id}" ${selected}>${role.name}</option>`;
                            }

                        }).join('') +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3' id='permission-row'>" +
                        "<label class='col-md-3'>Permissions:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<div class="form-check">' +
                        permissions.map(permission => {
                            const checked = userPermissions.some(userPermission =>
                                userPermission.model_id === rowData.user_id && userPermission.id === permission.id
                            ) ? 'checked' : '';
                            return `
                                <div>
                                    <input type="checkbox" class="form-check-input" id="permission-${permission.id}"
                                        value="${permission.id}" ${checked}>
                                    <label class="form-check-label" for="permission-${permission.id}">
                                        ${permission.name}
                                    </label>
                                </div>`;
                        }).join('') +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</form>' +
                        '</div>',

                    buttons: {
                        confirm: {
                            text: 'Edit User',
                            btnClass: 'btn-primary',
                            action: function () {
                                var self = this;

                                var formData = new FormData();

                                const email = $('#email').val();
                                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                                if (!emailRegex.test(email)) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Ошибка!',
                                        text: 'Введите корректный email!',
                                    });
                                    return false;
                                }

                                formData.append('user_id', rowData.user_id);
                                formData.append('first_name', $('#first_name').val());
                                formData.append('last_name', $('#last_name').val());
                                formData.append('username', $('#username').val());
                                formData.append('email', email);
                                if (croppedImage) {
                                    formData.append('avatar', croppedImage, 'cropped-image.png');
                                }                                formData.append('password', $('#password').val());
                                formData.append('password_confirmation', $('#confirm-password').val());
                                formData.append('role_id', $('#roles').val());
                                var selectedPermissions = [];
                                $('input[type="checkbox"]:checked').each(function () {
                                    selectedPermissions.push($(this).val());
                                });
                                formData.append('permissions', JSON.stringify(selectedPermissions));
                                formData.append('_token', '{{ csrf_token() }}');

                                $.ajax({
                                    url: '{{ route("users.editUser") }}',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function (response) {
                                        if (response.success) {
                                            self.close();

                                            Swal.fire({
                                                icon: 'success',
                                                title: response.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'warning',
                                                text: response.message
                                            });
                                            return false;
                                        }
                                    },
                                    error: function (xhr) {
                                        Swal.fire({
                                            icon: 'warning',
                                            text: xhr.responseJSON.message || 'Error occurred while editing user',
                                            confirmButtonText: 'OK'
                                        });
                                        return false;

                                    }
                                });
                                return false;

                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger'
                        },

                    },
                    onContentReady: function () {
                        if (permissions.length == 0) {
                            $('#permission-row').hide();

                        }
                        document.getElementById('avatar').addEventListener('change', function () {
                            const file = this.files[0];
                            if (!file) return;

                            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                            if (!allowedTypes.includes(file.type)) {
                                Swal.fire({ icon: 'warning', title: 'Недопустимый формат!', text: 'Допустимые форматы: JPEG, PNG, GIF' });
                                this.value = '';
                                return;
                            }

                            if (file.size > 1024 * 1024) {
                                Swal.fire({ icon: 'warning', title: 'Недопустимый размер!', text: 'Размер файла не должен превышать 1MB' });
                                this.value = '';
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = function (event) {
                                $.confirm({
                                    title: 'Edit Image',
                                    content: '<div style="text-align: center;"><img id="cropper-image" src="' + event.target.result + '" style="max-width:100%;"></div>',
                                    boxWidth: '600px',
                                    useBootstrap: false,
                                    buttons: {
                                        crop: {
                                            text: 'Apply',
                                            btnClass: 'btn-success',
                                            action: function () {
                                                const canvas = cropper.getCroppedCanvas();
                                                canvas.toBlob((blob) => {
                                                    croppedImage = blob;
                                                    const url = URL.createObjectURL(blob);
                                                    $('#preview-container').html('<img src="' + url + '" style="max-width: 100px; border-radius: 5px;">');
                                                }, 'image/png');
                                            }
                                        },
                                        cancel: {
                                            text: 'Cancel',
                                            btnClass: 'btn-danger'
                                        }
                                    },
                                    onContentReady: function () {
                                        const image = document.getElementById('cropper-image');
                                        cropper = new Cropper(image, { aspectRatio: 1, viewMode: 2 });
                                    }
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                });
            });


            // Delete User
            user.on('click', '.delete-user', function () {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("users.deleteUser") }}',
                            type: 'DELETE',
                            data: {
                                id:id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $('#userTable').DataTable().ajax.reload(null, false);
                                } else {
                                    Swal.fire('Error!', response.message, 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('Error!', 'Something went wrong.', 'error');
                            }
                        });
                    }
                });
            });

        });
    </script>


    {{--    Create User--}}
    <script>
        $(document).ready(function () {
            let croppedImage = null;
            $('#create-user').on('click', function () {
                $.confirm({
                    title: 'Create User',
                    type: 'blue',
                    boxWidth: '900px',
                    useBootstrap: false,
                    content: '' +
                        "<div class='add-user'>" +
                        '<form id="userForm">' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>First Name:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="text" id="first_name" class="form-control" placeholder="First name" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Last Name:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="text" id="last_name" class="form-control" placeholder="Last name" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Username:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="text" id="username" class="form-control" placeholder="Username" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Email:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="email" id="email" class="form-control" placeholder="Email" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Profile Picture:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input id="avatar" type="file" name="avatar" class="form-control">' +
                        '<div id="preview-container" style="margin-top: 10px;"></div>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Password:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="password" id="password" class="form-control" placeholder="Password" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Confirm Password:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="password" id="confirm-password" class="form-control" placeholder="Confirm password" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3' id='role-row'>" +
                        "<label class='col-md-3'>Role:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<select id="roles" name="roles[]" class="form-control">' +
                        "<option value=''>-- NOT SELECTED --</option>" +
                        roles.map(role => `<option value="${role.id}">${role.name}</option>`).join('') +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3' id='permission-row'>" +
                        "<label class='col-md-3'>Permission:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        permissions.map(permission =>
                            `<div class="form-check">
                                <input type="checkbox" class="form-check-input" id="permission_${permission.id}" name="permissions[]" value="${permission.id}">
                                <label class="form-check-label" for="permission_${permission.id}">${permission.name}</label>
                            </div>`
                        ).join('') +
                        '</div>' +
                        '</div>' +
                        '</form>' +
                        '</div>',

                    buttons: {
                        confirm: {
                            text: 'Create User',
                            btnClass: 'btn-primary',
                            action: function () {
                                var self = this;
                                var formData = new FormData();
                                const email = $('#email').val();
                                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                                if (!emailRegex.test(email)) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Ошибка!',
                                        text: 'Введите корректный email!',
                                    });
                                    return false;
                                }
                                formData.append('first_name', $('#first_name').val());
                                formData.append('last_name', $('#last_name').val());
                                formData.append('username', $('#username').val());
                                formData.append('email', email);
                                if (croppedImage) {
                                    formData.append('avatar', croppedImage, 'cropped-image.png');
                                }
                                formData.append('password', $('#password').val());
                                formData.append('password_confirmation', $('#confirm-password').val());
                                formData.append('role_id', $('#roles').val());
                                formData.append('_token', '{{ csrf_token() }}');
                                var selectedPermissions = [];
                                $('input[name="permissions[]"]:checked').each(function() {
                                    selectedPermissions.push($(this).val());
                                });
                                formData.append('permissions', JSON.stringify(selectedPermissions));
                                formData.append('_token', '{{ csrf_token() }}');
                                $.ajax({
                                    url: '{{ route("users.createUser") }}',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function (response) {
                                        if (response.success) {
                                            self.close();
                                            Swal.fire({
                                                icon: 'success',
                                                title: response.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                location.reload();

                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'warning',
                                                text: response.message
                                            });
                                            return false;
                                        }
                                    },
                                    error: function (xhr) {
                                        Swal.fire({
                                            icon: 'warning',
                                            text: xhr.responseJSON.message || 'Error occurred while creating user',
                                            confirmButtonText: 'OK'
                                        });
                                        return false;
                                    }
                                });
                                return false;
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger'
                        }
                    },
                    onContentReady: function () {
                        if (permissions.length == 0) {
                            $('#permission-row').hide();
                        }
                        document.getElementById('avatar').addEventListener('change', function () {
                            const file = this.files[0];
                            if (!file) return;
                            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                            if (!allowedTypes.includes(file.type)) {
                                Swal.fire({ icon: 'warning', title: 'Недопустимый формат!', text: 'Допустимые форматы: JPEG, PNG, GIF' });
                                this.value = '';
                                return;
                            }

                            if (file.size > 1024 * 1024) {
                                Swal.fire({ icon: 'warning', title: 'Недопустимый размер!', text: 'Размер файла не должен превышать 1MB' });
                                this.value = '';
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = function (event) {
                                $.confirm({
                                    title: 'Edit Image',
                                    content: '<div style="text-align: center;"><img id="cropper-image" src="' + event.target.result + '" style="max-width:100%;"></div>',
                                    boxWidth: '600px',
                                    useBootstrap: false,
                                    buttons: {
                                        crop: {
                                            text: 'Apply',
                                            btnClass: 'btn-success',
                                            action: function () {
                                                const canvas = cropper.getCroppedCanvas();
                                                canvas.toBlob((blob) => {
                                                    croppedImage = blob;
                                                    const url = URL.createObjectURL(blob);
                                                    $('#preview-container').html('<img src="' + url + '" style="max-width: 100px; border-radius: 5px;">');
                                                }, 'image/png');
                                            }
                                        },
                                        cancel: {
                                            text: 'Cancel',
                                            btnClass: 'btn-danger'
                                        }
                                    },
                                    onContentReady: function () {
                                        const image = document.getElementById('cropper-image');
                                        cropper = new Cropper(image, { aspectRatio: 1, viewMode: 2 });
                                    }
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                    }

                });
            });
        });
    </script>

    <script>

        $(document).ready(function() {
            $('#hotelName-filter').select2({
                placeholder: "Select hotel",
                allowClear: true,
            }).on('select2:open', function() {
                $('.select2-dropdown').css('min-height', '20px');
            });

            $('#regionName-filter').select2({
                placeholder: "Select region",
                allowClear: true,
            }).on('select2:open', function() {
                $('.select2-dropdown').css('min-height', '20px');
            });
            $('#hotelName-filter, #regionName-filter').next('.select2-container').find('.select2-selection--single').css('height', '40px');
            $('#hotelName-filter, #regionName-filter').next('.select2-container').find('.select2-selection__rendered').css({
                'line-height': '40px'
            });

        });

    </script>


@endsection

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 mb-3">
                    <button class="btn btn-outline-primary rounded me-2" id="create-user">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button type="button" class="btn btn-outline-info rounded me-2" id="toggle-fast-filter-btn">
                        <i class="fa fa-search-plus"></i>
                    </button>

                    <button type="button" class="btn btn-outline-danger rounded me-2" id="clear-filter-btn">
                        <i class="fas fa-filter"></i> <i class="fas fa-times"></i>
                    </button>

                </div>
                <div class="collapse mt-3 mb-3" id="filter-section">
                    <div class="p-3 border rounded bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-wrap gap-2">
                                <input type="text" class="form-control" id="name-filter" placeholder="Name" style="width: 150px;">
                                <input type="text" class="form-control" id="email-filter" placeholder="Email" style="width: 150px;">
                                <input type="text" class="form-control" id="username-filter" placeholder="Username" style="width: 150px;">
                                <select id="regionName-filter" class="form-control select2" style="width: 200px;">
                                    <option value="">Выберите регион</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>

                                <select id="hotelName-filter" class="form-control select2" style="width: 240px;">
                                    <option value="">Выберите отель</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                                    @endforeach
                                </select>


                                <button type="button" class="btn btn-dark" id="search-btn">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="listok-table-container" class="loading-overlay">
                    <div id="custom-loading">
                        <div class="spinner"></div>
                    </div>
                    <table id="userTable" class="table bg-gradient-info dataTable row-border table-hover table-striped">
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
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>

                <div id="imagePopup" class="modal">
                    <span class="close">&times;</span>
                    <img class="modal-content" id="img01">
                </div>

            </div>
        </div>
    </div>




@endsection
