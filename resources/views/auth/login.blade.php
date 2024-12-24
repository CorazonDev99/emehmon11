@extends('layouts.guest')
@section('title', 'Kirish')
@section('styles')
    <link rel="stylesheet" href="/assets/face-detection/style/face-detection.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src='/assets/face-detection/js/face-api.min.js'></script>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
    <style>

        .card{
            width: 600px;
            height: 500px;

        }

        .card-f {
            width: 600px;
            height: 500px;
        }
    </style>
@endsection
@section('content')
    <div class="col-md-8 col-lg-6 col-xl-5 card-f">
        <div class="card">

            <div class="card-body p-4">
                <div class="text-center mt-2">
                    <h5 class="text-primary">Xush kelibsiz!</h5>
                    <p class="text-muted">Hisobingizga kirish uchun email va parolingizni kiriting.</p>
                </div>
                <div class="p-2 mt-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div id="login">
                            <div class="mb-3">
                                <label class="form-label" for="email">{{__('Email')}}</label>
                                <input type="text" class="form-control" id="email" placeholder="Email kiriting" value="kabus1@emehmon.uz">
                            </div>

                            <div class="mb-3">
                                <div class="float-end">
                                    <a href="auth-recoverpw.html" class="text-muted">Parolni unutdingizmi?</a>
                                </div>
                                <label class="form-label" for="password">Parol</label>
                                <input type="password" class="form-control" id="password"
                                       placeholder="Parol kiriting">
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                <label class="form-check-label" for="auth-remember-check">Eslab qolish</label>
                            </div>

                            <div class="mt-3 text-end">
                                <button class="btn btn-primary w-sm waves-effect waves-light" type="button"
                                        onclick="getUser()">Kirish
                                </button>
                            </div>
                            <div class="mt-4 text-center">
                                <p class="mb-0">Jismoniy shaxslar uchun <a href="http://reg.emehmon.uz"
                                                                           class="fw-medium text-primary">reg.emehmon.uz</a>
                                </p>
                            </div>
                        </div>
                        <div id="face" style="display: none;">
                            <input type="hidden" name="user_id" id="user_id">
                            <div style="position:absolute; visibility:hidden">
                                <input type="checkbox" id="webcam-switch">

                                <input type="checkbox" disabled id="detection-switch">

                                <input type="checkbox" disabled id="box-switch">

                                <input type="checkbox" disabled id="landmarks-switch">

                                <input type="checkbox" disabled id="expression-switch">

                                <input type="checkbox" disabled id="age-gender-switch">

                            </div>
                            <div class="align-top" id="webcam-container">
                                <div id="message" style="display:none" >Kuling</div>
                                <div class="loading d-none neutral">
                                    Loading Model
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>
                                <div class="loading d-none happy" style="display:none; text-align:center">
                                    Kuling
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>

                                <div class="loading d-none checking" style="display:none; text-align:center">
                                    Checking
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only"></span>
                                    </div>
                                </div>

                                <div id="video-container">
                                    <video id="webcam" autoplay muted playsinline>

                                    </video>
                                </div>

                                <input type="hidden" name="image[]" class="image-neutral">
                                <input type="hidden" name="image[]" class="image-happy">

                                <div id="errorMsg" class="col-12 alert-danger d-none">
                                    Fail to start camera <br>
                                    1. Please allow permission to access camera. <br>
                                    2. If you are browsing through social media built in browsers, look for the ... or browser icon on the right top/bottom corner, and open the page in Sarafi (iPhone)/ Chrome (Android)
                                </div>
                            </div>
                            <br/>
{{--                            <div class="mt-3 text-end">--}}
{{--                                <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">--}}
{{--                                    Kirish--}}
{{--                                </button>--}}
{{--                            </div>--}}
                        </div>

                    </form>
                </div>

            </div>
        </div>

        <div class="mt-5 text-center">
            <p>©
                <script>document.write(new Date().getFullYear())</script>
                EMEHMON
            </p>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="/assets/facedetection/jquery.facedetection.js"></script>
    <script>
        function getUser() {

            let email = $('#email').val();
            let password = $('#password').val();
            $.ajax({
                url: '/api/user',
                type: 'POST',
                data: {
                    email: email,
                    password: password,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        $('#user_id').val(response.user_id);
                        $('#login').hide();
                        $('#face').show();
                        startVideo();
                        //  face recognition
                        // faceRecognition();
                    } else {
                        alert('User not found');
                    }
                }
            });
        }


    </script>

    <script src="/assets/face-detection/js/face-detection.js"></script>

    <script>

        function startVideo(){
            $("#webcam-switch").prop('checked', true).change();
            setTimeout(function () {
                $("#detection-switch").prop('checked', true).change();
            }, 4000);
            setTimeout(function () {
                $("#expression-switch").prop('checked', true).change();
            }, 4000);
        }

        const video = document.getElementById('webcam');

        function takePic(attr) {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
            const data = canvas.toDataURL('image/png');
            console.log(data);
            document.querySelector('.image-'+attr).value = data;
        }

        function logIn(){
            $.ajax({
                url: 'http://127.0.0.1:8000/api/search/',
                type: 'POST',
                data: {
                    natural_face: $('.image-neutral').val(),
                    happy_face: $('.image-happy').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.status === true) {
                        window.location.href = `/test-form?user_id=${$('#user_id').val()}`;
                    } else {
                        alert('Face not recognized');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error: ' + error);
                    alert('The natural and happy faces do not match.');
                }
            });
        }

    </script>
@endsection
