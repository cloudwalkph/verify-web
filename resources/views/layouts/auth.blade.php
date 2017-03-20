<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">

    <style>
        body {
            background-color: #383A38;
        }

        #app {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .flex {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -moz-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            height: 100%;
            flex-direction: column;
            justify-content: center;
            margin-top: 200px;
        }

        .logo-big {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            -moz-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%;
            align-self: center;
        }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
<div id="app">
    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
