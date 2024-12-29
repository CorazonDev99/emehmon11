<link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/libs/jquery-ui-dist/jquery-ui.css') }}">
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-ui-dist/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/libs/inputmask/inputmask.min.js') }}"></script>

<style>
    .select2-dropdown{
        z-index: 5000;
    }
    .ui-datepicker {
        background: #f8f9fa; /* Oq rang fon */
        border: 1px solid #ced4da; /* Chegara */
        border-radius: 0.25rem; /* Burchaklarni yumaloqlash */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soyalar */
    }

    .ui-datepicker-header {
        color: #fff; /* Oq matn */
        border-bottom: 1px solid #ced4da;
    }

    .ui-datepicker-calendar td a {
        padding: 5px;
        color: #495057; /* Oddiy sana matni */
        text-decoration: none;
        border-radius: 0.25rem;
        transition: background-color 0.2s, color 0.2s;
    }

    .ui-datepicker-calendar td a:hover {
        background-color: #007bff; /* Hover effekti */
        color: #fff;
    }

    .ui-datepicker-calendar .ui-state-active {
        background-color: #007bff; /* Tanlangan sana */
        color: #fff;
    }
    .ui-datepicker-prev, .ui-datepicker-next {
        color: white; /* Tugma matn rangi */
        border-radius: 0.25rem; /* Tugma burchaklarini yumaloqlash */
        padding: 5px; /* Ichki bo‘shliq */
        cursor: pointer; /* Tugmaga bosiladigan ko‘rinish berish */
        text-align: center; /* Matnni markazlashtirish */
        font-size: 17px; /* Matn o‘lchami */
    }

    .ui-datepicker-prev:hover, .ui-datepicker-next:hover {
        background: #ffffff; /* Tugma hover effekti */
    }

    .ui-datepicker-prev:before {
        content: "\25C0"; /* Chapga o‘q belgisi */
        margin-right: 5px;
    }

    .ui-datepicker-next:after {
        content: "\25B6"; /* O‘ngga o‘q belgisi */
        margin-left: 5px;
    }
</style>
<div id="basic-example">
    <h3>Booking Guest</h3>
    <section>
        <form>
            <!--
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Booking Room <span class="text-danger">*</span></label>
                        <input class="form-control" name="room" value="{{ $room_name }}" id="room" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Booking Room Type <span class="text-danger">*</span></label>
                        <input class="form-control" name="new_room" value="{{ $room_type }}" id="new-room" required>
                    </div>
                </div>
            </div>
            -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Start date booking <span class="text-danger">*</span></label>
                        <input class="form-control" name="minDate" value="{{ $minDate }}" id="start-date" required>
                        <small id="start-date-error" class="text-danger" style="display: none;">Sana noto‘g‘ri kiritilgan!</small>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">End date booking <span class="text-danger">*</span></label>
                        <input class="form-control" name="maxDate" value="{{ $maxDate }}" id="end-date" required>
                        <small id="end-date-error" class="text-danger" style="display: none;">End Date Start Date’dan kichik bo'lishi mumkin emas!</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="contact-phone">Contact Phone <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="contact-phone" name="contact_phone" required>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="basicpill-servicetax-input">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="surname" name="staffname" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for = "select-guest">Guest</label>
                        <select class="select2 form-select" name="guest">
                            <option value="1" selected>1 adult</option>
                            <option value="2">2 adults</option>
                            <option value="3">3 adults</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for = "select-children">Children</label>
                        <select class="select2 form-select" name="children">
                            <option value="0" selected>0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Date of Birth</label>
                        <input class="form-control" name="datebirth" value="" id="date-birth">
                        <small id="birth-date-error" class="text-danger" style="display: none;">Sana noto‘g‘ri kiritilgan!</small>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="passport">Passport Series | Number</label>
                        <input type="text" class="form-control" id="passport" name="passport" placeholder="">
                    </div>
                </div>
            </div>
            <!-- end row -->
        </form>
    </section>
    <section>
        <form>
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="contact-email">Contact Email</label>
                        <input type="email" class="form-control" id="contact-email" name="contact_email" placeholder="example@mail.com">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="basicpill-pancard-input">PINFL</label>
                        <input class="form-control" name="pinfl" type="text" maxlength="14" id="pinfl" value="">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="comments">Comments</label>
                        <textarea class="form-control" id="comments" name="comments" rows="4" placeholder="Comment..."></textarea>
                    </div>
                </div>
            </div><!-- end row -->
        </form>
    </section>
</div>

<script src="/assets/libs/jquery-steps/build/jquery.steps.min.js"></script>
<script src="/assets/libs/select2/js/select2.min.js"></script>
{{--<script src="/assets/js/pages/form-wizard.init.js"></script>--}}
<script src="{{ asset('assets/js/booking/form.js') }}"></script>
