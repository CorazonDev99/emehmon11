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
    @csrf
    <h3>Edit Booking</h3>
    <section>
        <form>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Booking Room</label>
                        <input class="form-control" name="room" value="{{ $booking->room_id ?? '' }}" id="room" readonly>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Change Booking Room</label>
                        <input class="form-control" name="new_room" value="{{ $booking->room ?? '' }}" id="new-room" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Start date booking</label>
                        <input class="form-control" name="minDate" value="{{ $booking->date_from ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Change Start date booking</label>
                        <input class="form-control" name="new_minDate" value="{{ $booking->startDate ?? '' }}" id="start-date" required>
                    </div>
                </div>
            </div>
            <div class = "row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">End date booking</label>
                        <input class="form-control" name="maxDate" value="{{ $booking->date_to ?? '' }}" readonly>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Change End date booking</label>
                        <input class="form-control" name="new_maxDate" value="{{ $booking->endDate ?? '' }}" id="end-date" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="basicpill-servicetax-input">Full Name </label>
                        <input type="text" class="form-control" id="surname" name="staffname" value="{{ $booking->staffname }}" readonly>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="contact-phone">Contact Phone</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="contact-phone" name="contact_phone" value="{{ $booking->contact_phone }}" readonly>
                        </div>
                    </div>
                </div>
                <!--
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="basicpill-servicetax-input"> Change Full Name </label>
                        <input type="text" class="form-control" id="new-surname" name="new_staffname" value="" required>
                    </div>
                </div>-->
            </div>
            <!--
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="contact-phone">Contact Phone</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="contact-phone" name="contact_phone" value="{{ $booking->contact_phone }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="contact-phone"> Change Contact Phone</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="new-contact-phone" name="new_contact_phone" value="" required>
                        </div>
                    </div>
                </div>
            </div>-->
            <!-- end row -->
        </form>
    </section>
</div>

<script src="/assets/libs/jquery-steps/build/jquery.steps.min.js"></script>
<script src="/assets/libs/select2/js/select2.min.js"></script>
<script src="{{ asset('assets/js/booking/form.js') }}"></script>
