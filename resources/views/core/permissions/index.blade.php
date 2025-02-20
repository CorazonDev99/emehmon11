@extends('layouts.app')
@section('header_title', 'Permissions')
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(permissionId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${permissionId}`).submit();
                }
            });
        }
    </script>
@endsection
@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-key me-2"></i> Permissions
                </h2>

                <a href="/permissions/create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>Add Permission
                </a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    @include('layouts.messages')
                </div>

                @if($permissions->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>No permissions found. Add a new one!
                    </div>
                @else
                    <table class="table dataTable row-border table-hover table-striped">
                        <thead>
                        <tr>
                            <th scope="col" width="50%"><i class="fas fa-user me-2"></i>Name</th>
                            <th scope="col" width="30%"><i class="fas fa-shield-alt me-2"></i>Guard</th>
                            <th scope="col" class="text-center" colspan="2"><i class="fas fa-cogs me-2"></i>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td class="fw-bold">{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form id="delete-form-{{ $permission->id }}" action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $permission->id }})">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
@endsection
