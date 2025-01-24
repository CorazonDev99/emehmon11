@extends('layouts.app')
@section('header_title', 'Edit Permissions')
@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h2 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Edit Permission
                </h2>
            </div>
            <div class="card-body">
                <div class="lead mb-4">
                    You can update the permission details here.
                </div>

                <form method="POST" action="/permissions/{{ $permission->id }}">
                    @method('patch')
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Permission Name</label>
                        <input value="{{ $permission->name }}"
                               type="text"
                               class="form-control"
                               name="name"
                               placeholder="Enter permission name"
                               required>
                        @if ($errors->has('name'))
                            <div class="text-danger mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                        <a href="/permissions" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
