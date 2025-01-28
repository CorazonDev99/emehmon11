@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .nav-tabs .nav-link {
            background-color: #f8f9fa;
            color: #007bff;
        }

        .menu-structure {
            list-style-type: none;
            padding-left: 0;
        }

        .menu-structure li {
            margin: 5px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            cursor: move;
            border: 1px solid #ced4da;
        }

        .menu-structure li ul {
            list-style-type: none;
            padding-left: 20px;
            margin-top: 10px;
        }

        .menu-structure li ul li {
            margin-bottom: 5px;
            padding: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }

        .menu-structure li:hover {
            background-color: #e2e6ea;
        }

    </style>


    <style>

        .menu-level {
            background-color: #39a4fd !important;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .child-menu-level {
            background-color: #50E3C2 !important; /* Мятный для подменю */
            padding: 10px;
            margin-left: 20px; /* Смещение для визуального разделения */
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .subchild-menu-level {
            background-color: #F5A623 !important; /* Яркий оранжевый для под-подменю */
            padding: 10px;
            margin-left: 40px; /* Ещё большее смещение */
            margin-bottom: 5px;
            border-radius: 5px;
        }

        /* Общие стили для всех уровней */
        .menu-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu-col{
            padding-right: 50px !important;
            margin-bottom: 20px !important;
        }
        .menu-row{
            margin-top:15px !important;
        }
        .container{
            max-width: 95% !important;
        }

        #editMenuForm{
            margin-left: 70px !important;
            margin-top: 20px !important;
            margin-right: 20px !important;
            margin-bottom: 40px !important;
        }

    </style>


