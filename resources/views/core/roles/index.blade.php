@extends('layouts.app')
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(roleId) {
            Swal.fire({
                title: 'Вы уверены?',
                text: "Вы не сможете отменить это действие!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Да, удалить!',
                cancelButtonText: 'Нет'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/roles/${roleId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deleted!', data.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error!', data.message, 'warning');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        });
                }
            });
        }
    </script>
@endsection
@section('header_title', 'Roles')
@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center bg-gradient-info text-white">
                <h2 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-users-cog me-2"></i>Roles
                </h2>
                <a class="btn btn-success btn-sm" href="{{ route('roles.create') }}">
                    <i class="fas fa-plus-circle me-2"></i>Create New Role
                </a>
            </div>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table dataTable row-border table-hover table-striped">
                    <thead class="bg-gradient-info">
                    <tr>
                        <th><i class="fas fa-cogs me-2"></i>Role Name</th>
                        <th scope="col" width="30%"><i class="fas fa-shield-alt me-2"></i>Guard</th>
                        <th width="280px" class="text-center"><i class="fas fa-tools me-2"></i>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($roles as $key => $role)
                        <tr>
                            <td class="fw-bold">{{ $role->name }}</td>
                            <td>{{ $role->guard_name }}</td>
                            <td class="d-flex justify-content-start gap-3">
                                <a class="btn btn-outline-info btn-sm" href="{{ route('roles.show', $role->id) }}">
                                    <i class="fas fa-eye me-1"></i> Show
                                </a>
                                <a class="btn btn-outline-warning btn-sm" href="{{ route('roles.edit', $role->id) }}">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>

                                <form id="delete-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete({{ $role->id }})">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
