<?php
$minAge = (new DateTime())->modify('-16 years')->format('Y-m-d');
$maxAge = (new DateTime())->modify('-120 years')->format('Y-m-d');
?>
<h4>Self Tourist</h4>
<div class="tabs pb-4 container">


    <!-- ++++++++++++++++++++++++++++++++++++++  tab navbars  ++++++++++++++++++++++++++++++++++++++-->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link disabled active" id="initial-info-tab" data-bs-toggle="tab" href="#initial-info"
                role="tab" aria-controls="initial-info" aria-selected="true">Поиск гостя </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link disabled" id="general-info-tab" data-bs-toggle="tab" href="#general-info" role="tab"
                aria-controls="general-info" aria-selected="false">Основная информация </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link disabled" id="additional-info-tab" data-bs-toggle="tab" href="#additional-info"
                role="tab" aria-controls="additional-info" aria-selected="false">Дополнительная информация </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link disabled" id="children-info-tab" data-bs-toggle="tab" href="#children-info"
                role="tab" aria-controls="children-info" aria-selected="false">Информация о детях </a>
        </li>
    </ul>
    <!-- ++++++++++++++++++++++++++++++++++++++ tab navbars end  ++++++++++++++++++++++++++++++++++++++-->



    <div class="tab-content mt-3" id="myTabContent">
        <!-- -============================================initial Info Tab-============================================ -->
        <div class="tab-pane fade show active" id="initial-info" role="tabpanel" aria-labelledby="initial-info-tab">
            <form class="row g-3 needs-validation" novalidate id="initialForm">
                @csrf
                {{-- citizens --}}
                <div class="col-md-4">
                    <label for="id_citizen">ГРАЖДАНСТВО *</label>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <select name="id_citizen" onchange="changeFlag('id_citizen', 'flag_ctz')" class="form-select  "
                        id="id_citizen" required>
                        <option selected disabled value="">---Гражданство---</option>
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->SP_NAME03 }}</option>
                        @endforeach
                    </select>
                    <img width="60px" style="max-height: 30px; display:none; margin-left:8px" id="flag_ctz"
                        alt="Flag">
                </div>

                {{-- citizens end --}}

                {{-- pass type start --}}
                <div class="col-md-4">
                    <label for="id_passporttype">ТИП ДОКУМЕНТА *</label>
                </div>
                <div class="col-md-6">
                    <select name="id_passporttype" class="form-select" id="id_passporttype" required>
                        <option selected disabled value="">--Тип документа--</option>
                        <option value="1">Паспорт</option>
                        <option value="2">Воен.удостоверение</option>
                        <option value="3">Другой документ</option>
                        <option value="4">ID карта</option>
                    </select>
                </div>
                {{-- pass type end --}}


                {{-- date start --}}
                <div class="col-md-4">
                    <label for="datebirth">ДАТА РОЖДЕНИЯ: *</label>
                </div>
                <div class="col-md-6">
                    <div class="input-group" id="validationCustom03">
                        <input autocomplete="off" name="datebirth" required type="date" class="form-control"
                            min="<?php echo $maxAge; ?>" max="<?php echo $minAge; ?>" id="datebirth">
                    </div>
                </div>
                {{-- date end --}}

                {{-- pass ser start --}}
                <div class="col-md-4">
                    <label for="passportNumber">ПАСПОРТ СЕРИЯ|НОМЕР:</label>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3" id="validationCustom04">
                        <input autocomplete="off" pattern="^[A-Za-z0-9]{1,20}$" name="passportNumber" required
                            type="text" class="form-control" id="passportNumber" placeholder="Серия|номер паспорта ">
                    </div>
                </div>
                {{-- pass ser end --}}

                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-primary px-5" id="formCheckButton"
                        onclick="formCheck('#general-info-tab', 'initialForm')">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                            id="loadingSpinner" style="display:none;"></span>
                        Следующий
                    </button>
                </div>
            </form>
        </div>
        <!-- -============================================initial Info Tab end-============================================ -->


        <!--=============================================== general info tab ================================================================================-->
        <div class="tab-pane fade" id="general-info" role="tabpanel" aria-labelledby="general-info-tab">
            <form class="row g-3 needs-validation" novalidate id="generalInfo">
                @csrf
                {{-- citizens --}}
                <div class="col-md-4">
                    <label for="date_of_issue">ПАСПОРТ ДАТА ВЫДАЧИ: *</label>
                </div>
                <div class="col-md-6 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input autocomplete="off" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-50 years')); ?>"
                                name="date_of_issue" required type="date" class="form-control"
                                id="date_of_issue">
                        </div>
                    </div>
                </div>
                {{-- citizens end --}}


                {{-- passport given by --}}
                <div class="col-md-4">
                    <label for="pass_given_by">ПАСПОРТ ВЫДАН:</label>
                </div>
                <div class="col-md-6 align-items-center">
                    <div class="input-group">
                        <input autocomplete="off" name="pass_given_by" required type="text" class="form-control"
                            id="pass_given_by" placeholder="КЕМ ВЫДАН ПАСПОРТ?"
                            pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$">
                    </div>
                </div>
                {{-- pass given by end --}}


                {{-- full name inputs --}}
                <div class="col-md-4">
                    <label for="surname">ФАМИЛИЯ, ИМЯ, ОТЧЕСТВО: *</label>
                </div>
                <div class="col-md-6 align-items-center d-flex">
                    <div class="input-group" id="validationCustom07">
                        <input autocomplete="off" required type="text" class="form-control" id="surname"
                            name="surname" placeholder="ФАМИЛИЯ " pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$">
                    </div>
                    <div class="input-group mx-1" id="validationCustom08">
                        <input autocomplete="off" required type="text" class="form-control" id="firstname"
                            name="firstname" placeholder="ИМЯ" pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$">
                    </div>
                    <div class="input-group  " id="validationCustom09">
                        <input autocomplete="off" required type="text" class="form-control" id="lastname"
                            name="lastname" placeholder="ОТЧЕСТВО " pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$">
                    </div>
                </div>
                {{-- name inputs end --}}


                {{-- born country --}}
                <div class="col-md-4">
                    <label for="id_born">СТРАНА РОЖДЕНИЯ: *</label>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <select name="id_born" onchange="changeFlag('id_born','flag_born')" class="form-select"
                        id="id_born" required>
                        <option selected disabled value="">---НЕ ВЫБРАНО---</option>
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->SP_NAME03 }}</option>
                        @endforeach
                    </select>
                    <img width="60px" style="max-height: 30px; display:none; margin-left:8px" id="flag_born"
                        alt="Flag">
                </div>
                {{-- end --}}

                {{-- visit from country --}}
                <div class="col-md-4">
                    <label for="id_from_c">СТРАНА ОТКУДА ПРИБЫЛ:*</label>
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <select name="id_from_c" onchange="changeFlag('id_from_c', 'flag_from_c')" class="form-select  "
                        id="id_from_c" required>
                        <option selected disabled value="">---НЕ ВЫБРАНО---</option>
                        @foreach ($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->SP_NAME03 }}</option>
                        @endforeach
                    </select>
                    <img width="60px" style="max-height: 30px; display:none; margin-left:8px" id="flag_from_c"
                        alt="Flag">
                </div>
                {{-- end --}}


                {{-- sex type start --}}
                <div class="col-md-4">
                    <label for="sex">ПОЛ *</label>
                </div>
                <div class="col-md-6">
                    <select name="sex" class="form-select" id="sex" required>
                        <option selected disabled value="">--Укажите пол--</option>
                        <option value="1">Мужчина</option>
                        <option value="2">Женщина </option>
                    </select>
                </div>
                {{-- sex type end --}}

                {{-- visit date --}}
                <div class="col-md-4">
                    <label for="visit_time">ДАТА ПРИБЫТИЯ: *</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input autocomplete="off" value="" required type="datetime-local" class="form-control"
                            id="visit_time" name="visit_time">
                    </div>
                </div>
                {{-- date end --}}


                {{-- next button --}}
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-secondary" onclick="prevTab('#initial-info-tab')">Назад</button>
                    <button class="btn btn-primary"
                        onclick="nextTab('#additional-info-tab', 'generalInfo')">Следующий</button>
                </div>
            </form>
        </div>
        <!--============================================= general info tab end ================================================================================-->


        <!-- ==============================================additional Info Tab============================================== -->
        <div class="tab-pane fade" id="additional-info" role="tabpanel" aria-labelledby="additional-info-tab">

            <form class="row g-3 needs-validation" novalidate id="additionalInfo">
                <div class="col-md-3">
                    <label for="calculate_sbor">НАСКОЛЬКО ДНЕЙ ПРИБЫЛ:</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group ">
                        <input autocomplete="off" required type="number" id="s_days" min="1"
                            class="form-control" placeholder="№">
                        <button class="btn btn-primary" type="button" onclick="calculateSbor()"
                            id="calculate_sbor">Посчитать </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="calculate_sbor">СУММА ТУРСБОРА:</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group ">
                        <input name="summa" id="summa_tursbor" type="text" class="form-control disabled"
                            readonly disabled aria-label="Amount" aria-describedby="basic-addon2">
                        <span class="input-group-text" id="basic-addon2">UZS</span>
                    </div>
                </div>

                {{-- visit type --}}
                <div class="col-md-3">
                    <label for="id_visittype">ТИП ВИЗИТА:*</label>
                </div>
                <div class="col-md-3">
                    <select name="id_visittype" class="form-select  " id="id_visittype" required>
                        <option selected disabled value="">---НЕ ВЫБРАНО---</option>
                        @foreach ($visittype as $vt)
                            <option value="{{ $vt->id }}">{{ $vt->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- visit type end --}}

                {{-- visa type --}}
                <div class="col-md-3">
                    <label for="id_visatype">ТИП ВИЗЫ:*</label>
                </div>
                <div class="col-md-3">
                    <select name="id_visatype" class="form-select" id="id_visatype" required>
                        <option selected disabled value="">---НЕ ВЫБРАНО---</option>
                        @foreach ($visatype as $visat)
                            <option value="{{ $visat->sp_id }}">{{ $visat->sp_name00 }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- visa type end --}}

                {{-- visa number --}}
                <div class="col-md-3">
                    <label for="visa_number">№ ВИЗЫ:</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group me-3">
                        <input pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$" autocomplete="off" name="visa_number" required
                            type="text" class="form-control" id="visa_number" placeholder="Номер визы">
                    </div>
                </div>
                {{-- visa number --}}

                {{-- visa issued by --}}
                <div class="col-md-3">
                    <label for="visa_by">КЕМ ВЫДАН ВИЗА:</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$" autocomplete="off" required type="text"
                            class="form-control" id="visa_by" name="visa_by" placeholder="Кем выдан виза ">
                    </div>
                </div>
                {{-- visa issued by end --}}


                {{-- visa date start --}}
                <div class="col-md-3">
                    <label for="visa_start">СРОК ВИЗЫ С:</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input required type="date" class="form-control" max="<?php echo date('Y-m-d'); ?>"
                            min="<?php echo date('Y-m-d', strtotime('-50 years')); ?>" id="visa_start" name="visa_start">
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="visa_end">СРОК ВИЗЫ ПО:</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input max="<?php echo date('Y-m-d', strtotime('+50 years')); ?>" min="<?php echo date('Y-m-d'); ?>" required type="date"
                            class="form-control" id="visa_end" name="visa_end">
                    </div>
                </div>
                {{-- visa date end --}}


                {{-- KPP number --}}
                <div class="col-md-3">
                    <label for="kppNumber">КПП №</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group me-3">
                        <input pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$" autocomplete="off" max="<?php echo date('Y-m-d'); ?>"
                            min="<?php echo date('Y-m-d', strtotime('-50 years')); ?>" required type="text" class="form-control" id="kppNumber"
                            placeholder="Номер КПП" name="kppNumber">
                    </div>
                </div>
                {{-- KPP number end --}}

                {{-- date KPP --}}
                <div class="col-md-3">
                    <label for="date_kpp">ДАТА ЗАЕЗДА КПП:</label>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input required type="date" class="form-control" id="date_kpp" name="date_kpp">
                    </div>
                </div>
                {{-- date KPP end --}}


                {{-- guest type --}}
                <div class="col-md-3">
                    <label for="id_gesttype">ТИП ГОСТЯ:</label>
                </div>
                <div class="col-md-3">
                    <select name="id_gesttype" class="form-select" id="id_gesttype" required>
                        <option selected disabled value="">---НЕ ВЫБРАНО---</option>
                        @foreach ($guesttypes as $gt)
                            <option value="{{ $gt->id }}">{{ $gt->guesttype_en }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- guest type end --}}

                {{-- hotel address disabled --}}
                <div class="col-md-3">
                    <label for="hotel_address">МЕСТО ПРОПИСКИ <small> (По умолчанию адрес зарегистрированной
                            гостиницы):</small></label>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input disabled type="text" class="form-control bg-success text-light text-bold"
                            value="{{ $hotel->address }}" id="hotel_address">
                    </div>
                </div>
                {{-- hotel addres disabled --}}

                {{-- hotel address disabled --}}
                <div class="col-md-3">
                    <label for="direction">МАРШРУТ:</label>
                </div>
                <div class="col-md-9">
                    <div class="input-group">
                        <textarea pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$" autocomplete="off" class="form-control" id="direction"
                            name="direction">
                        </textarea>
                    </div>
                </div>
                {{-- hotel addres disabled --}}



                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-secondary"
                        onclick="prevTab('#general-info-tab')">Назад</button>
                    <button type="button" class="btn btn-primary"
                        onclick="nextTab('#children-info-tab', 'additionalInfo')">Следующий</button>
                </div>
            </form>
        </div>
        <!-- ==============================================additional Info Tab End============================================== -->


        <!-- ================================================children Info Tab================================================ -->
        <div class="tab-pane fade" id="children-info" role="tabpanel" aria-labelledby="children-info-tab">
            <form class="row g-3 needs-validation" novalidate id="childrenForm">
                @csrf
                <!-- Static Child Info Fields (First Child) -->
                <div class="children-entry" id="childEntry0">
                    <div class="row g-3 align-items-center">
                        <!-- Child Full Name -->
                        <div class="col-md-3">
                            <label for="child_full_name">ФИО ребенка</label>
                            <input pattern="^[a-zA-Zа-яА-Я0-9\s\-\.\$]+$" autocomplete="off" type="text"
                                class="form-control" name="children[0][full_name]" id="child_full_name">
                        </div>

                        <!-- Child Birth Date -->
                        <div class="col-md-3">
                            <label for="child_birth_day">Дата рождения</label>
                            <input max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-16 years')); ?>" type="date"
                                class="form-control" name="children[0][birth_day]" id="child_birth_day">
                        </div>

                        <!-- Child sex -->
                        <div class="col-md-3">
                            <label for="child_gen">Пол *</label>
                            <select class="form-select" name="children[0][gen]" id="child_gen">
                                <option selected disabled value="">--Укажите пол --</option>
                                <option value="M">Мужчина</option>
                                <option value="W">Женщина </option>
                            </select>
                        </div>

                        <!-- Remove Button -->
                        <div class="col-md-3">
                            <label for="child_gen">&emsp;</label>
                            <div class="form-btn">
                                <button type="button" class="btn btn-danger remove-child" data-id="0">
                                    <i class="fas fa-trash-alt p-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

            <div class="py-4 pt-0">
                <button type="button" class="btn btn-primary" id="addChild">
                    <i class="fas fa-plus"></i>
                </button>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-secondary"
                    onclick="prevTab('#additional-info-tab')">Назад</button>
                <button type="button" class="btn btn-success px-2" onclick="submitForm()">Сохранить</button>
            </div>
        </div>

        <!-- ================================================children Info Tab end  ================================================ -->

    </div>
