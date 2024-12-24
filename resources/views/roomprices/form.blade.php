<div>
    <h3>New Room Price</h3>
    <section>
        <form id="price-list-store">
            @csrf
            <div class="row mx-auto">
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex ">
                        <label class="customWidth" for="hotelName" class="me-2 mb-0 justify-content-start">HOTEL</label>
                        <span class="form-control bg-success">{{ $hotelName }}</span>
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="dt">DATE</label>
                        <input required autocomplete="off" class="form-control" name="dt" type="date"
                            id="dt" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                    </div>
                </div>

                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="id_type">ROOM TYPE</label>
                        <select id="id_type" name="id_type" class="form-select" required>
                            <option value="" disabled selected>Select a room type</option>
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}">{{ $roomType->en }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="beds">BEDS</label>
                        <input type="number" class="form-control" id="beds" name="beds" min="1"
                            placeholder="Enter beds">
                        <small class="text-danger d-none" id="bedsError">Please enter a valid number</small>
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="capacity">CAPACITY</label>
                        <input type="number" class="form-control" id="capacity" name="capacity"
                            placeholder="Enter capacity" min="1">
                        <small class="text-danger d-none" id="capacityError">Please enter a valid number</small>
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="uzs">PRICE UZS</label>
                        <input type="number" class="form-control" id="uzs" name="uzs" min="1"
                            max="50000000">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="usd">PRICE USD</label>
                        <input type="number" class="form-control" id="usd" name="usd" min="1"
                            max="50000">
                    </div>
                </div>
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <div class="form-group">
                            <label class="control-label customWidthEx text-left"> BREAKFAST:</label>
                            <input type="hidden" name="breakfast" value="0" id="hidden-breakfast">
                            <label class="switch">
                                <input type="checkbox" name="breakfast" value="1" id="breakfast-checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <script>
        const dateInput = document.getElementById("dt");
        const currentDate = new Date();
        const oneYearFromNow = new Date(currentDate.getFullYear() + 1, currentDate.getMonth(), currentDate
            .getDate()); // One year from today

        dateInput.addEventListener("blur", () => {
            const dateValue = dateInput.value;

            if (!dateValue) {
                dateInput.style.borderColor = "red"; // Highlight if the field is empty
                return;
            }

            const inputDate = new Date(dateValue);

            if (inputDate < currentDate || inputDate > oneYearFromNow) {
                dateInput.style.borderColor = "red"; // Invalid if outside the range
            } else {
                dateInput.style.borderColor = ""; // Reset if valid
            }
        });

        $(document).ready(function() {
            function validateField(inputId, errorId) {
                const value = $(inputId).val();
                if (value === "" || !/^[1-9]\d*$/.test(value)) {
                    $(errorId).removeClass('d-none');
                    return false;
                } else {
                    $(errorId).addClass('d-none');
                    return true;
                }
            }

            $("#beds, #capacity").on("input", function(event) {
                const inputId = "#" + $(this).attr("id");
                const errorId = "#" + $(this).attr("id") + "Error";
                validateField(inputId, errorId);
                event.stopImmediatePropagation();
            });

            $("form").on("submit", function(event) {
                let isValid = true;
                isValid &= validateField("#beds", "#bedsError");
                isValid &= validateField("#capacity", "#capacityError");

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
    <style>
        .customWidth {
            width: 120px;
        }

        .customWidthEx {
            width: 100px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 19px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #f8ac59;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #f8ac59;
            ;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(20px);
            -ms-transform: translateX(20px);
            transform: translateX(20px);
        }

        .form-control {
            border: 1px solid #6689ff
        }

        label .text-left {
            font-weight: 700;
        }
    </style>
