<div>
    <h3>Hotel Images</h3>
    <br>
    <section>
        <form enctype="multipart/form-data">
            @csrf
            <div class="row mx-auto">
                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <label class="customWidth" for="room_tp">ROOM TYPE</label>
                        <select id="room_tp" name="room_tp" class="form-select" required>
                            <option value="" selected>Hotel Image</option>
                            @foreach ($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" @if (isset($data) && $data->room_tp == $roomType->id) selected @endif>
                                    {{ $roomType->en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-8 mx-auto">
                    @if (isset($data->photo) && $data->photo)
                        <div id="imagePreview" class="mt-3">
                            <img src="{{ asset('storage/hotelimages/' . $data->photo) }}" alt="Image preview"
                                style="max-width: 100%; max-height: 200px;">
                        </div>
                    @else
                        <div class="mb-3 d-flex">
                            <label class="customWidth" for="photo">PHOTO</label>
                            <input required class="form-control" name="photo" type="file" id="photo"
                                accept="image/*" onchange="previewImage(event)">
                        </div>
                        <!-- Preview Section -->
                        <div id="imagePreview" class="mt-3">
                            <p>No image selected</p>
                        </div>
                    @endif
                </div>

                <div class="col-md-8 mx-auto">
                    <div class="mb-3 d-flex">
                        <div class="form-group">
                            <label class="control-label customWidthEx text-left"> Main Photo</label>
                            <input type="hidden" name="is_main" value="0">
                            <!-- Hidden input to ensure default value -->
                            <label class="switch">
                                <input type="checkbox" name="is_main" value="1"
                                    @if (isset($data) && $data->is_main == 1) checked @endif>
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


    <script>
        function previewImage(event) {
            const fileInput = event.target;
            const previewDiv = document.getElementById('imagePreview');
            previewDiv.innerHTML = '';
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];

                if (!file.type.startsWith('image/')) {
                    previewDiv.innerHTML = '<p style="color: red;">Invalid file type. Please select an image.</p>';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Uploaded Image';
                    img.style.maxWidth = '100%';
                    img.style.height = '120px';
                    previewDiv.appendChild(img);
                };
                reader.readAsDataURL(file);
            } else {
                previewDiv.innerHTML = '<p>No image selected</p>';
            }
        }
    </script>
