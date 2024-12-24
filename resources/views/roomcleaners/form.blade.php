<div>
    <h3 class="text-uppercase">NEW housekeeper</h3>
    <br>
    <section>
        <form>
            @csrf
            <div class="row mx-auto">

                {{-- name of staf --}}
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="cleaner">Full name</label>
                        <input value="{{ old('cleaner', $data->cleaner ?? '') }}" required autocomplete="off"
                            class="form-control" name="cleaner" type="text" id="cleaner">
                    </div>
                </div>


                {{-- hiring date --}}
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="work_start">DATE</label>
                        <input required autocomplete="off" class="form-control" name="work_start" type="date"
                            value="{{ old('work_stop', $data->work_start ?? '') }}" id="work_start" required
                            max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" min="<?php echo date('Y-m-d', strtotime('-1 year')); ?>">
                    </div>
                </div>

                {{-- firing date --}}
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="work_stop">DATE</label>
                        <input autocomplete="off" class="form-control" name="work_stop" type="date" id="work_stop"
                            value="{{ old('work_start', $data->work_stop ?? '') }}" min="<?php echo date('Y-m-d'); ?>"
                            max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                    </div>
                </div>


                {{-- Status of employee --}}
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <div class="form-group">
                            <label class="control-label customWidthEx text-left">ACTIVE</label>
                            <input type="hidden" name="active" value="0">
                            <!-- Hidden input to ensure default value -->
                            <label class="switch">
                                <input type="checkbox" name="active" value="1"
                                    @if (isset($data) && $data->active == 1) checked @endif>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>
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
