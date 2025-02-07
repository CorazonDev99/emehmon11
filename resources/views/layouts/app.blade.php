<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>



    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .navbar-header {
            max-width: 100% !important;
        }
        .container-fluid {
            max-width: 100% !important;
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
            margin-left: 0.1rem;
        }

        .arrow-right {
            display: inline-block;
            margin-left: 8px;
            font-size: 12px;
            color: #000;
        }
        .arrow-right::after {
            content: "\F285";
            font-family: "bootstrap-icons";
            font-size: 12px;
            color: #393939;
            margin-left: 8px;
        }

        .dropdown-submenu:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu i{
            width: 1.5rem;
            text-align: center;
        }
        #page-topbar {
            background: #2F4050 !important;
        }
    </style>

    @yield('style')


    <!-- Scripts -->
</head>
{{--preloader--}}

{{--<body class="font-sans antialiased">--}}
<body data-layout="horizontal" data-topbar="colored">

<div id="layout-wrapper">


        @include('core.components.header')

{{--    @include('core.components.sidebar')--}}

    <div class="main-content">
        <div class="page-content">
            @yield('content')
        </div>
        @include('core.components.footer')
    </div>


</div>


@include('core.components.scripts')


</body>
</html>
