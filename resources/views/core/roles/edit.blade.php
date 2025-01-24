@extends('layouts.app')
@section('style')
    <style>
        .checkbox-wrapper-19 {
            box-sizing: border-box;
            --background-color: #fff;
            --checkbox-height: 25px;
        }

        .checkbox-wrapper-19 input[type=checkbox] {
            display: none;
        }

        .checkbox-wrapper-19 .check-box {
            height: var(--checkbox-height);
            width: var(--checkbox-height);
            background-color: transparent;
            border: calc(var(--checkbox-height) * .1) solid #000;
            border-radius: 5px;
            position: relative;
            display: inline-block;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            -moz-transition: border-color ease 0.2s;
            -o-transition: border-color ease 0.2s;
            -webkit-transition: border-color ease 0.2s;
            transition: border-color ease 0.2s;
            cursor: pointer;
        }

        .checkbox-wrapper-19 .check-box::before,
        .checkbox-wrapper-19 .check-box::after {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            position: absolute;
            height: 0;
            width: calc(var(--checkbox-height) * .2);
            background-color: #34b93d;
            display: inline-block;
            -moz-transform-origin: left top;
            -ms-transform-origin: left top;
            -o-transform-origin: left top;
            -webkit-transform-origin: left top;
            transform-origin: left top;
            border-radius: 5px;
            content: " ";
            -webkit-transition: opacity ease 0.5;
            -moz-transition: opacity ease 0.5;
            transition: opacity ease 0.5;
        }

        .checkbox-wrapper-19 .check-box::before {
            top: calc(var(--checkbox-height) * .72);
            left: calc(var(--checkbox-height) * .41);
            box-shadow: 0 0 0 calc(var(--checkbox-height) * .05) var(--background-color);
            -moz-transform: rotate(-135deg);
            -ms-transform: rotate(-135deg);
            -o-transform: rotate(-135deg);
            -webkit-transform: rotate(-135deg);
            transform: rotate(-135deg);
        }

        .checkbox-wrapper-19 .check-box::after {
            top: calc(var(--checkbox-height) * .37);
            left: calc(var(--checkbox-height) * .05);
            -moz-transform: rotate(-45deg);
            -ms-transform: rotate(-45deg);
            -o-transform: rotate(-45deg);
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
        }

        .checkbox-wrapper-19 input[type=checkbox]:checked + .check-box,
        .checkbox-wrapper-19 .check-box.checked {
            border-color: #34b93d;
        }

        .checkbox-wrapper-19 input[type=checkbox]:checked + .check-box::after,
        .checkbox-wrapper-19 .check-box.checked::after {
            height: calc(var(--checkbox-height) / 2);
            -moz-animation: dothabottomcheck-19 0.2s ease 0s forwards;
            -o-animation: dothabottomcheck-19 0.2s ease 0s forwards;
            -webkit-animation: dothabottomcheck-19 0.2s ease 0s forwards;
            animation: dothabottomcheck-19 0.2s ease 0s forwards;
        }

        .checkbox-wrapper-19 input[type=checkbox]:checked + .check-box::before,
        .checkbox-wrapper-19 .check-box.checked::before {
            height: calc(var(--checkbox-height) * 1.2);
            -moz-animation: dothatopcheck-19 0.4s ease 0s forwards;
            -o-animation: dothatopcheck-19 0.4s ease 0s forwards;
            -webkit-animation: dothatopcheck-19 0.4s ease 0s forwards;
            animation: dothatopcheck-19 0.4s ease 0s forwards;
        }
    </style>

    <style>
        .form-check{
            margin-top:10px !important;
        }
        .checkbox-wrapper-46 input[type="checkbox"] {
            display: none;
            visibility: hidden;
        }

        .checkbox-wrapper-46 .cbx {
            margin: auto;
            -webkit-user-select: none;
            user-select: none;
            cursor: pointer;
        }
        .checkbox-wrapper-46 .cbx span {
            display: inline-block;
            vertical-align: middle;
            transform: translate3d(0, 0, 0);
        }
        .checkbox-wrapper-46 .cbx span:first-child {
            position: relative;
            width: 18px;
            height: 18px;
            border-radius: 3px;
            transform: scale(1);
            vertical-align: middle;
            border: 1px solid #9098A9;
            transition: all 0.2s ease;
        }
        .checkbox-wrapper-46 .cbx span:first-child svg {
            position: absolute;
            top: 3px;
            left: 2px;
            fill: none;
            stroke: #FFFFFF;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 16px;
            stroke-dashoffset: 16px;
            transition: all 0.3s ease;
            transition-delay: 0.1s;
            transform: translate3d(0, 0, 0);
        }
        .checkbox-wrapper-46 .cbx span:first-child:before {
            content: "";
            width: 100%;
            height: 100%;
            background: #506EEC;
            display: block;
            transform: scale(0);
            opacity: 1;
            border-radius: 50%;
        }
        .checkbox-wrapper-46 .cbx span:last-child {
            padding-left: 8px;
        }
        .checkbox-wrapper-46 .cbx:hover span:first-child {
            border-color: #506EEC;
        }

        .checkbox-wrapper-46 .inp-cbx:checked + .cbx span:first-child {
            background: #506EEC;
            border-color: #506EEC;
            animation: wave-46 0.4s ease;
        }
        .checkbox-wrapper-46 .inp-cbx:checked + .cbx span:first-child svg {
            stroke-dashoffset: 0;
        }
        .checkbox-wrapper-46 .inp-cbx:checked + .cbx span:first-child:before {
            transform: scale(3.5);
            opacity: 0;
            transition: all 0.6s ease;
        }

        @keyframes wave-46 {
            50% {
                transform: scale(0.9);
            }
        }
    </style>


    <style>
        @supports (-webkit-appearance: none) or (-moz-appearance: none) {
            .checkbox-wrapper-14 input[type=checkbox] {
                --active: #275EFE;
                --active-inner: #fff;
                --focus: 2px rgba(39, 94, 254, .3);
                --border: #BBC1E1;
                --border-hover: #275EFE;
                --background: #fff;
                --disabled: #F6F8FF;
                --disabled-inner: #E1E6F9;
                -webkit-appearance: none;
                -moz-appearance: none;
                height: 21px;
                outline: none;
                display: inline-block;
                vertical-align: top;
                position: relative;
                margin: 0;
                cursor: pointer;
                border: 1px solid var(--bc, var(--border));
                background: var(--b, var(--background));
                transition: background 0.3s, border-color 0.3s, box-shadow 0.2s;
            }
            .checkbox-wrapper-14 input[type=checkbox]:after {
                content: "";
                display: block;
                left: 0;
                top: 0;
                position: absolute;
                transition: transform var(--d-t, 0.3s) var(--d-t-e, ease), opacity var(--d-o, 0.2s);
            }
            .checkbox-wrapper-14 input[type=checkbox]:checked {
                --b: var(--active);
                --bc: var(--active);
                --d-o: .3s;
                --d-t: .6s;
                --d-t-e: cubic-bezier(.2, .85, .32, 1.2);
            }
            .checkbox-wrapper-14 input[type=checkbox]:disabled {
                --b: var(--disabled);
                cursor: not-allowed;
                opacity: 0.9;
            }
            .checkbox-wrapper-14 input[type=checkbox]:disabled:checked {
                --b: var(--disabled-inner);
                --bc: var(--border);
            }
            .checkbox-wrapper-14 input[type=checkbox]:disabled + label {
                cursor: not-allowed;
            }
            .checkbox-wrapper-14 input[type=checkbox]:hover:not(:checked):not(:disabled) {
                --bc: var(--border-hover);
            }
            .checkbox-wrapper-14 input[type=checkbox]:focus {
                box-shadow: 0 0 0 var(--focus);
            }
            .checkbox-wrapper-14 input[type=checkbox]:not(.switch) {
                width: 21px;
            }
            .checkbox-wrapper-14 input[type=checkbox]:not(.switch):after {
                opacity: var(--o, 0);
            }
            .checkbox-wrapper-14 input[type=checkbox]:not(.switch):checked {
                --o: 1;
            }
            .checkbox-wrapper-14 input[type=checkbox] + label {
                display: inline-block;
                vertical-align: middle;
                cursor: pointer;
                margin-left: 4px;
            }

            .checkbox-wrapper-14 input[type=checkbox]:not(.switch) {
                border-radius: 7px;
            }
            .checkbox-wrapper-14 input[type=checkbox]:not(.switch):after {
                width: 5px;
                height: 9px;
                border: 2px solid var(--active-inner);
                border-top: 0;
                border-left: 0;
                left: 7px;
                top: 4px;
                transform: rotate(var(--r, 20deg));
            }
            .checkbox-wrapper-14 input[type=checkbox]:not(.switch):checked {
                --r: 43deg;
            }
            .checkbox-wrapper-14 input[type=checkbox].switch {
                height: 23px;
                width: 38px;
                border-radius: 11px;
            }
            .checkbox-wrapper-14 input[type=checkbox].switch:after {
                left: 2px;
                top: 2px;
                border-radius: 50%;
                width: 17px;
                height: 17px;
                background: var(--ab, var(--border));
                transform: translateX(var(--x, 0));
            }
            .checkbox-wrapper-14 input[type=checkbox].switch:checked {
                --ab: var(--active-inner);
                --x: 17px;
            }
            .checkbox-wrapper-14 input[type=checkbox].switch:disabled:not(:checked):after {
                opacity: 0.6;
            }
        }

        .checkbox-wrapper-14 * {
            box-sizing: inherit;
        }
        .checkbox-wrapper-14 *:before,
        .checkbox-wrapper-14 *:after {
            box-sizing: inherit;
        }
    </style>


