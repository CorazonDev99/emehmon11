@extends('layouts.app')
@section('style')
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
        .birth-date-person{
            width:492px !important;
        }
        .date-arrive{
            margin-left: 100px !important;
        }
        #flag_img {
            margin-top: 27px;
        }

        #flag_img_countryfrom {
            margin-top: 27px;
        }

        #flag_img_country {
            margin-top: 27px;
        }

        #passport {
            text-transform: uppercase;
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            color: #495057;
            cursor: not-allowed;
            border: 1px solid #ced4da;
            box-shadow: none;
            opacity: 1;
        }


    </style>
@endsection

@section('script')



    <script src="{{ asset('/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


    <script>
        $('#child_name').on('input', function() {
            var maxLength = 30;
            $(this).val($(this).val().replace(/[^a-zA-Z\s]/g, '').substring(0, maxLength)
                .toUpperCase()
            );
        });
    </script>
    <script>
        function updateFlagImage(selectedValue, flagImgId) {
            const flagImg = $(flagImgId);

            if (selectedValue) {
                const flagUrl = `/uploads/flags/${selectedValue}.png`;
                flagImg.attr('src', flagUrl)
                    .attr('title', selectedValue)
                    .show();
            } else {
                flagImg.hide();
            }
        }

        $('#id_citizen').on('change', function () {
            const selectedValue = $(this).val();
            updateFlagImage(selectedValue, '#flag_img');
        });
        $('#id_countryfrom').on('change', function () {
            const selectedValue = $(this).val();
            updateFlagImage(selectedValue, '#flag_img_countryfrom');
        });

        $('#id_country').on('change', function () {
            const selectedValue = $(this).val();
            updateFlagImage(selectedValue, '#flag_img_country');
        });
        function UpdateSelectList(data,domid,selid){
            $(domid).append('<option value="" ' + (selid == -1 ? ' selected': '') + '> --- НЕ ВЫБРАНО ---</option>');
            $.each(data,function(key, value){
                $(domid).append('<option value="'+value[0]+'" '+(selid==value[0]?' selected':'')+'>'+value[1]+'</option>');});
            $(domid).trigger('change');
        }

        $.getJSON("{!! url('listok/combo-select?filter=tb_citizens:id:sp_name04') !!}",function(json){
            UpdateSelectList(json,'#id_country',{{ !empty($row["id_country"]) ? $row["id_country"] : -1}});
            UpdateSelectList(json,'#id_citizen',{{ !empty($row["id_citizen"]) ? $row["id_citizen"] : -1}});
            UpdateSelectList(json,'#id_countryfrom',{{ !empty($row["id_countryFrom"]) ? $row["id_countryFrom"] : -1}});
            const selectedValue = $('#id_citizen').val();
            updateFlagImage(selectedValue, '#flag_img');
            const selectedCountryFrom = $('#id_countryfrom').val();
            updateFlagImage(selectedCountryFrom, '#flag_img_countryfrom');
            const selectedCountry = $('#id_country').val();
            updateFlagImage(selectedCountry, '#flag_img_country');

        });
        $.getJSON("{!! url('listok/combo-select?filter=tb_visittype:id:name') !!}",function(json){
            UpdateSelectList(json,'#id_visitType',{{ isset($row["id_visitType"]) ? $row["id_visitType"]:'' }});
        });
        $.getJSON("{!! url('listok/combo-select?filter=tb_visa:id:name') !!}",function(json){
            UpdateSelectList(json,'#id_visa',{{ isset($row["id_visa"]) ? $row["id_visa"]:'' }});
        });
        $.getJSON("{!! url('listok/combo-select?filter=tb_guests:id:guesttype') !!}",function(json){
            UpdateSelectList(json,'#id_guest',{{ isset($row["id_guest"]) ? $row["id_guest"]:'' }});
        });
        $.getJSON("{!! url('listok/combo-select?filter=tb_hotels:id:') !!}",function(json){
            UpdateSelectList(json,'#id_guest',{{ isset($row["id_guest"]) ? $row["id_guest"]:'' }});
        });


        function tab01() {
            const form = document.querySelector('#home1 form');
            let isValid = true;

            const requiredFields = [
                { id: '#id_citizen', type: 'select' },
                { id: '#input-date1', type: 'date' },
                { id: '#passport', type: 'text' },
            ];

            requiredFields.forEach(field => {
                const element = document.querySelector(field.id);
                if (field.type === 'select' && element.value === '') {
                    isValid = false;
                    element.classList.add('is-invalid');
                } else if (field.type === 'date' && !element.value) {
                    isValid = false;
                    element.classList.add('is-invalid');
                }else if (field.type === 'text' && !element.value) {
                    isValid = false;
                    element.classList.add('is-invalid');
                }else {
                    element.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка!',
                    text: 'Пожалуйста, заполните все обязательные поля.',
                });
                form.classList.add('was-validated');

                return;
            }

            $.ajax({
                url: '/listok/check',
                type: 'POST',
                data: {
                    citezenID: $('#id_citizen').val(),
                    pspType: $('input[name=id_passporttype]:checked').val(),
                    dtb: $('#input-date1').val(),
                    passportNumber: $('#passport').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status === 'success') {
                        $('#hiddenPersonID').val(data.person.last_checkin.person_id);
                        $('#lastname').val(data.person.lastname);
                        $('#firstname').val(data.person.firstname);
                        $('#surname').val(data.person.surname);
                        // $('#datevisiton').val(data.person.last_checkin.reg_date);
                        $('#visa-info').hide();

                        if (data.person.last_checkin.direction_country !== 173) {
                            $('#visaNumber').val(data.person.visaNumber);
                            $('#id_visa').val(data.person.visa_id);
                            $('#dateVisaOn').val(data.person.dateVisaOn);
                            $('#dateVisaOff').val(data.person.dateVisaOff);
                            $('#visaIssuedBy').val(data.person.visaIssuedBy);
                            $('#kpp_number').val(data.person.kppNumber);
                            $('#datekpp').val(data.person.dateKPP);
                            $('#visa-info').show();

                        }else{
                            $('#visa-info').hide();
                            $('#visaNumber').val('');
                            $('#dateVisaOn').val('');
                            $('#dateVisaOff').val('');
                            $('#visaIssuedBy').val('');
                            $('#kpp_number').val();
                            $('#datekpp').val();

                        }

                        const selectedCountryFrom = data.person.last_checkin.direction_country
                        $('#id_countryfrom').val(selectedCountryFrom);
                        updateFlagImage(selectedCountryFrom, '#flag_img_countryfrom')
                        if (selectedCountryFrom in [231, 173]) {
                            $('#pinfl').closest('.mb-3').show();
                        } else {
                            $('#pinfl').closest('.mb-3').hide();
                        }
                        if (data.person.last_checkin.sex === 1) {
                            $('input[value="5"]').prop('checked', true);
                        } else if (data.person.last_checkin.sex === 2) {
                            $('input[value="6"]').prop('checked', true);
                        }

                        $('a[href="#profile1"]').tab('show');
                        increaseProgress(20);
                        $('a[href="#profile1"]').removeClass('disabled');
                        $('a[href="#messages1"]').addClass('disabled');
                        $('a[href="#childdata"]').addClass('disabled');
                        $('a[href="#settings1"]').addClass('disabled');

                        Swal.fire({
                            icon: 'success',
                            title: data.message,
                            text: 'Информация о госте успешно загружена.',
                        });
                    } else {
                        $('#checking').val('0');
                        $('#datebirth').prop('disabled', false);
                        $('#passport').prop('disabled', false);
                        $('#flag_ctz').hide();
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                }
            });
        }

        function tab02() {
            const form = document.querySelector('#profile1 form');
            if (form.checkValidity()) {
                form.classList.remove('was-validated');
                increaseProgress(20)
                $('a[href="#messages1"]').removeClass('disabled');
                $('a[href="#messages1"]').tab('show');
            } else {
                form.classList.add('was-validated');
            }
        }

        function tab03() {
            const form = document.querySelector('#messages1 form');
            if (form.checkValidity()) {
                form.classList.remove('was-validated');
                increaseProgress(20)
                $('a[href="#childdata"]').removeClass('disabled');
                $('a[href="#childdata"]').tab('show');
            } else {
                form.classList.add('was-validated');
            }

        }

        function tab04() {
            increaseProgress(20)
            $('a[href="#settings1"]').removeClass('disabled');
            $('a[href="#settings1"]').tab('show');
        }


        function returnToHomeTab() {
            increaseProgress(-20)
            $('a[href="#home1"]').tab('show');
            $('a[href="#profile1"]').addClass('disabled');
            $('a[href="#messages1"]').addClass('disabled');
            $('a[href="#childdata"]').addClass('disabled');
            $('a[href="#settings1"]').addClass('disabled');
        }

        function returnToTab02() {
            increaseProgress(-20)
            $('a[href="#profile1"]').tab('show');
            $('a[href="#messages1"]').addClass('disabled');
            $('a[href="#childdata"]').addClass('disabled');
            $('a[href="#settings1"]').addClass('disabled');
        }

        function returnToTab03() {
            increaseProgress(-20)
            $('a[href="#messages1"]').tab('show');
            $('a[href="#childdata"]').addClass('disabled');
            $('a[href="#settings1"]').addClass('disabled');
        }

        function returnToTab04() {
            increaseProgress(-20)

            $('a[href="#childdata"]').tab('show');
            $('a[href="#settings1"]').addClass('disabled');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dateVisitOn = document.getElementById('datevisiton');
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const hours = String(today.getHours()).padStart(2, '0');
            const minutes = String(today.getMinutes()).padStart(2, '0');
            dateVisitOn.value = `${year}-${month}-${day}T${hours}:${minutes}`;
        });

        function convertDateFormat(dateStr) {
            const dateParts = dateStr.split('/');
            if (dateParts.length === 3) {
                return `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`;
            }
            return dateStr;
        }


        function saveProfileData() {
            const children = [];
            $('[data-repeater-item]').each(function() {
                const childName = $(this).find('#child_name').val();
                const childBirthday = $(this).find('#input-date4').val();
                const childGender = $(this).find('#child_gender').val();

                if (childName || childBirthday || childGender) {
                    children.push({
                        name: childName,
                        birthday: childBirthday,
                        gender: childGender,
                    });
                }
            });



            const personID = $('#hiddenPersonID').val();
            const passport = document.getElementById('passport');
            let passportSerial = null;
            let passportNumber = null;

            const sexValue = $('input[name="sex"]:checked').val();
            const sex = sexValue === '5' ? 'M' : (sexValue === '6' ? 'W' : null);



            if (passport && passport.value) {
                const passportMatch = passport.value.match(/^([A-Za-z]+)[-\s]?(\d+)$/);

                if (passportMatch) {
                    passportSerial = passportMatch[1];
                    passportNumber = passportMatch[2];
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Неверный формат паспорта!',
                        text: 'Пожалуйста, убедитесь, что номер паспорта содержит буквы и цифры.',
                    });
                }
            }
            const data = {
                datebirth: convertDateFormat($('#input-date1').val()),
                passportSerial: passportSerial,
                passportNumber: passportNumber,
                datePassport: convertDateFormat($('#input-date2').val()),
                sex: sex,
                childrens: children,
                propiska: $('#id_room').val(),
                wdays: $('#stay_days').val(),
                id_visitType: $('#id_visitType').val(),
                id_visa: $('#id_visa').val(),
                kppNumber: $('#kpp_number').val(),
                dateKPP: convertDateFormat($('#datekpp').val()),
                payed: $('#payment_status').val(),
                amount: $('#payment_amount').val(),
                id_guest: $('#id_guest').val(),
                visaNumber: $('#visaNumber').val(),
                dateVisaOn: $('#dateVisaOn').val(),
                dateVisaOff: $('#dateVisaOff').val(),
                visaIssuedBy: $('#visaIssuedBy').val(),
                pinfl: $('#pinfl').val(),
                passportissuedby: $('#passportissuedby').val(),
                surname: $('#surname').val(),
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val() || "XXX",
                id_country: $('#id_country').val(),
                id_countryfrom: $('#id_countryfrom').val(),
                id_passporttype: $('input[name="id_passporttype"]:checked').val(),
                datevisiton: $('#datevisiton').val(),
                lived_days: $('#stay_days').val() || 0,
                id_person: personID,
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '/listok/savedata',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = '/listok'
                    } else {
                        Swal.fire('Ошибка', 'Произошла ошибка', 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr);
                    Swal.fire('Ошибка', 'Произошла ошибка при сохранении данных', 'error');
                }
            });
        }

    </script>
    <script>
        function increaseProgress(increment) {
            const progressBar = document.getElementById('progress-bar');
            let currentProgress = parseInt(progressBar.getAttribute('aria-valuenow'));
            let newProgress = Math.min(currentProgress + increment, 100); // Cap at 100%

            progressBar.style.width = newProgress + '%';
            progressBar.setAttribute('aria-valuenow', newProgress);
            progressBar.textContent = newProgress + '%';
        }
    </script>
    <script>
        // Validate Date
        document.addEventListener('DOMContentLoaded', function () {
            class DateValidator {
                constructor(inputElement, errorMessageElement, minYearSpanElement) {
                    this.inputElement = inputElement;
                    this.errorMessageElement = errorMessageElement;
                    this.minYearSpanElement = minYearSpanElement;
                    this.today = new Date();
                    this.minYear = this.today.getFullYear() - 150;

                    this.applyMask();
                    this.addEventListeners();

                    if (this.minYearSpanElement) {
                        this.minYearSpanElement.textContent = this.minYear;
                    }
                }

                applyMask() {
                    const im1 = new Inputmask("dd/mm/yyyy");
                    im1.mask(this.inputElement);
                }

                addEventListeners() {
                    this.inputElement.addEventListener('input', this.handleInput.bind(this));
                    this.inputElement.addEventListener('blur', this.handleBlur.bind(this));
                }

                handleInput() {}

                handleBlur() {
                    const dateValue = this.inputElement.value;
                    const dateParts = dateValue.split('/');

                    if (dateParts.length === 3) {
                        const year = parseInt(dateParts[2], 10);

                        if (year < this.minYear) {
                            this.inputElement.value = '';
                            if (this.errorMessageElement) {
                                this.errorMessageElement.style.display = 'block';
                            }
                        } else {
                            if (this.errorMessageElement) {
                                this.errorMessageElement.style.display = 'none';
                            }
                        }
                    }
                }
            }

            const dateInputs = [
                { inputId: "input-date1", errorMessageId: "error-message-date1", minYearSpanId: "min-year1" },
                { inputId: "input-date2", errorMessageId: "error-message-date2", minYearSpanId: "min-year2" },
                { inputId: "input-date3", errorMessageId: "error-message-date3", minYearSpanId: "min-year3" },
                { inputId: "input-date4", errorMessageId: "error-message-date4", minYearSpanId: "min-year4" }
            ];

            dateInputs.forEach(({ inputId, errorMessageId, minYearSpanId }) => {
                const inputElement = document.getElementById(inputId);
                const errorMessageElement = document.getElementById(errorMessageId);
                const minYearSpanElement = document.getElementById(minYearSpanId);

                if (inputElement && errorMessageElement && minYearSpanElement) {
                    new DateValidator(inputElement, errorMessageElement, minYearSpanElement);
                }
            });
        });

        //Validate password
        document.addEventListener('DOMContentLoaded', function () {
            const passportInput = document.getElementById('passport');
            const errorMessage = document.getElementById('error-message');

            passportInput.addEventListener('input', function (e) {
                let value = e.target.value;

                value = value.toUpperCase();

                value = value.replace(/[^A-Za-z0-9\-]/g, '');

                const hasLetters = /[A-Za-z]/.test(value);
                const hasNumbers = /\d/.test(value);

                if (!hasLetters || !hasNumbers) {
                    errorMessage.textContent = 'Разрешены только анг. буквы, цифры и дефис (-)';
                    errorMessage.style.display = 'block';
                    checkButton.disabled = true;


                } else {
                    errorMessage.style.display = 'none';
                    checkButton.disabled = false;
                }
                e.target.value = value;
            });
        });

    </script>



    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

    <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-repeater.int.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alerts.init.js') }}"></script>

    <script src="{{ asset('assets/js/pages/form-mask.init.js') }}"></script>
    <script src="{{ asset('assets/libs/inputmask/inputmask.min.js') }}"></script>

    <script>
        document.querySelector('[data-repeater-create]').addEventListener('click', function () {
            setTimeout(() => {
                Inputmask().mask(document.querySelectorAll('.input-mask-date'));
            }, 100);
        });
    </script>



    @endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="user">
                    <div class="card-header d-flex justify-content-between">
                        <h4><b>Листок прибытие</b></h4>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#exitModal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exitModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exitModalLabel">Подтверждение выхода</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Вы действительно хотите выйти? Все данные будут потеряны!
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет</button>
                                        <a href="/listok" class="btn btn-primary">Да</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress mt-3">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width:0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home1" onclick="returnToHomeTab()" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">Поиск гостя</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#profile1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                    <span class="d-none d-sm-block">Основная информация</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#messages1" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">Доп. информация</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#childdata" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Информация о детях</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" data-bs-toggle="tab" href="#settings1" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">Информация о госте</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="home1" role="tabpanel">
                                <div>
                                    <div class="row col-md-6">
                                        <div class="col-md-12">
                                            <div class="mt-1 d-flex">
                                                <div class="row">
                                                    <div class="col-md-11">
                                                        <label for="id_citizen">ГРАЖДАНСТВО: <span class="text-danger">*</span></label>
                                                        <select id="id_citizen" name="id_citizen" class="form-control form-select" required></select>
                                                    </div>
                                                    <div class="col-md-1 flag">
                                                        <img id="flag_img" src="" alt="Флаг" title="" width="50px" height="35px" style="text-shadow: 1px 1px; border: 1px solid #777; display: none; " />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="mb-3">
                                                <label for="basicpill-lastname-input">ТИП ДОКУМЕНТА:</label><br>
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio1" autocomplete="off" checked value="1">
                                                    <label class="btn btn-outline-primary" for="btnradio1">Паспорт</label>
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio2" autocomplete="off"  value="2">
                                                    <label class="btn btn-outline-primary" for="btnradio2">Воен.удостоверение</label>
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio3" autocomplete="off"  value="3">
                                                    <label class="btn btn-outline-primary" for="btnradio3">Другой документ</label>
                                                    <input type="radio" class="btn-check" name="id_passporttype" id="btnradio4" autocomplete="off"  value="4">
                                                    <label class="btn btn-outline-primary" for="btnradio4">ID карта</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="birth-date-person">
                                            <div class="mb-3">
                                                <label class="form-label" for="input-date1">ДАТА РОЖДЕНИЕ: <span class="text-danger">*</span></label>
                                                <input id="input-date1" class="form-control input-mask-date" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" required>
                                                <div id="error-message-date1" class="text-danger" style="display: none; font-size: 0.875rem; margin-top: 5px;">
                                                    Дата должна быть не раньше чем <span id="min-year1"></span>.
                                                </div>
                                            </div>

                                        </div>
                                        <div class="birth-date-person">
                                            <div class="mb-3">
                                                <label for="passport">ПАСПОРТ СЕРИЯ|НОМЕР: <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="passport" name="passport" placeholder="" required>
                                                <div id="error-message" class="text-danger" style="display: none; font-size: 0.875rem; margin-top: 5px;">
                                                    Разрешены только анг. буквы, цифры и дефис (-).
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <button type="button" class="btn btn-primary waves-effect waves-light" id="checkButton" onclick="tab01()">Проверить</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="profile1" role="tabpanel">
                                <section>
                                    <form>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="pinfl">ПИНФЛ:</label>
                                                <input class="form-control" name="pinfl" type="text" maxlength="14" id="pinfl" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="input-date2">ПАСПОРТ ДАТА ВЫДАЧИ: <span class="text-danger">*</span></label>
                                                <input id="input-date2" class="form-control input-mask-date" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" required>
                                                <div id="error-message-date2" class="text-danger" style="display: none; font-size: 0.875rem; margin-top: 5px;">
                                                    Дата должна быть не раньше чем <span id="min-year2"></span>.
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="passportissuedby">КЕМ ПАСПОРТ ВЫДАН:</label>
                                                <input type="text" class="form-control" id="passportissuedby" name="passportissuedby" placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3 d-flex justify-content-between">
                                            <div class="col-md-3">
                                                <label class="form-label" for="surname">ФАМИЛИЯ: <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       id="surname"
                                                       name="surname"
                                                       class="form-control"
                                                       placeholder=""
                                                       required
                                                       disabled
                                                       pattern="^[\p{L}]{1,30}$"
                                                       title="Введите только буквы, минимум 1 и максимум 30 символов.">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="firstname">ИМЯ: <span class="text-danger">*</span></label>
                                                <input type="text"
                                                       id="firstname"
                                                       name="firstname"
                                                       class="form-control"
                                                       placeholder=""
                                                       required
                                                       disabled
                                                       pattern="^[\p{L}]{1,30}$"
                                                       title="Введите только буквы, минимум 1 и максимум 30 символов.">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="lastname">ОТЧЕСТВО: </label>
                                                <input type="text"
                                                       id="lastname"
                                                       name="lastname"
                                                       class="form-control"
                                                       placeholder=""
                                                       disabled
                                                       pattern="^[\p{L}]{1,30}$"
                                                       title="Введите только буквы, минимум 1 и максимум 30 символов.">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <label for="basicpill-companyuin-input">СТРАНА РОЖДЕНИЯ: <span class="text-danger">*</span></label>
                                                            <select name='id_country' id='id_country' class='select2 form-select' required></select>
                                                        </div>
                                                        <div class="col-md-1 flag">
                                                            <img id="flag_img_country" src="" alt="Флаг" title="" width="50px" height="35px" style="text-shadow: 1px 1px; border: 1px solid #777; display: none;" />
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <label for="id_countryfrom">СТРАНА ОТКУДА ПРИБЫЛ:</label>
                                                            <select name='id_countryfrom' id='id_countryfrom' class='select2 form-select' disabled></select>
                                                        </div>
                                                        <div class="col-md-1 flag">
                                                            <img id="flag_img_countryfrom" src="" alt="Флаг" title="" width="50px" height="35px" style="text-shadow: 1px 1px; border: 1px solid #777; display: none;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-3 ">
                                                        <div class="mb-3">
                                                            <label for="basicpill-lastname-input">ПОЛ: <span class="text-danger">*</span></label><br>
                                                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                                <input type="radio" class="btn-check" name="sex" id="btnradio5" autocomplete="off" value="5" required disabled>
                                                                <label class="btn btn-outline-primary" for="btnradio5">Мужчина</label>
                                                                <input type="radio" class="btn-check" name="sex" id="btnradio6" autocomplete="off"  value="6" required disabled>
                                                                <label class="btn btn-outline-primary" for="btnradio6">Женщина</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mt-1 date-arrive">
                                                        <div class="mb-3">
                                                            <label for="datevisiton">ДАТА ПРИБЫТИЯ: <span class="text-danger">*</span></label>
                                                            <input class="form-control inputmaskDate" data-date-format='dd-mm-yyyy' name="datevisiton" type="datetime-local" required readonly id="datevisiton">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-end">
                                                    <div class="m-4">
                                                        <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab02()">Далее</button>
                                                        <a class="btn btn-danger" onclick="returnToHomeTab()">Отмена</a>
                                                    </div>
                                                </div>
                                            </div>
                                    </form>
                                </section>
                            </div>
                            <div class="tab-pane" id="messages1" role="tabpanel">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="id_room">Номер / Комната: <span class="text-danger">*</span></label>
                                                <select id="id_room" name="id_room" class="select2 form-select" required>
                                                    <option value="">--- НЕ ВЫБРАНО ---</option>
                                                    @foreach ($rooms as $room)
                                                        <option value="{{ $room->room_number }}">
                                                            {{ $room->room_number }} - {{ $room->room_type }};
                                                            {{ $room->room_floor }} - этаж;
                                                            {{ $room->living_room }}/{{ $room->beds }}
                                                            @if (!empty($room->tag))
                                                                ;({{ $room->tag }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="stay_days">На сколько дней прибыл:</label>
                                                <input type="number" id="stay_days" name="stay_days" class="form-control">
                                            </div>
                                            <div class="mb-3">
                                                <label for="id_visitType">Тип визита:</label>
                                                <select id="id_visitType" name="id_visitType" class="select2 form-select"></select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="payment_status">Статус оплаты:</label>
                                                <select id="payment_status" name="payment_status" class="form-control">
                                                    <option disabled selected>-- НЕ ВЫБРАНО --</option>
                                                    <option value="1">Оплачен частично</option>
                                                    <option value="2">Оплачен полностью</option>
                                                    <option value="3">Не оплачен</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="payment_amount">Сумма оплаты:</label>
                                                <input type="number" id="payment_amount" name="payment_amount" class="form-control" value="0">
                                            </div>

                                            <div class="mb-3">
                                                <label for="id_guest">Тип гостя:</label>
                                                <select id="id_guest" name="id_guest" class="form-control">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="visa-info">
                                            <div class="mb-2">
                                                <label for="id_visa">Тип визы:</label>
                                                <select id="id_visa" name="id_visa" class="form-control" readonly></select>
                                            </div>

                                            <div class="mb-2">
                                                <label class="visa-label" for="visaNumber">Номер визы:</label>
                                                <input type="text" id="visaNumber" class="form-control form-text" readonly />
                                            </div>

                                            <div class="mb-3">
                                                <label for="dateVisaOn">Дата выдачи визы:</label>
                                                <input type="date" id="dateVisaOn" class="form-control form-text" readonly />
                                            </div>

                                            <div class="mb-3">
                                                <label for="dateVisaOff">Visa Expiry Date:</label>
                                                <input type="date" id="dateVisaOff" class="form-control form-text" readonly />
                                            </div>

                                            <div class="mb-3">
                                                <label for="visaIssuedBy">Виза выдана:</label>
                                                <input type="text" id="visaIssuedBy" class="form-control form-text" readonly />
                                            </div>

                                            <div class="mb-3">
                                                <label for="kpp_number">КПП №:</label>
                                                <input type="text" id="kpp_number" name="kpp_number" class="form-control" value="" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="datekpp">Дата заезда КПП: </label>
                                                <input type="date" id="datekpp" class="form-control form-text" readonly />
                                                {{--                                                   <input id="input-date3" class="form-control input-mask-date" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy" readonly>--}}
                                                {{--                                                   <div id="error-message-date3" class="text-danger" style="display: none; font-size: 0.875rem; margin-top: 5px;">--}}
                                                {{--                                                       Дата должна быть не раньше чем <span id="min-year3"></span>.--}}
                                                {{--                                                   </div>--}}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="m-4">
                                            <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab03()">Далее</button>
                                            <a class="btn btn-danger" onclick="returnToTab02()">Отмена</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane" id="childdata" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <form class="repeater" enctype="multipart/form-data">
                                                    <div data-repeater-list="group-a">
                                                        <div data-repeater-item class="row">
                                                            <div  class="mb-3 col-md-4">
                                                                <label class="form-label" for="name">ФИО РЕБЁНКА:</label>
                                                                <input type="text" id="child_name" name="untyped-input" class="form-control" placeholder="" />
                                                            </div>

                                                            <div  class="mb-3 col-md-4">
                                                                <label class="form-label" for="input-date2">ДАТА РОЖДЕНИЕ: </label>
                                                                <input id="input-date4" class="form-control input-mask-date" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="dd/mm/yyyy">
                                                                <div id="error-message-date4" class="text-danger" style="display: none; font-size: 0.875rem; margin-top: 5px;">
                                                                    Дата должна быть не раньше чем <span id="min-year4"></span>.
                                                                </div>
                                                            </div>

                                                            <div  class="mb-3 col-md-3">
                                                                <label class="form-label" for="gender">ПОЛ:</label>
                                                                <select id="child_gender" class="form-control">
                                                                    <option value="" disabled selected>Выберите пол</option>
                                                                    <option value="M">Мальчик</option>
                                                                    <option value="W">Девочка</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-1 mt-2 align-self-center">
                                                                <button data-repeater-delete type="button" class="btn btn-outline-danger rounded">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <input data-repeater-create type="button" class="btn btn-success mt-3 mt-lg-0" value="Добавить"/>

                                                    <div class="col-12 d-flex justify-content-end">
                                                        <div class="m-5">
                                                            <button type="button" class="btn btn-primary waves-effect waves-light" onclick="tab04()">Далее</button>
                                                            <a class="btn btn-danger" onclick="returnToTab03()">Отмена</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane" id="settings1" role="tabpanel">
                                <div class="form-group">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#info">Информация</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#reviews">Отзывы <span class="badge badge-success">0</span></a>
                                        </li>
                                    </ul>

                                    <div class="tab-content mt-3">
                                        <div id="info" class="tab-pane fade show active">
                                            <p>Информация о госте</p>
                                        </div>
                                        <div id="reviews" class="tab-pane fade">
                                            <p>Отзывы гостя</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="saveProfileData();">Сохранить</button>
                                <a class="btn btn-danger" onclick="returnToTab04()">Отмена</a>
                            </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





@endsection

