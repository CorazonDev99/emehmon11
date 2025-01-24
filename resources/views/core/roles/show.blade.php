@extends('layouts.app')

@section('style')
    <style>
        .tab-content {
            margin-top: 20px;
        }

        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            border: 1px solid #ddd;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
            padding: 10px;
        }

        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .tab-pane {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: 0;
            border-radius: 0 0 5px 5px;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table-sm td, .table-sm th {
            padding: 8px;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f1f1f1;
        }

        .table-hover tbody tr:hover {
            background-color: #e0e0e0;
        }

        .list-group-item {
            border: 1px solid #ddd;
        }

        .badge-warning {
            background-color: #ffc107;
        }
    </style>
@endsection
@section('header_title', 'View Role')
@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h2 class="mb-0">
                    <i class="fas fa-eye me-2"></i>View Role
                </h2>
                <a class="btn btn-light btn-sm" href="{{ route('roles.index') }}">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="roleTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#roleInfo" role="tab">Role Information</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#permissions" role="tab">Permissions information</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab3" data-bs-toggle="tab" href="#modules" role="tab">Access rights to modules</a>
                    </li>
                </ul>

                <div class="tab-content" id="roleTabsContent">

                    <div class="tab-pane fade show active" id="roleInfo" role="tabpanel">
                        <div class="card shadow-lg border-0 mb-4">
                            <div class="card-header bg-gradient-primary text-white">
                                <h5 class="mb-0">Role Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <p><strong>Role Name:</strong> {{ $role->name }}</p>
                                        <p><strong>SuperAdmin:</strong> {!! $role->is_admin ? '<span class="badge bg-success rounded-pill">Да</span>' : '<span class="badge bg-danger rounded-pill">Нет</span>' !!}</p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="tab2">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-gradient-info text-white">
                                <h5 class="mb-0">Access permissions</h5>
                            </div>
                            <div class="card-body">
                                @if($allPermissions->isNotEmpty())
                                    <ul class="list-group list-group-flush">
                                        @foreach($allPermissions as $permission)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                {{ $permission->name }}
                                                @if($rolePermissions->contains('id', $permission->id))
                                                    <span class="badge bg-success rounded-pill">Allowed</span>
                                                @else
                                                    <span class="badge bg-warning rounded-pill">Not assigned</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="alert alert-warning">
                                        No permissions available.
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="modules" role="tabpanel" aria-labelledby="tab3">
                        <h3 class="mb-4">Access rights to modules</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>Module</th>
                                    <th>View</th>
                                    <th>Create</th>
                                    <th>Read</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($modules as $module)
                                    <tr>
                                        <td>{{ $module->module_title }}</td>

                                        <td class="text-center">
                                            <i class="fas {{ $module->is_visible ? 'fa-check-circle' : 'fa-times-circle' }}"
                                               style="color: {{ $module->is_visible ? 'green' : 'red' }};"
                                               title="{{ $module->is_visible ? 'Доступно' : 'Не доступно' }}"></i>
                                        </td>

                                        <td class="text-center">
                                            <i class="fas {{ $module->is_create ? 'fa-check-circle' : 'fa-times-circle' }}"
                                               style="color: {{ $module->is_create ? 'green' : 'red' }};"
                                               title="{{ $module->is_create ? 'Разрешено' : 'Запрещено' }}"></i>
                                        </td>

                                        <td class="text-center">
                                            <i class="fas {{ $module->is_read ? 'fa-check-circle' : 'fa-times-circle' }}"
                                               style="color: {{ $module->is_read ? 'green' : 'red' }};"
                                               title="{{ $module->is_read ? 'Разрешено' : 'Запрещено' }}"></i>
                                        </td>

                                        <td class="text-center">
                                            <i class="fas {{ $module->is_edit ? 'fa-check-circle' : 'fa-times-circle' }}"
                                               style="color: {{ $module->is_edit ? 'green' : 'red' }};"
                                               title="{{ $module->is_edit ? 'Разрешено' : 'Запрещено' }}"></i>
                                        </td>

                                        <td class="text-center">
                                            <i class="fas {{ $module->is_delete ? 'fa-check-circle' : 'fa-times-circle' }}"
                                               style="color: {{ $module->is_delete ? 'green' : 'red' }};"
                                               title="{{ $module->is_delete ? 'Разрешено' : 'Запрещено' }}"></i>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