@endsection
@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#menu_icons').select2({
                templateResult: formatIcon,
                templateSelection: formatIcon
            });

            function formatIcon(icon) {
                if (!icon.id) {
                    return icon.text;
                }
                var $icon = $(
                    '<span><i class="' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>'
                );
                return $icon;
            }
        });
    </script>

    <script>
        document.getElementById('topMenuTab').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('topMenuSection').style.display = 'block';
            document.getElementById('topMenuTab').classList.add('active');
        });
    </script>

    <script>
        let isSaveClicked = false;

        function buildMenuHierarchy(container, parentId = 0) {
            let items = [];
            container.querySelectorAll(':scope > li').forEach((li, index) => {
                let item = {
                    id: li.dataset.id,
                    parent_id: parentId,
                    ordering: index + 1,
                    menu_type: parentId === 0 ? 'external' : 'internal'
                };

                let subMenu = li.querySelector('ul');
                if (subMenu && subMenu.children.length > 0) {
                    item.children = buildMenuHierarchy(subMenu, item.id);
                }

                items.push(item);
            });
            return items;
        }

        function applyNestedSortable(container) {
            new Sortable(container, {
                group: {
                    name: 'nested',
                    pull: true,
                    put: true
                },
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                ghostClass: 'sortable-ghost',
                onEnd(evt) {
                    let item = evt.item;
                    let parent = item.parentElement;

                    if (!item.querySelector('ul')) {
                        let newSubMenu = document.createElement('ul');
                        newSubMenu.classList.add('menu-structure');
                        item.appendChild(newSubMenu);
                        applyNestedSortable(newSubMenu);
                    }

                }
            });

            container.querySelectorAll('ul').forEach(function(subContainer) {
                if (!subContainer.classList.contains('sortable-applied')) {
                    subContainer.classList.add('sortable-applied');
                    applyNestedSortable(subContainer);
                }
            });
        }

        function saveMenuOrder(containerId) {
            const container = document.getElementById(containerId);
            if (!container) {
                console.error(`Container with ID "${containerId}" not found.`);
                return;
            }

            const menu = buildMenuHierarchy(container);

            return axios.post("{{ route('menu.updateOrder') }}", { menu: menu });
        }

        applyNestedSortable(document.getElementById('topMenuStructure'));

        document.getElementById('saveMenuOrder').addEventListener('click', function () {
            Promise.all([
                saveMenuOrder('topMenuStructure'),
            ])
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Порядок меню сохранён успешно!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch((error) => {
                    console.error('Ошибка при сохранении порядка меню:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка при сохранении порядка меню!',
                        text: error.response ? error.response.data : 'Неизвестная ошибка',
                        showConfirmButton: true
                    });
                });
        });
    </script>

    <script>
        var menus = @json($menus);
        const modules = @json($modules);
        const icons = @json($icons);
    </script>

    <script>
        $(document).ready(function () {
            $(document).on('click', '.edit-menu', function () {
                const menuId = $(this).data('id');

                const menu = menus.find(menu => menu.menu_id === menuId);

                if (menu) {
                    const menuName = menu.menu_name;
                    const menuType = menu.menu_type;
                    const menuUrl = menu.url;
                    const menuModule = menu.module;
                    const menuIcons = menu.menu_icons;
                    const isActive = menu.active;
                    const entryBy = menu.entry_by;
                    const moduleOptions = modules.map(module => {
                        const moduleName = module.module_title;
                        const selected = moduleName === menuModule ? 'selected' : '';
                        return `<option value="${moduleName}" ${selected}>${moduleName}</option>`;
                    }).join('');

                    const activeStatusHtml = `
                    <fieldset class="row mt-4">
                        <div class="col-md-3"><label class="form-label">Cтатус</label></div>
                        <div class="col-md-7">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="active" id="active" value="1" ${isActive == '1' ? 'checked' : ''}>
                            <label class="form-check-label" for="active">Активный</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="active" id="inactive" value="0" ${isActive == '0' ? 'checked' : ''}>
                            <label class="form-check-label" for="inactive">Неактивный</label>
                        </div>
                        </div>
                    </fieldset>
                `;

                    $.confirm({
                        title: 'Редактировать меню',
                        type: 'blue',
                        boxWidth: '800px',
                        useBootstrap:false,
                        content: `
                            <form id="editMenuForm">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label for="menuName">Название меню</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" id="menuName" name="menu_name" value="${menuName || ''}" required>
                                    </div>
                                </div>

                                <div class="form-group row mt-3">
                                    <div class="col-md-3">
                                        <label for="menuType">Тип меню</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="form-control" id="menuType" name="menu_type">
                                            <option value="internal" ${menuType === 'internal' ? 'selected' : ''}>Внутренний</option>
                                            <option value="external" ${menuType === 'external' ? 'selected' : ''}>Внешний</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mt-3" id="module-container">
                                    <div class="col-md-3">
                                        <label for="menuRoleId">Модуль</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="form-control" id="module" name="module">
                                            <option value="">-- НЕ ВЫБРАНО --</option>
                                            ${moduleOptions}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mt-3">
                                    <div class="col-md-3">
                                        <label for="menuUrl">URL</label>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" id="menuUrl" name="url" value="${menuUrl || ''}">
                                    </div>
                                </div>

                                 <div class="form-group row mt-3">
                                    <div class="col-md-3">
                                        <label for="icons">Иконка меню</label>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="form-control" id="icons" name="icons">
                                            <option value="" selected>-- НЕ ВЫБРАНО --</option>
                                            ${icons.map(icon =>

                                                `<option value="${icon.icon_name}" data-icon="${icon.icon_name}" ${`${icon.icon_name}` === menuIcons ? 'selected' : ''}>
                                                    ${menuIcons}
                                                </option>
                                            `).join('')}
                                        </select>
                                    </div>
                                </div>

                                ${activeStatusHtml}
                            </form>
                        `,


                        buttons: {

                            save: {
                                text: 'Сохранить',
                                btnClass: 'btn-success',
                                action: function () {
                                    const menuName = this.$content.find('#menuName').val();
                                    const menuType = this.$content.find('#menuType').val();
                                    const menuUrl = this.$content.find('#menuUrl').val();
                                    const menuModule = this.$content.find('#module').val();
                                    const menuIcons = this.$content.find('#icons').val();
                                    const active = this.$content.find('input[name="active"]:checked').val();

                                    $.ajax({
                                        url: '{{ route("menu.updateMenu") }}',
                                        type: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        data: {
                                            menu_id: menu.menu_id,
                                            entry_by: entryBy,
                                            menu_name: menuName,
                                            menu_type: menuType,
                                            url: menuUrl,
                                            module: menuModule,
                                            menu_icons: menuIcons,
                                            active: active
                                        },
                                        success: function (response) {
                                            Swal.fire({
                                                title: 'Успешно!',
                                                text: response.message,
                                                icon: 'success',
                                                timer: 1500,
                                                showConfirmButton: false,
                                            }).then(() => {
                                                location.reload();
                                            });
                                        },
                                        error: function () {
                                            Swal.fire({
                                                title: 'Ошибка!',
                                                text: 'Не удалось обновить меню.',
                                                icon: 'error',
                                                timer: 1500,
                                                showConfirmButton: false,
                                            });
                                        }
                                    });

                                }
                            },
                            cancel: {
                                text: 'Отмена',
                                btnClass: 'btn-danger',
                                action: function () {}
                            },
                        },

                        onContentReady: function () {
                            $('#icons').selectize({
                                render: {
                                    option: function (item) {
                                        return `
                                            <div>
                                                <i class="${item.dataIcon}"></i>
                                                <span>${item.text}</span>
                                            </div>
                                            `;
                                                                        },
                                                                        item: function (item) {
                                                                            return `
                                            <div>
                                                <i class="${item.dataIcon}"></i>
                                                <span>${item.text}</span>
                                            </div>
                                            `;
                                    }
                                },
                                searchField: 'text',
                                labelField: 'text',
                                valueField: 'value',
                                options: icons.map(icon => ({
                                    value: `${icon.icon_name}`,
                                    text: icon.icon_name,
                                    dataIcon: `${icon.icon_name}`
                                }))
                            });



                            const moduleContainer = this.$content.find('#module-container');
                            const menuTypeSelect = this.$content.find('#menuType');

                            function toggleModuleField() {
                                const selectedType = menuTypeSelect.val();
                                if (selectedType === 'internal') {
                                    moduleContainer.show();
                                    moduleContainer.find('#module').prop('required', true);
                                } else {
                                    moduleContainer.hide();
                                    moduleContainer.find('#module').prop('required', false);
                                }
                            }

                            toggleModuleField();
                            menuTypeSelect.on('change', toggleModuleField);
                        }
                    });
                }
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const moduleContainer = document.getElementById('module-container');
            const menuTypeRadios = document.querySelectorAll('.menu-type');

            function updateModuleField() {
                const selectedType = document.querySelector('.menu-type:checked').value;
                if (selectedType === 'internal') {
                    moduleContainer.style.display = 'block';
                    document.getElementById('module').setAttribute('required', 'required');
                } else {
                    moduleContainer.style.display = 'none';
                    document.getElementById('module').removeAttribute('required');
                }
            }

            menuTypeRadios.forEach(radio => {
                radio.addEventListener('change', updateModuleField);
            });

            updateModuleField();
        });

    </script>

    <script>
        $('#createMenuForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("menu.store") }}',
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Успешно!',
                        text: 'Меню успешно создано',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        title: 'Ошибка!',
                        text: 'Не удалось создать меню!',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                }
            });
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row menu-row">
            <div class="col-md-6 menu-col">
                <h2 class="mb-3">Структура меню</h2>
                <div id="topMenuSection" class="col-md-12">
                    <ul id="topMenuStructure" class="menu-structure">
                        @foreach ($topMenus as $menu)
                            <li data-id="{{ $menu->menu_id }}" data-type="{{ $menu->menu_type }}" class="menu-level">
                                <div class="menu-item d-flex justify-content-between align-items-center">
                                    <i class="{{ $menu->menu_icons }}"></i>
                                    <span>{{ $menu->menu_name }}</span>
                                    <button class="btn btn-sm btn-danger-dark edit-menu" data-id="{{ $menu->menu_id }}" data-name="{{ $menu->menu_name }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                                @if (count($menu->children))
                                    <ul>
                                        @foreach ($menu->children as $child)
                                            <li data-id="{{ $child->menu_id }}" data-type="{{ $child->menu_type }}" class="child-menu-level">
                                                <div class="menu-item d-flex justify-content-between align-items-center">
                                                    <i class="{{ $child->menu_icons }}"></i>
                                                    <span>{{ $child->menu_name }}</span>
                                                    <button class="btn btn-sm btn-danger-dark edit-menu" data-id="{{ $child->menu_id }}" data-name="{{ $child->menu_name }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </div>
                                                @if (count($child->children))
                                                    <ul>
                                                        @foreach ($child->children as $subChild)
                                                            <li data-id="{{ $subChild->menu_id }}" data-type="{{ $subChild->menu_type }}" class="subchild-menu-level">
                                                                <div class="menu-item d-flex justify-content-between align-items-center">
                                                                    <i class="{{ $subChild->menu_icons }}"></i>
                                                                    <span>{{ $subChild->menu_name }}</span>
                                                                    <button class="btn btn-sm btn-danger-dark edit-menu" data-id="{{ $subChild->menu_id }}" data-name="{{ $subChild->menu_name }}">
                                                                        <i class="fa fa-edit"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>

                        @endforeach
                    </ul>
                </div>
                <button id="saveMenuOrder" class="btn btn-success mt-3">Сохранить порядок</button>
            </div>

            <div class="col-md-6">
                <h2>Создать меню</h2>
                <form id="createMenuForm">
                    @csrf
                    <div class="mb-3">
                        <label for="menu_name" class="form-label">Название/Заголовок</label>
                        <input type="text" class="form-control" id="menu_name" name="menu_name" required>
                    </div>
                    <fieldset class="mb-3">
                        <label class="form-label">Тип меню</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input menu-type" type="radio" name="menu_type" id="internal" value="internal" checked>
                            <label class="form-check-label" for="internal">Внутренний</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input menu-type" type="radio" name="menu_type" id="external" value="external">
                            <label class="form-check-label" for="external">Внешний</label>
                        </div>
                    </fieldset>
                    <div class="mb-3" id="module-container">
                        <label for="module" class="form-label">Модуль</label>
                        <select class="form-control" id="module" name="module" required>
                            <option value="" selected>-- НЕ ВЫБРАНО --</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->module_title }}">{{ $module->module_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label">Link</label>
                        <input type="text" class="form-control" id="url" name="url">
                    </div>
                    <div class="mb-3">
                        <label for="menu_icons" class="form-label mb-1">Иконка</label>
                        <select class="form-control" id="menu_icons" name="menu_icons">
                            <option value="" selected>-- НЕ ВЫБРАНО --</option>
                            @foreach($icons as $icon)
                                <option value="fa  {{ $icon->icon_name }}" data-icon="fa {{ $icon->icon_name }}">{{ $icon->icon_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Статус</label>
                        <div class="col-md-7">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="active" id="active" value="1">
                                <label class="form-check-label" for="active">Активный</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="active" id="inactive" value="0">
                                <label class="form-check-label" for="inactive">Неактивный</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">Создать</button>
                </form>
            </div>
        </div>
    </div>
@endsection




