@extends('layouts.app')
@section('header_title', 'Меню')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            background-color: #ffffff;
            border-radius: 5px;
            cursor: move;
            border: 2px solid #ced4da;
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
            border-radius: 5px;
            border: 2px solid #ced4da;
        }

        .menu-structure li:hover {
            background-color: #e2e6ea;
        }

    </style>
    <style>

        .menu-level {
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .child-menu-level {
            padding: 10px;
            margin-left: 20px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .subchild-menu-level {
            padding: 10px;
            margin-left: 40px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .menu-item {
            width: 100%;
        }

        .child-icon{
            margin-top: 5px;
            margin-right: 10px !important;


        }

        .menu-item i {
            margin-right: 8px;
        }

        .menu-item span {
            flex-grow: 1;
        }

        .menu-item button {
            margin-left: auto;
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
    <style>

        /* Ряд с меню */
        .menu-row {
            display: flex;
            flex-wrap: wrap;
        }

        /* Колонки */
        .menu-col {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Заголовки */
        h2 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        /* Структура меню */
        .menu-structure {
            list-style: none;
            padding: 0;
        }

        .menu-level, .child-menu-level, .subchild-menu-level {
            padding: 10px;
            background: #e9ecef;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 500;
        }

        /* Иконки */
        .child-icon {
            color: #007bff;
        }

        /* Subchild Menu */
        .subchild-menu-level {
            background: #d6d8db;
            padding-left: 20px;
            border-left: 3px solid #007bff;
        }

        .subchild-icon  {
            color: #28a745;
        }

        /* Кнопки */
        .btn-danger-dark {
            background-color: #dc3545;
            border: none;
            color: white;
        }

        .btn-danger-dark:hover {
            background-color: #c82333;
        }

        /* Форма создания меню */
        #createMenuForm {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            .menu-row {
                flex-direction: column;
            }
        }


    </style>

    <style>

        /* Кастомные стили для радио-кнопок */
        .custom-radio .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #6c757d; /* Цвет границы */
            margin-right: 8px;
            cursor: pointer;
            position: relative;
            appearance: none; /* Убираем стандартный стиль */
            border-radius: 50%; /* Делаем круглыми */
            transition: all 0.3s ease;
        }

        /* Стиль для активной радио-кнопки */
        .custom-radio .form-check-input:checked {
            border-color: #0d6efd; /* Цвет границы при выборе */
            background-color: #0d6efd; /* Цвет фона при выборе */
        }

        /* Анимация для активной радио-кнопки */
        .custom-radio .form-check-input:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: white; /* Цвет внутреннего круга */
            border-radius: 50%;
        }

        /* Стиль для текста */
        .custom-radio .form-check-label {
            font-size: 16px;
            color: #495057; /* Цвет текста */
            cursor: pointer;
        }

        /* Эффект при наведении */
        .custom-radio:hover .form-check-input {
            border-color: #0d6efd; /* Цвет границы при наведении */
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
                    const menuLang = JSON.parse(menu.menu_lang) || '';
                    const menuUZ = menuLang.uz || '';
                    const menuRU = menuLang.ru || '';
                    const menuEN = menuLang.en || '';
                    const menuType = menu.menu_type;
                    const menuUrl = menu.url;
                    const menuModule = menu.module;
                    const menuIcons = menu.menu_icons;
                    const isActive = menu.active;
                    const entryBy = menu.entry_by;

                    // Генерация опций для модуля
                    const moduleOptions = modules.map(module => {
                        const moduleName = module.module_title;
                        const moduleNameValue = module.module_name; // Значение module_name
                        const selected = moduleName === menuModule ? 'selected' : '';
                        return `<option value="${moduleName}" data-module-name="/${moduleNameValue}" ${selected}>${moduleName}</option>`;
                    }).join('');

                    const activeStatusHtml = `
                <fieldset class="row mt-4">
                    <div class="col-md-3"><label class="form-label">Cтатус</label></div>
                    <div class="col-md-7">
                        <div class="form-check form-check-inline custom-radio">
                            <input class="form-check-input" type="radio" name="active" id="active" value="1" ${isActive == '1' ? 'checked' : ''}>
                            <label class="form-check-label" for="active">Активный</label>
                        </div>
                        <div class="form-check form-check-inline custom-radio">
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
                        useBootstrap: false,
                        content: `
                    <form id="editMenuForm">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>Название меню</label>
                            </div>
                            <div class="col-md-7">
                                <ul class="nav nav-tabs" id="editMenuLangTabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="edit-ru-tab" data-bs-toggle="tab" href="#edit-ru">РУ</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="edit-uz-tab" data-bs-toggle="tab" href="#edit-uz">UZ</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="edit-en-tab" data-bs-toggle="tab" href="#edit-en">ENG</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-2">
                                    <div class="tab-pane fade show active" id="edit-ru">
                                        <input type="text" class="form-control" id="menuNameRU" name="menu_name_ru" value="${menuRU || ''}" required placeholder="Название на русском">
                                    </div>
                                    <div class="tab-pane fade" id="edit-uz">
                                        <input type="text" class="form-control" id="menuNameUZ" name="menu_name_uz" value="${menuUZ || ''}" required placeholder="Название на узбекском">
                                    </div>
                                    <div class="tab-pane fade" id="edit-en">
                                        <input type="text" class="form-control" id="menuNameEN" name="menu_name_en" value="${menuEN || ''}" required placeholder="Название на английском">
                                    </div>
                                </div>
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

                        <div class="form-group row mt-3" id="url-container" style="display: none;">
                            <div class="col-md-3">
                                <label for="menuUrl">URL</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="menuUrl" name="url" value="${menuUrl || ''}" readonly>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-3">
                                <label for="icons">Иконка меню</label>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="icons" name="icons" value="${menuIcons || ''}">
                                    <span class="input-group-text">
                                        <i id="icon-preview" class="${menuIcons || ''}"></i>
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <p>Пример:
                                        <span class="badge bg-info">icon-windows8</span>,
                                        <span class="badge bg-info">fa fa-cloud-upload</span>
                                    </p>
                                    <p>Применение:
                                        <a href="{{route('menu.getIcons')}}" target="_blank" class="text-decoration-none">Font Awesome</a>
                                        class name
                                    </p>
                                </div>
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
                                    const menuNameRU = this.$content.find('#menuNameRU').val();
                                    const menuNameUZ = this.$content.find('#menuNameUZ').val();
                                    const menuNameEN = this.$content.find('#menuNameEN').val();
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
                                            menu_name_ru: menuNameRU,
                                            menu_name_uz: menuNameUZ,
                                            menu_name_en: menuNameEN,
                                            menu_type: menuType,
                                            url: menuUrl,
                                            module: menuModule,
                                            menu_icons: menuIcons,
                                            active: active,
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
                            document.getElementById('icons').addEventListener('input', function() {
                                var iconPreview = document.getElementById('icon-preview');
                                iconPreview.className = this.value;
                            });

                            const moduleContainer = this.$content.find('#module-container');
                            const urlContainer = this.$content.find('#url-container');
                            const menuTypeSelect = this.$content.find('#menuType');
                            const moduleSelect = this.$content.find('#module');
                            const urlInput = this.$content.find('#menuUrl');

                            function toggleFields() {
                                const selectedType = menuTypeSelect.val();
                                if (selectedType === 'internal') {
                                    moduleContainer.show();
                                    moduleSelect.prop('required', true);

                                    if (moduleSelect.val() !== "") {
                                        urlContainer.show();
                                        urlInput.prop('required', true);
                                    } else {
                                        urlContainer.hide();
                                        urlInput.prop('required', false);
                                    }
                                } else {
                                    moduleContainer.hide();
                                    moduleSelect.prop('required', false);
                                    urlContainer.hide();
                                    urlInput.prop('required', false);
                                }
                            }

                            moduleSelect.on('change', function () {
                                const selectedOption = moduleSelect.find('option:selected');
                                const moduleName = selectedOption.data('module-name');

                                if (moduleName) {
                                    urlInput.val(moduleName);
                                    urlContainer.show();
                                    urlInput.prop('required', true);
                                } else {
                                    urlInput.val('');
                                    urlContainer.hide();
                                    urlInput.prop('required', false);
                                }
                            });

                            toggleFields();
                            menuTypeSelect.on('change', toggleFields);

                            if (moduleSelect.val() !== "") {
                                const selectedOption = moduleSelect.find('option:selected');
                                const moduleName = selectedOption.data('module-name');
                                urlInput.val(moduleName);
                                urlContainer.show();
                            }
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.getElementById('menu_icons').addEventListener('input', function() {
            var iconPreview = document.getElementById('menu-icon-preview');
            iconPreview.className = this.value;
        });

        document.addEventListener('DOMContentLoaded', function () {
            const moduleContainer = document.getElementById('module-container');
            const urlContainer = document.getElementById('url-container');
            const moduleSelect = document.getElementById('module');
            const urlInput = document.getElementById('url');
            const menuTypeRadios = document.querySelectorAll('.menu-type');

            function updateFields() {
                const selectedType = document.querySelector('.menu-type:checked').value;
                if (selectedType === 'internal') {
                    moduleContainer.style.display = 'block';
                    moduleSelect.setAttribute('required', 'required');

                    if (moduleSelect.value !== "") {
                        urlContainer.style.display = 'block';
                        urlInput.setAttribute('required', 'required');
                    } else {
                        urlContainer.style.display = 'none';
                        urlInput.removeAttribute('required');
                    }
                } else {
                    moduleContainer.style.display = 'none';
                    moduleSelect.removeAttribute('required');
                    urlContainer.style.display = 'none';
                    urlInput.removeAttribute('required');
                }
            }

            moduleSelect.addEventListener('change', function () {
                const selectedOption = moduleSelect.options[moduleSelect.selectedIndex];

                if (selectedOption.value !== "") {
                    const moduleName = selectedOption.getAttribute('data-module-name');
                    urlInput.value = moduleName;

                    urlContainer.style.display = 'block';
                    urlInput.setAttribute('required', 'required');
                } else {
                    urlContainer.style.display = 'none';
                    urlInput.removeAttribute('required');
                    urlInput.value = "";
                }
            });

            menuTypeRadios.forEach(radio => {
                radio.addEventListener('change', updateFields);
            });

            updateFields();
        });

    </script>

    <script>
        $('#createMenuForm').submit(function(e) {
            e.preventDefault();

            let menuNameRu = $('input[name="menu_name_ru"]').val();
            let menuNameUz = $('input[name="menu_name_uz"]').val();
            let menuNameEn = $('input[name="menu_name_en"]').val();
            console.log(menuNameRu, menuNameUz, menuNameEn)

            if (!menuNameRu || !menuNameUz || !menuNameEn) {
                Swal.fire({
                    title: 'Ошибка!',
                    text: 'Заполните все поле названия меню!',
                    icon: 'warning',
                    showConfirmButton: true,
                });
                return;
            }

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

    <script>
        $(document).on('click', '.delete-menu', function() {
            var menuId = $(this).data('id');

            Swal.fire({
                title: 'Вы уверены?',
                text: "Вы не сможете отменить это действие!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Да, удалить!',
                cancelButtonText: 'Отмена'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route('menu.deleteMenu')}}',
                        type: 'DELETE',
                        data: {
                            id: menuId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Удалено!',
                                    text: 'Элемент успешно удален.',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 2500
                                }).then(() => {
                                    $('li[data-id="' + menuId + '"]').remove();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Ошибка!',
                                    text: 'Не удалось удалить элемент.',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Ошибка!',
                                text: 'Произошла ошибка при удалении элемента.',
                                icon: 'error',
                                confirmButtonText: 'ОК'
                            });
                        }
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
                            @php
                                $menuLang = is_string($menu->menu_lang) ? json_decode($menu->menu_lang, true) : $menu->menu_lang;
                                $menuNameRu = $menuLang['ru'] ?? '';
                            @endphp
                            <li data-id="{{ $menu->menu_id }}" data-type="{{ $menu->menu_type }}" class="menu-level">
                                <div class="menu-item d-flex align-items-center justify-content-start @if($menu->active) inactive @endif">
                                    <i class="{{ $menu->menu_icons }} @if(!$menu->active) text-danger @endif"></i>
                                    <span>{{ $menuNameRu }}</span>
                                    <button class="btn btn-sm btn-warning edit-menu" data-id="{{ $menu->menu_id }}" data-name="{{ $menuNameRu }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-menu" data-id="{{ $menu->menu_id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                @if (count($menu->children))
                                    <ul>
                                        @foreach ($menu->children as $child)
                                            @php
                                                $childLang = is_string($child->menu_lang) ? json_decode($child->menu_lang, true) : $child->menu_lang;
                                                $childNameRu = $childLang['ru'] ?? '';
                                            @endphp
                                            <li data-id="{{ $child->menu_id }}" data-type="{{ $child->menu_type }}" class="child-menu-level">
                                                <div class="menu-item d-flex justify-content-between align-items-start @if($child->active) inactive @endif">
                                                    <i class="{{ $child->menu_icons }} text-info @if(!$child->active) text-danger @endif"></i>
                                                    <span>{{ $childNameRu }}</span>
                                                    <div>
                                                        <button class="btn btn-sm btn-warning edit-menu" data-id="{{ $child->menu_id }}" data-name="{{ $childNameRu }}">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-menu" data-id="{{ $child->menu_id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @if (count($child->children))
                                                    <ul>
                                                        @foreach ($child->children as $subChild)
                                                            @php
                                                                $subChildLang = is_string($subChild->menu_lang) ? json_decode($subChild->menu_lang, true) : $subChild->menu_lang;
                                                                $subChildNameRu = $subChildLang['ru'] ?? '';
                                                            @endphp
                                                            <li data-id="{{ $subChild->menu_id }}" data-type="{{ $subChild->menu_type }}" class="subchild-menu-level">
                                                                <div class="menu-item d-flex justify-content-between align-items-start @if($subChild->active) inactive @endif">
                                                                    <i class="{{ $subChild->menu_icons }} text-success @if(!$subChild->active) text-danger @endif"></i>
                                                                    <span>{{ $subChildNameRu }}</span>
                                                                    <div>
                                                                        <button class="btn btn-sm btn-warning edit-menu" data-id="{{ $subChild->menu_id }}" data-name="{{ $subChildNameRu }}">
                                                                            <i class="fa fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-danger delete-menu" data-id="{{ $subChild->menu_id }}">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
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
                <form id="createMenuForm">
                    <h2>Создать меню</h2>

                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Название/Заголовок</label>
                        <ul class="nav nav-tabs" id="menuLangTabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="ru-tab" data-bs-toggle="tab" href="#ru">РУ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="uz-tab" data-bs-toggle="tab" href="#uz">UZ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="en-tab" data-bs-toggle="tab" href="#en">ENG</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-2">
                            <div class="tab-pane fade show active" id="ru">
                                <input type="text" class="form-control" name="menu_name_ru" placeholder="Название на русском">
                            </div>
                            <div class="tab-pane fade" id="uz">
                                <input type="text" class="form-control" name="menu_name_uz" placeholder="Название на узбекском">
                            </div>
                            <div class="tab-pane fade" id="en">
                                <input type="text" class="form-control" name="menu_name_en" placeholder="Название на английском">
                            </div>
                        </div>
                    </div>
                    <fieldset class="mb-3">
                        <label class="form-label">Тип меню</label><br>
                        <div class="form-check form-check-inline custom-radio">
                            <input class="form-check-input menu-type" type="radio" name="menu_type" id="internal" value="internal" checked>
                            <label class="form-check-label" for="internal">Внутренний</label>
                        </div>
                        <div class="form-check form-check-inline custom-radio">
                            <input class="form-check-input menu-type" type="radio" name="menu_type" id="external" value="external">
                            <label class="form-check-label" for="external">Внешний</label>
                        </div>
                    </fieldset>
                    <div class="mb-3" id="module-container">
                        <label for="module" class="form-label">Модуль</label>
                        <select class="form-control" id="module" name="module" required>
                            <option value="" selected>-- НЕ ВЫБРАНО --</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->module_title }}" data-module-name="/{{ $module->module_name }}">
                                    {{ $module->module_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="url-container">
                        <label for="url" class="form-label">Link</label>
                        <input type="text" class="form-control" id="url" name="url" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="menu_icons" class="form-label">Иконка меню</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="menu_icons" name="menu_icons">
                            <span class="input-group-text">
                                <i id="menu-icon-preview"></i>
                            </span>
                        </div>
                        <div class="mt-2">
                            <p>Пример:
                                <span class="badge bg-info">icon-windows8</span>,
                                <span class="badge bg-info">fa fa-cloud-upload</span>
                            </p>
                            <p>Применение:
                                <a href="{{route('menu.getIcons')}}" target="_blank" class="text-decoration-none">Font Awesome</a>
                                class name
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Статус</label>
                        <div class="col-md-7">
                            <div class="form-check form-check-inline custom-radio">
                                <input class="form-check-input" type="radio" name="active" id="active" value="1" checked>
                                <label class="form-check-label" for="active">Активный</label>
                            </div>
                            <div class="form-check form-check-inline custom-radio">
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