</div>

<script>
    // here new gathering dtaa
    if (typeof childCounter === 'undefined') {
        var childCounter = 1;
    }
    // Add new child form section
    $('#addChild').on('click', function() {
        let newChildEntry = `
        <div class="children-entry" id="childEntry${childCounter}">
            <div class="row g-3 align-items-center">
                <!-- Child Full Name -->
                <div class="col-md-3">
                    <label for="child_full_name">ФИО ребенка</label>
                    <input pattern="^[a-zA-Zа-яА-Я0-9\\s\\-\\.\\$]+$" autocomplete="off" type="text" class="form-control" required name="children[${childCounter}][full_name]" id="child_full_name">
                </div>

                <!-- Child Birth Date -->
                <div class="col-md-3">
                    <label for="child_birth_day">Дата рождения</label>
                    <input max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-16 years')); ?>" type="date" class="form-control" required name="children[${childCounter}][birth_day]" id="child_birth_day">
                </div>

                <!-- Child sex -->
                <div class="col-md-3">
                    <label for="child_gen">Пол *</label>
                    <select class="form-select" required name="children[${childCounter}][gen]" id="child_gen">
                        <option selected disabled value="">--Укажите пол --</option>
                        <option value="M">Мужчина</option>
                        <option value="W">Женщина </option>
                    </select>
                </div>

                <!-- Remove Button -->
                <div class="col-md-3">
                    <label for="child_gen">&emsp;</label>
                    <div class="form-btn">
                        <button type="button" class="btn btn-danger remove-child" data-id="${childCounter}">
                            <i class="fas fa-trash-alt p-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

        $('#childrenForm').append(newChildEntry);
        childCounter++;
    });

    // Remove a child entry
    $(document).on('click', '.remove-child', function() {
        let childId = $(this).data('id');
        $('#childEntry' + childId).remove();
    });

    // Collect and submit the data
    function submitForm() {
        let childrenData = [];

        // Collect data from the children entries
        $('.children-entry').each(function(index) {
            let childData = {};

            let fullName = $(this).find('input[name="children[' + index + '][full_name]"]').val();
            let birthDay = $(this).find('input[name="children[' + index + '][birth_day]"]').val();
            let gen = $(this).find('select[name="children[' + index + '][gen]"]').val();

            // Only include the child if all fields are filled out
            if (fullName && birthDay && gen) {
                childData.full_name = fullName;
                childData.birth_day = birthDay;
                childData.gen = gen;
                childrenData.push(childData);
            }
        });

        // Collect data from other forms (if any)
        let formData = $('#initialForm, #generalInfo, #additionalInfo').serializeArray();

        // Combine the data into one object
        let combinedData = {};
        formData.forEach(function(item) {
            combinedData[item.name] = item.value;
        });

        // If children data exists, add it to the combined data
        if (childrenData.length > 0) {
            combinedData.children = childrenData;
        }

        const formIds = ['initialForm', 'generalInfo', 'additionalInfo', 'childrenForm'];
        const formToTabMap = {
            'initialForm': 'initial-info',
            'generalInfo': 'general-info',
            'additionalInfo': 'additional-info'
        };
        let isValid = true;

        let openedTab = false;

        formIds.forEach(function(formId) {
            const form = document.getElementById(formId);

            if (form && !form.checkValidity()) {
                // If form is invalid, add validation feedback
                form.classList.add('was-validated');

                // Find the first invalid input in the form
                const firstInvalidInput = form.querySelector(':invalid');
                if (firstInvalidInput && !openedTab) {
                    const tabId = formToTabMap[formId];
                    const tab = document.querySelector(`a[href="#${tabId}"]`);

                    if (tab) {
                        new bootstrap.Tab(tab).show();
                        openedTab = true;
                    }
                }
                isValid = false;
            }
        });

        // Submitting the data via AJAX
        if (isValid) {
            $.ajax({
                url: '/selflistok/store', // The URL you want to send the data to
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(combinedData), // Convert the object into JSON format
                success: function(response) {
                    if (response.status === 'success') {
                        $.alert({
                            title: 'Успешно сохранено',
                            content: response.message,
                            type: 'green',
                            buttons: {
                                OK: function() {
                                    mainFormModal.close();
                                    location.reload();
                                    console.log('User confirmed success!');
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $.alert({
                        title: 'Error!!!',
                        content: response.message,
                        type: 'red',
                        buttons: {
                            OK: function() {
                                mainFormModal.close();
                                console.log('Error????');
                            }
                        }
                    });
                }
            });
        }

    }


    //    ====================================================================================================

    // ========= prevent enter button submittion
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    });

    // ============ enable tabs
    function enableTabs() {
        const navItems = document.querySelectorAll('.nav-link');
        navItems.forEach(navItem => {
            if (navItem.classList.contains('disabled')) {
                navItem.classList.remove('disabled');
            }
        });
    }

    // ============ disable tabs
    function disableTabs() {
        const navItems = document.querySelectorAll('.nav-link');
        navItems.forEach(navItem => {
            navItem.classList.add('disabled');
        });
    }

    function initialFormOnchange() {
        const form = document.getElementById('initialForm');
        Array.from(form.elements).forEach(element => {
            if (element.tagName === 'INPUT' || element.tagName === 'SELECT') {
                element.addEventListener('input', function() {
                    disableTabs();
                });
            }
        });
    }
    initialFormOnchange();


    // ============check guest from 3rd api
    function formCheck(tabId, formId) {
        event.preventDefault();
        const form = document.getElementById(formId);
        const formData = new FormData(form);
        const spinner = document.getElementById('loadingSpinner');
        const formCheckButton = document.getElementById('formCheckButton');


        if (form && form.checkValidity()) {

            formCheckButton.setAttribute('disabled', 'disabled');
            spinner.style.display = 'inline-block';
            // Perform backend validation
            fetch('/selflistok/check', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {

                    // populate inputs based on responce
                    if (data.status == 'success') {
                        enableTabs();
                        const person = data.person;
                        document.getElementById('firstname').value = person.firstname || '';
                        document.getElementById('lastname').value = person.lastname || '';
                        document.getElementById('surname').value = person.surname || '';
                        document.getElementById('sex').value = person.sex || '';
                        new bootstrap.Tab(tabId).show();
                    } else if (data.status == 'error') {
                        $.alert({
                            title: 'Гость не найден!',
                            content: data.message,
                            closeIcon: true,
                            columnClass: 'col-md-6',
                            theme: 'supervan',
                            autoClose: 'close|5000',
                            buttons: {
                                close: {
                                    text: 'Close',
                                    btnClass: 'btn-red',
                                    action: function() {}
                                },
                            },
                        });
                    }
                    // removing loading from button
                    spinner.style.display = 'none';
                    formCheckButton.removeAttribute('disabled');
                })
                .catch(error => console.error('Backend Validation Error:', error));

            // removing loading from button
            spinner.style.display = 'none';
            formCheckButton.removeAttribute('disabled');
        } else {
            if (form) form.classList.add('was-validated');
        }
    }


    //============= switch to next tab  =============
    function nextTab(tabId, formId) {
        event.preventDefault();
        const form = document.getElementById(formId);
        if (form && form.checkValidity()) {
            new bootstrap.Tab(tabId).show();
        } else {
            if (form) form.classList.add('was-validated');
        }
    }


    //============= switch to prev tab  =============
    function prevTab(tabId) {
        event.preventDefault();
        new bootstrap.Tab(tabId).show();
    }



    // Select citizen flag show-hide
    function changeFlag(optionId, imgId) {
        const selectElement = document.getElementById(optionId);
        const imgElement = document.getElementById(imgId);
        const selectedValue = selectElement.value;

        if (selectedValue) {
            imgElement.src = "{{ asset('uploads/flags') }}/" + selectedValue + ".png";
            imgElement.style.display = 'inline';
        } else {
            imgElement.style.display = 'none';
        }
    }

    // calculate tur sbor
    function calculateSbor() {
        var s_days = document.getElementById('s_days');
        var visit_time = document.getElementById('visit_time');
        var summa_tursbor = document.getElementById('summa_tursbor');

        s_days.addEventListener('input', function(event) {
            // Allow only whole numbers
            this.value = this.value.replace(/[^0-9]/g, '');
            summa_tursbor.style.backgroundColor = '';
            summa_tursbor.value = '';
        });

        $.ajax({
            url: '/selflistok/calcpayment', // The URL you want to send the data to
            method: 'GET',
            contentType: 'application/json',
            data: {
                daysLive: s_days.value,
                dateVisitOn: visit_time.value,
            },
            success: function(response) {
                summa_tursbor.value = response.summa
                summa_tursbor.style.backgroundColor = '#11f54e';
            },
            error: function(xhr) {
                let message = '';
                if (xhr.status === 400) {
                    message = xhr.responseJSON?.message || 'An unknown error occurred';
                } else {
                    message = 'An error occurred: ' + xhr.statusText;
                }

                $.alert({
                    title: 'Ошибка',
                    content: message,
                    closeIcon: true,
                    columnClass: 'col-md-4',
                    autoClose: 'close|3000',
                    buttons: {
                        close: {
                            text: 'Close',
                            btnClass: 'btn-red',
                            action: function() {}
                        },
                    },
                });
            }
        });
    }
</script>




<style>
    .nav-link.active {
        background-color: #007bff !important;
        color: #fff !important;
        border-color: #007bff !important;
    }
</style>