@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rowChecks = document.querySelectorAll('.row-check');

            rowChecks.forEach(rowCheck => {
                rowCheck.addEventListener('change', () => {
                    const row = rowCheck.closest('tr');
                    const checkboxesInRow = row.querySelectorAll('input[type="checkbox"]:not(.row-check)');

                    checkboxesInRow.forEach(checkbox => {
                        checkbox.checked = rowCheck.checked;
                    });
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const superAdminCheckbox = document.getElementById('s1-14');
            const permissionCheckboxes = document.querySelectorAll('.inp-cbx');
            const rowChecks = document.querySelectorAll('.row-check');

            superAdminCheckbox.addEventListener('change', () => {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = superAdminCheckbox.checked;
                });
                rowChecks.forEach(rowCheck => {
                    rowCheck.checked = superAdminCheckbox.checked;
                });
            });

            rowChecks.forEach(rowCheck => {
                rowCheck.addEventListener('change', () => {
                    const row = rowCheck.closest('tr');
                    const checkboxesInRow = row.querySelectorAll('input[type="checkbox"]:not(.row-check)');

                    checkboxesInRow.forEach(checkbox => {
                        checkbox.checked = rowCheck.checked;
                    });
                });
            });
        });
    </script>
@endsection
@section('header_title', 'Edit Role')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h2 class="mb-0">
                    <i class="fas fa-user-shield me-2"></i>Edit Role
                </h2>
                <a class="btn btn-light btn-sm" href="{{ route('roles.index') }}">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> something went wrong.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group mb-4">
                            <strong>Role name:</strong>
                            <input type="text" name="name" class="form-control" placeholder="Enter the role name" value="{{ $role->name }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-4">
                                <strong class="text-dark permission">Permissions:</strong>
                                <div class="form-check">
                                    @foreach($permissions as $value)
                                        <div class="form-check form-check-inline">
                                            <div class="checkbox-wrapper-46">
                                                <input type="checkbox" name="permission[]" class="inp-cbx" value="{{ $value->id }}" id="permission-{{ $value->id }}"
                                                       @if(in_array($value->id, $rolePermissions)) checked @endif>
                                                <label class="cbx" for="permission-{{ $value->id }}">
                                                <span>
                                                    <svg width="12px" height="10px" viewBox="0 0 12 10">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                    <span>{{ $value->name }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="text-dark mb-2"><strong>SuperAdmin:</strong></label><br>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <div class="checkbox-wrapper-14">
                                        <input id="s1-14" type="checkbox" name="is_admin" class="switch"
                                               @if(old('is_admin', $role->is_admin)) checked @endif>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="mb-4">
                        <label class="form-label"><strong>Setting access privileges:</strong></label>
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">№</th>
                                <th>Module name</th>
                                <th style="width: 60px; text-align: center;">All</th>
                                <th style="width: 100px; text-align: center;">View</th>
                                <th style="width: 100px; text-align: center;">Create</th>
                                <th style="width: 100px; text-align: center;">Read</th>
                                <th style="width: 100px; text-align: center;">Update</th>
                                <th style="width: 100px; text-align: center;">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($allmodules as $index => $module)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $module->module_title }}</td>
                                    <td class="text-center">
                                        <div class="checkbox-wrapper-19">
                                            <input type="checkbox" class="row-check" id="cbtest-{{ $module->module_id }}"
                                                {{ isset($modules[$module->module_id]) &&
                                                $modules[$module->module_id]->is_visible &&
                                                $modules[$module->module_id]->is_create &&
                                                $modules[$module->module_id]->is_read &&
                                                $modules[$module->module_id]->is_edit &&
                                                $modules[$module->module_id]->is_delete ? 'checked' : '' }} />
                                            <label for="cbtest-{{ $module->module_id }}" class="check-box"></label>
                                        </div>
                                    </td>

                                    <!-- Просмотр -->
                                    <td class="text-center">
                                        <div class="checkbox-wrapper-19">
                                            <input type="checkbox" name="permissions[{{ $module->module_id }}][view]"
                                                   value="1" id="view-{{ $module->module_id }}" class="inp-cbx"
                                                {{ isset($modules[$module->module_id]) && $modules[$module->module_id]->is_visible ? 'checked' : '' }}>
                                            <label for="view-{{ $module->module_id }}" class="check-box"></label>
                                        </div>
                                    </td>

                                    <!-- Создание -->
                                    <td class="text-center">
                                        <div class="checkbox-wrapper-19">
                                            <input type="checkbox" name="permissions[{{ $module->module_id }}][create]"
                                                   value="1" id="create-{{ $module->module_id }}" class="inp-cbx"
                                                {{ isset($modules[$module->module_id]) && $modules[$module->module_id]->is_create ? 'checked' : '' }}>
                                            <label for="create-{{ $module->module_id }}" class="check-box"></label>
                                        </div>
                                    </td>

                                    <!-- Чтение -->
                                    <td class="text-center">
                                        <div class="checkbox-wrapper-19">
                                            <input type="checkbox" name="permissions[{{ $module->module_id }}][read]"
                                                   value="1" id="read-{{ $module->module_id }}" class="inp-cbx"
                                                {{ isset($modules[$module->module_id]) && $modules[$module->module_id]->is_read ? 'checked' : '' }}>
                                            <label for="read-{{ $module->module_id }}" class="check-box"></label>
                                        </div>
                                    </td>

                                    <!-- Обновление -->
                                    <td class="text-center">
                                        <div class="checkbox-wrapper-19">
                                            <input type="checkbox" name="permissions[{{ $module->module_id }}][edit]"
                                                   value="1" id="edit-{{ $module->module_id }}" class="inp-cbx"
                                                {{ isset($modules[$module->module_id]) && $modules[$module->module_id]->is_edit ? 'checked' : '' }}>
                                            <label for="edit-{{ $module->module_id }}" class="check-box"></label>
                                        </div>
                                    </td>

                                    <!-- Удаление -->
                                    <td class="text-center">
                                        <div class="checkbox-wrapper-19">
                                            <input type="checkbox" name="permissions[{{ $module->module_id }}][delete]"
                                                   value="1" id="delete-{{ $module->module_id }}" class="inp-cbx"
                                                {{ isset($modules[$module->module_id]) && $modules[$module->module_id]->is_delete ? 'checked' : '' }}>
                                            <label for="delete-{{ $module->module_id }}" class="check-box"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                </form>
            </div>

        </div>
    </div>

@endsection
