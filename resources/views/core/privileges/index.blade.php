@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-confirm@3.3.0/dist/jquery-confirm.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        #moduleForm{
            margin-left: 100px !important;
            margin-right: 20px !important;
            margin-top: 20px !important;
        }

    </style>
    <style>
        .checkbox-wrapper-5 .check {
            --size: 40px;

            position: relative;
            background: linear-gradient(90deg, #9af3ec, #7ac0f1);
            line-height: 0;
            perspective: 400px;
            font-size: var(--size);
        }

        .checkbox-wrapper-5 .check input[type="checkbox"],
        .checkbox-wrapper-5 .check label,
        .checkbox-wrapper-5 .check label::before,
        .checkbox-wrapper-5 .check label::after,
        .checkbox-wrapper-5 .check {
            appearance: none;
            display: inline-block;
            border-radius: var(--size);
            border: 0;
            transition: .35s ease-in-out;
            box-sizing: border-box;
            cursor: pointer;
        }

        .checkbox-wrapper-5 .check label {
            width: calc(2.2 * var(--size));
            height: var(--size);
            background: #d7d7d7;
            overflow: hidden;
        }

        .checkbox-wrapper-5 .check input[type="checkbox"] {
            position: absolute;
            z-index: 1;
            width: calc(.8 * var(--size));
            height: calc(.8 * var(--size));
            top: calc(.1 * var(--size));
            left: calc(.1 * var(--size));
            background: linear-gradient(45deg, #dedede, #ffffff);
            box-shadow: 0 6px 7px rgba(0,0,0,0.3);
            outline: none;
            margin: 0;
        }

        .checkbox-wrapper-5 .check input[type="checkbox"]:checked {
            left: calc(1.3 * var(--size));
        }

        .checkbox-wrapper-5 .check input[type="checkbox"]:checked + label {
            background: transparent;
        }

        .checkbox-wrapper-5 .check label::before,
        .checkbox-wrapper-5 .check label::after {
            content: "· ·";
            position: absolute;
            overflow: hidden;
            left: calc(.15 * var(--size));
            top: calc(.5 * var(--size));
            height: var(--size);
            letter-spacing: calc(-0.04 * var(--size));
            color: #9b9b9b;
            font-family: "Times New Roman", serif;
            z-index: 2;
            font-size: calc(.6 * var(--size));
            border-radius: 0;
            transform-origin: 0 0 calc(-0.5 * var(--size));
            backface-visibility: hidden;
        }

        .checkbox-wrapper-5 .check label::after {
            content: "●";
            top: calc(.65 * var(--size));
            left: calc(.2 * var(--size));
            height: calc(.1 * var(--size));
            width: calc(.35 * var(--size));
            font-size: calc(.2 * var(--size));
            transform-origin: 0 0 calc(-0.4 * var(--size));
        }

        .checkbox-wrapper-5 .check input[type="checkbox"]:checked + label::before,
        .checkbox-wrapper-5 .check input[type="checkbox"]:checked + label::after {
            left: calc(1.55 * var(--size));
            top: calc(.4 * var(--size));
            line-height: calc(.1 * var(--size));
            transform: rotateY(360deg);
        }

        .checkbox-wrapper-5 .check input[type="checkbox"]:checked + label::after {
            height: calc(.16 * var(--size));
            top: calc(.55 * var(--size));
            left: calc(1.6 * var(--size));
            font-size: calc(.6 * var(--size));
            line-height: 0;
        }
        label {
            margin-top: 0.0rem !important;
        }
    </style>
@endsection
@section('script')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function () {
            var module = $('#modulesTable')

            var table = module.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/privileges/data',
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
                    { data: 'module_id', title: 'module_id', searchable: false, visible: false},
                    { data: 'module_name', title: 'Module Name' },
                    { data: 'module_title', title: 'Module Title' },
                    { data: 'module_note', title: 'Module Note', width: '150px'},
                    { data: 'module_author', title: 'Module Author' },
                    { data: 'module_created', title: 'Created At' },
                    { data: 'module_desc', title: 'Description', width: '150px'},
                    { data: 'module_db', title: 'Database' },
                    { data: 'module_db_key', title: 'DB Key' },
                    { data: 'module_type', title: 'Type' },
                    {
                        data: 'activate',
                        title: 'Status',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            if (data == 1) {
                                return '<i class="fas fa-check-circle text-success"></i>';
                            } else {
                                return '<i class="fas fa-times-circle text-danger"></i>';
                            }
                        }
                    },
                    {
                        data: null,
                        title: 'Actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `
                       <div class="d-flex justify-content-around">
                            <button class="btn btn-sm btn-outline-warning edit-module me-2" data-id="${row.module_id}">
                                <i class="fas fa-edit"></i>
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


            module.on('click', '.edit-module', function () {
                var rowData = table.row($(this).closest('tr')).data();
                if (!rowData) {
                    $.alert('Не удалось найти данные строки!');
                    return;
                }
                $.confirm({
                    title: 'Edit Module',
                    type: 'blue',
                    boxWidth: '900px',
                    useBootstrap: false,
                    content: '' +
                        "<div class='correct-module'>" +
                        '<form id="moduleForm">' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Name:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<input type="text" id="module_name" value="${rowData.module_name || ''}" class="form-control" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Title:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<input type="text" id="module_title" value="${rowData.module_title || ''}" class="form-control" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Note:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<input type="text" id="module_note" value="${rowData.module_note || ''}" class="form-control" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Author:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<input type="text" id="module_author" value="${rowData.module_author || ''}" class="form-control" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Description:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<textarea id="module_desc" class="form-control">${rowData.module_desc || ''}</textarea>` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Database:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<input type="text" id="module_db" value="${rowData.module_db || ''}" class="form-control" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module DB Key:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<input type="text" id="module_db_key" value="${rowData.module_db_key || ''}" class="form-control" required />` +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Type:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        `<select id="module_type" class="form-control">` +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        `<option value="master" ${rowData.module_type === 'master' ? 'selected' : ''}>Master</option>` +
                        `<option value="report" ${rowData.module_type === 'report' ? 'selected' : ''}>Report</option>` +
                        `<option value="process" ${rowData.module_type === 'process' ? 'selected' : ''}>Process</option>` +
                        `<option value="core" ${rowData.module_type === 'core' ? 'selected' : ''}>Core</option>` +
                        `<option value="generic" ${rowData.module_type === 'generic' ? 'selected' : ''}>Generic</option>` +
                        `<option value="addon" ${rowData.module_type === 'addon' ? 'selected' : ''}>Addon</option>` +
                        `<option value="ajax" ${rowData.module_type === 'ajax' ? 'selected' : ''}>Ajax</option>` +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ACTIVE:</label>" +
                        "<div class='col-md-7 checkbox-wrapper-5'>" +
                        '<div class="check">' +
                        '<input id="module_active" type="checkbox" name="module_active" value="1" ' +
                        (rowData.activate == 1 ? 'checked' : '') + '>' +
                        '<label for="module_active"></label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</form>',
                    buttons: {
                        confirm: {
                            text: 'Изменить',
                            btnClass: 'btn-success',
                            action: function () {
                                var moduleName = $('#module_name').val();
                                var moduleTitle = $('#module_title').val();
                                var moduleDesc = $('#module_desc').val();
                                var moduleType = $('#module_type').val();
                                var moduleNote = $('#module_note').val();
                                var moduleAuthor = $('#module_author').val();
                                var moduleDB = $('#module_db').val();
                                var moduleDBKey = $('#module_db_key').val();
                                var moduleActive = $('#module_active').is(':checked') ? 1 : 0;


                                if (!moduleName || !moduleTitle) {
                                    $.alert('Please fill out all required fields');
                                    return false;
                                }

                                var formData = {
                                    module_id: rowData.module_id,
                                    module_name: moduleName,
                                    module_title: moduleTitle,
                                    module_desc: moduleDesc,
                                    module_note: moduleNote,
                                    module_author: moduleAuthor,
                                    module_db: moduleDB,
                                    module_db_key: moduleDBKey,
                                    module_type: moduleType,
                                    activate: moduleActive,
                                    _token: '{{ csrf_token() }}'

                                };

                                $.ajax({
                                    url: '/privileges/edit',
                                    type: 'POST',
                                    data: formData,
                                    success: function (response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: response.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                $('#modulesTable').DataTable().ajax.reload(null, false);
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error!',
                                                text: 'Failed to edit module'
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Error occurred while creating module',
                                            confirmButtonText: 'OK'
                                        });
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
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#addModuleBtn').on('click', function () {
                $.confirm({
                    title: 'Add New Module',
                    type: 'blue',
                    boxWidth: '900px',
                    useBootstrap: false,
                    content: '' +
                        "<div class='add-module'>" +
                        '<form id="moduleForm">' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Name:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<input type="text" id="module_name" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Title:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<input type="text" id="module_title" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Note:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<input type="text" id="module_note" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Author:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<input type="text" id="module_author" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Description:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<textarea id="module_desc" class="form-control"></textarea>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Database:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<input type="text" id="module_db" class="form-control" required />' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module DB Key:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<input type="text" id="module_db_key" class="form-control" required />' +
                        '</div>' +
                        '</div>' +

                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>Module Type:</label>" +
                        "<div class='col-md-7 search-input'>" +
                        '<select id="module_type" class="form-control">' +
                        "<option value=''>-- НЕ ВЫБРАНО --</option>" +
                        '<option value="master">Master</option>' +
                        '<option value="report">Report</option>' +
                        '<option value="process">Process</option>' +
                        '<option value="core">Core</option>' +
                        '<option value="generic">Generic</option>' +
                        '<option value="addon">Addon</option>' +
                        '<option value="ajax">Ajax</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        "<div class='row align-items-center mb-3'>" +
                        "<label class='col-md-3'>ACTIVE:</label>" +
                        "<div class='col-md-7 checkbox-wrapper-5'>" +
                        '<div class="check">' +
                        '<input id="module_active" type="checkbox" name="module_active" value="1" checked>' +
                        '<label for="module_active"></label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</form>' +
                        '</div>',

                    buttons: {
                        confirm: {
                            text: 'Create Module',
                            btnClass: 'btn-primary',
                            action: function () {
                                var moduleName = $('#module_name').val();
                                var moduleTitle = $('#module_title').val();
                                var moduleDesc = $('#module_desc').val();
                                var moduleType = $('#module_type').val();
                                var moduleNote = $('#module_note').val();
                                var moduleAuthor = $('#module_author').val();
                                var moduleDB = $('#module_db').val();
                                var moduleDBKey = $('#module_db_key').val();
                                var moduleActive = $('#module_active').is(':checked') ? 1 : 0;

                                if (!moduleName || !moduleTitle) {
                                    $.alert('Please fill out all required fields');
                                    return false;
                                }

                                var formData = {
                                    module_name: moduleName,
                                    module_title: moduleTitle,
                                    module_desc: moduleDesc,
                                    module_note: moduleNote,
                                    module_author: moduleAuthor,
                                    module_db: moduleDB,
                                    module_db_key: moduleDBKey,
                                    module_type: moduleType,
                                    activate: moduleActive,
                                    _token: '{{ csrf_token() }}'

                                };

                                $.ajax({
                                    url: '/privileges/create',
                                    type: 'POST',
                                    data: formData,
                                    success: function (response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: response.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                $('#modulesTable').DataTable().ajax.reload(null, false);
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error!',
                                                text: 'Failed to create module'
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error!',
                                            text: 'Error occurred while creating module',
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
@section('header_title', 'Modules')
@section('content')

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-key me-2"></i> Modules
                </h2>
                <button id="addModuleBtn" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Add Modules
                </button>
            </div>
            <div class="card-body">

                    <table id="modulesTable" class="table dataTable row-border table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="non_searchable"></th>
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
                        <tbody></tbody>
                    </table>
            </div>
        </div>
    </div>
@endsection



