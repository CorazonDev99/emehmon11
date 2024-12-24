<div>
    <h3>Record # {{ $data->id }} </h3>
    <section>
        <form>
            <div class="row mx-auto">
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex ">
                        <label class="customWidth" for="hotelName" class="me-2 mb-0 justify-content-start">HOTEL</label>
                        @if ($data && isset($data->name))
                            <span class="form-control bg-success">{{ $data->name }}</span>
                        @else
                            <span class="form-control bg-danger">Hotel Name Not Found</span>
                        @endif

                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="date">ДАТА</label>
                        <input class="form-control inputmaskDate" name="date" disabled value="{{ $data->dt }}"
                            type="date" required id="date">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="room_type">ROOM TYPE</label>
                        <input class="form-control " type="input" disabled value="{{ $room_type->en }}">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="beds">Beds</label>
                        <input type="number" class="form-control" disabled value="{{ $data->beds }}">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="people">People Number</label>
                        <input type="number" class="form-control" disabled value="{{ $data->capacity }}">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="uzs">Price UZS</label>
                        <input type="number" class="form-control" disabled value="{{ $data->uzs }}">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="usd">Price USD</label>
                        <input type="number" class="form-control" disabled value="{{ $data->usd }}">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <!-- Switch Input -->
                    <div class="mb-3 d-flex form-check form-switch px-0">
                        <label class="customWidth" for="booleanSwitch">Breakfast</label>
                        <input type="text" class="form-control" disabled
                            value="{{ $data->breakfast ? 'yes' : 'not' }}">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <!-- Switch Input -->
                    <div class="mb-3 d-flex form-check form-switch px-0">
                        <label class="customWidth">Created at</label>
                        <input type="text" class="form-control" disabled value="{{ $data->created_at }}">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <!-- Switch Input -->
                    <div class="mb-3 d-flex form-check form-switch px-0">
                        <label class="customWidth">Created by</label>
                        <input type="text" class="form-control" disabled value="{{ $created_by }}">
                    </div>
                </div>

            </div>
        </form>
    </section>
    <style>
        .customWidth {
            width: 200px;
        }
    </style>
