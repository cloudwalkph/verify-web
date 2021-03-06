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
    <link rel="stylesheet" href="{{ asset('fontawesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('jquery-ui-datepicker/jquery-ui.min.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/se-1.2.2/datatables.min.css"/>

    @yield('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/management') }}">
                        {{--{{ config('app.name', 'Laravel') }}--}}
                        <img src="{{ asset('images/v-logo.png') }}" alt="logo">
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav" style="margin-left: 5px;">
                        <li class="{{ Request::is('management') ? 'active' : '' }}"  ><a href="/management">Dashboard <span class="sr-only">(current)</span></a></li>
                        <li class="{{ Request::is('management/projects*') ? 'active' : '' }}"><a href="/management/projects">Projects</a></li>
                        <li class="{{ Request::is('management/users*') ? 'active' : '' }}"><a href="/management/users">Users Management</a></li>
                        <li class="hide {{ Request::is('management/maintenance*') ? 'active dropdown' : 'dropdown' }}" class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Maintenance <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Project Categories</a></li>
                                <li><a href="#">VBox Management</a></li>
                                <li><a href="#">User Roles</a></li>
                            </ul>
                        </li>
                        <li class="hide {{ Request::is('management/reports*') ? 'active' : '' }}"><a href="#">Reports</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->profile->full_name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="/management/profile">Profile</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="//cdn.datatables.net/v/bs/dt-1.10.15/r-2.1.1/se-1.2.2/datatables.min.js"></script>
    <script type="text/javascript" src="{{ asset('jquery-ui-datepicker/jquery-ui.min.js') }}"></script>

    <script>
        $(function() {
            $('.clickable').on('click', function() {
                location.href = $(this).data('uri');
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
