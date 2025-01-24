@extends('layouts.app')
@section('header_title', 'Users')
@section('style')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.0/dist/jquery-confirm.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

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
@endsection


@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        const roles = @json($roles);
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
                        d.username = $('#username-filter').val();
                        d.email = $('#email-filter').val();
                        d.first_name = $('#firstName-filter').val();
                        d.last_name = $('#lastName-filter').val();
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
                    { data: 'username', title: 'Username' },
                    { data: 'email', title: 'Email' },
                    { data: 'first_name', title: 'First Name' },
                    { data: 'last_name', title: 'Last Name' },
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
            $('#clear-filter-btn').on('click', function () {
                $('#username-filter').val('');
                $('#email-filter').val('');
                $('#firstName-filter').val('');
                $('#lastName-filter').val('');
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
                                        style="width: 200px; height: 200px; object-fit: cover;">
                                    <i class="fa fa-user ${avatarSrc ? 'd-none' : ''}" style="font-size: 200px;"></i>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group mb-2">
                                        <strong>First Name:</strong>
                                        <p class="mb-0">${rowData.first_name || ''}</p>
                                    </div>
                                    <div class="form-group mb-2">
                                        <strong>Last Name:</strong>
                                        <p class="mb-0">${rowData.last_name || ''}</p>
                                    </div>
                                    <div class="form-group mb-2">
                                        <strong>Username:</strong>
                                        <p class="mb-0">${rowData.username || ''}</p>
                                    </div>
                                    <div class="form-group mb-2">
                                        <strong>Email:</strong>
                                        <p class="mb-0">${rowData.email || ''}</p>
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

                if (!rowData) {
                    $.alert('Не удалось найти данные строки!');
                    return;
                }

                var avatarSrc = (rowData.avatar && rowData.avatar.match(/src="([^"]+)"/)) ? rowData.avatar.match(/src="([^"]+)"/)[1] : '';

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
                        `<input type="text" id="first_name" class="form-control" value="${rowData.first_name || ''}" required />` +
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
                        (avatarSrc ? `<img src="${avatarSrc}" width="50px" height="50px" style="text-shadow: 1px 1px; border:1px solid #777;" />` : '') +
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
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Role:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<select id="roles" name="roles[]" class="form-control">' +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        roles.map(role => {
                            const selected = rowData.role_id === role.id ? 'selected' : '';
                            return `<option value="${role.id}" ${selected}>${role.name}</option>`;
                        }).join('') +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
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
                                var formData = new FormData();
                                formData.append('user_id', rowData.user_id);
                                formData.append('first_name', $('#first_name').val());
                                formData.append('last_name', $('#last_name').val());
                                formData.append('username', $('#username').val());
                                formData.append('email', $('#email').val());
                                formData.append('avatar', $('#avatar')[0].files[0]);
                                formData.append('password', $('#password').val());
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
                                                icon: 'error',
                                                title: 'Error!',
                                                text: response.message
                                            });
                                        }
                                    },
                                    error: function (xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: xhr.responseJSON.message || 'Error occurred while editing user',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger'
                        }
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



    <script>
        $(document).ready(function () {
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
                        '<input type="text" id="first_name" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Last Name:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="text" id="last_name" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Username:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="text" id="username" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Email:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input type="email" id="email" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Profile Picture:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<input id="avatar" type="file" name="avatar" class="form-control">' +
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
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Role:</label>" +
                        "<div class='col-md-6 search-input'>" +
                        '<select id="roles" name="roles[]" class="form-control">' +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        roles.map(role => `<option value="${role.id}">${role.name}</option>`).join('') +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
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
                                var formData = new FormData();
                                formData.append('first_name', $('#first_name').val());
                                formData.append('last_name', $('#last_name').val());
                                formData.append('username', $('#username').val());
                                formData.append('email', $('#email').val());
                                formData.append('avatar', $('#avatar')[0].files[0]);
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
                                                icon: 'error',
                                                title: 'Error!',
                                                text: response.message
                                            });
                                        }
                                    },
                                    error: function (xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: xhr.responseJSON.message || 'Error occurred while creating user',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                });
                            }
                        },
                        cancel: {
                            text: 'Cancel',
                            btnClass: 'btn-danger'
                        }
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
                    <button class="btn btn-outline-primary rounded me-2" id="create-user">
                        <i class="fas fa-plus"></i>
                    </button>

                    <button type="button" class="btn btn-outline-info rounded me-2" id="toggle-fast-filter-btn">
                        <i class="fa fa-search-plus"></i>
                    </button>

                    <button type="button" class="btn btn-outline-info rounded me-2" id="clear-filter-btn">
                        <i class="fa fa-undo"></i>
                    </button>

                </div>
                <div class="collapse mt-3 mb-3" id="filter-section">
                    <div class="p-3 border rounded bg-light shadow-sm">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-wrap gap-2">
                                <input type="text" class="form-control" id="username-filter"
                                       placeholder="Username" style="width: 150px;">
                                <input type="text" class="form-control" id="email-filter"
                                       placeholder="Email" style="width: 150px;">
                                <input type="text" class="form-control" id="firstName-filter" placeholder="First Name"
                                       style="width: 150px;">
                                <input type="text" class="form-control" id="lastName-filter" placeholder="Last Name"
                                       style="width: 150px;">
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
