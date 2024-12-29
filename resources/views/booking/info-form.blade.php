<link href="/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

<style>
    .select2-dropdown {
        z-index: 5000;
    }

    .table-bordered {
        border: none;
        border-collapse: collapse;
    }

    .table-bordered th,
    .table-bordered td {
        border: none;
    }

    .table tr:nth-child(odd) {
        background-color: #f8f9fa;
        border: none;
    }

    .table tr:nth-child(even) {
        background-color: #ffffff;
        border: none;
    }

    .table td {
        text-align: left;
    }

    .hidden {
        display: none;
    }
</style>

<div class="content">
    <div class="mt-3">
        <button id="show-info" class="btn btn-info">Info</button>
        <button id="show-edit" class="btn btn-warning">Booking Edit</button>
    </div>
    <div id="info-section">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td>Full Name</td>
                    <td>{{ $booking->staffname }}</td>
                </tr>
                <tr>
                    <td>Contact Phone</td>
                    <td>{{ $booking->contact_phone }}</td>
                </tr>
                <tr>
                    <td>Room</td>
                    <td>{{ $booking->room_id }}</td>
                </tr>
                <tr>
                    <td>Start Date Booking</td>
                    <td>{{ $booking->date_from ?? '' }}</td>
                </tr>
                <tr>
                    <td>End Date Booking</td>
                    <td>{{ $booking->date_to ?? '' }}</td>
                </tr>

                <tr>
                    <td>Guest</td>
                    <td>{{ $booking->adults }}</td>
                </tr>
                <tr>
                    <td>Children</td>
                    <td>{{ $booking->children }}</td>
                </tr>
                <tr>
                    <td>Created Booking</td>
                    <td>{{ $booking->created_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="edit-section" class="hidden">
        <form>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Full Name</label>
                        <input class="form-control" name="new_staffname" value="{{ $booking->staffname ?? '' }}" id="room" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Contact phone</label>
                        <input class="form-control" name="new_contact_phone" value="{{ $booking->contact_phone ?? '' }}" id="new-room" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Start Booking</label>
                        <input class="form-control" name="new_minDate" value="{{ $booking->date_from ?? '' }}" id="new-start-date" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">End Booking</label>
                        <input class="form-control" name="new_maxDate" value="{{ $booking->date_to ?? '' }}" id="new-end-date" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="select-guest">Guest</label>
                        <select class="select2 form-select" name="new_adults">
                            <option value="{{ $booking->adults }}" selected>{{ $booking->adults }} adult</option>
                            @if($booking->adults != 1)
                                <option value="1">1 adult</option>
                            @endif
                            @if($booking->adults != 2)
                                <option value="2">2 adults</option>
                            @endif
                            @if($booking->adults != 3)
                                <option value="3">3 adults</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="select-children">Children</label>
                        <select class="select2 form-select" name="new_children">
                            <option value="{{ $booking->children }}" selected>{{ $booking->children }}</option>
                            @if($booking->children != 0)
                                <option value="0">0</option>
                            @endif
                            @if($booking->children != 1)
                                <option value="1">1</option>
                            @endif
                            @if($booking->children != 2)
                                <option value="2">2</option>
                            @endif
                            @if($booking->children != 3)
                                <option value="3">3</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="example-date-input">Date of Birth</label>
                        <input class="form-control" name="new_datebirth" value="{{ $booking->dtb }}" id="new-date-birth" placeholder="dd.mm.yyyy">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="passport">Passport Series | Number</label>
                        <input type="text" class="form-control" id="passport" name="new_passport" placeholder="" value="{{ $booking->doc_number }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="contact-email">Contact Email</label>
                        <input type="email" class="form-control" id="contact-email" name="new_contact_email" placeholder="example@mail.com" value="{{ $booking->contact_email }}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="basicpill-pancard-input">PINFL</label>
                        <input class="form-control" name="new_pinfl" type="text" maxlength="14" id="pinfl" value="{{ $booking->pinfl }}">
                    </div>
                </div>
            </div>
            <!--more fields-->
        </form>
    </div>
</div>

<script>
    document.getElementById('show-info').addEventListener('click', function () {
        document.getElementById('info-section').classList.remove('hidden');
        document.getElementById('edit-section').classList.add('hidden');
    });

    document.getElementById('show-edit').addEventListener('click', function () {
        document.getElementById('info-section').classList.add('hidden');
        document.getElementById('edit-section').classList.remove('hidden');
    });
    function formatDateInput(inputElement) {
        inputElement.addEventListener('input', function (e) {
            let input = e.target.value.replace(/\D/g, '');
            if (input.length > 2) input = input.slice(0, 2) + '.' + input.slice(2);
            if (input.length > 5) input = input.slice(0, 5) + '.' + input.slice(5);
            if (input.length > 10) input = input.slice(0, 10);
            e.target.value = input;
        });
    }

    const startDateInput = document.getElementById('new-start-date');
    const endDateInput = document.getElementById('new-end-date');
    const dateBirth = document.getElementById('new-date-birth');

    if (startDateInput) formatDateInput(startDateInput);
    if (endDateInput) formatDateInput(endDateInput);
    if (dateBirth) formatDateInput(dateBirth);
</script>
