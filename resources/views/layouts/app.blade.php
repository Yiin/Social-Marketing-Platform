<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.ico') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

    <title>{{ config('app.name', 'Social Marketing Platform') }}</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>

    <!-- Animation library for notifications   -->
    <link async href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet"/>

    <!-- Bootstrap core CSS     -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="{{ asset('assets/css/light-bootstrap-dashboard.css') }}" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link async href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="{{ asset('assets/css/pe-icon-7-stroke.css') }}" rel="stylesheet"/>

    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
            'apiToken' => $user->api_token
        ]) !!};
    </script>

</head>
<body>

<div class="wrapper" id="app">
    <div class="sidebar has-image" data-color="blue">

        <!--

            Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
            Tip 2: you can also add an image using data-image tag

        -->
        <div class="logo">
            <a href="{{ route('dashboard') }}" class="logo-text">
                {{ $user->name }}
            </a>
        </div>

        <div class="sidebar-wrapper">

            <ul class="nav">
                @foreach($navLinks as $link)
                    <li class="{{ \Route::currentRouteName() != $link->route ?: 'active' }}">
                        <a href="{{ route($link->route) }}">
                            <i class="{{ $link->icon }}"></i>
                            <p>{{ $link->title }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">{{ $currentPageTitle }}</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                        <li class="dropdown dropdown-with-icons">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-power-off"></i>
                                <p class="hidden-md hidden-lg">
                                    More
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <ul class="dropdown-menu dropdown-with-icons" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        <i class="pe-7s-close-circle"></i>
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @include('flash::message')
                    </div>
                </div>

                @yield('content')
            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    {!! Breadcrumbs::render(\Route::currentRouteName()) !!}
                </nav>
                <p class="copyright pull-right">
                    &copy; {{ date('Y') }} <a href="#">Social Marketing Platform</a>, all rights reserved
                </p>
            </div>
        </footer>

    </div>
</div>


</body>

<!--   Core JS Files   -->
<script src="{{ asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>

<!--  Select Picker Plugin -->
<script src="{{ asset('assets/js/bootstrap-selectpicker.js') }}"></script>

<!--  Checkbox, Radio & Switch Plugins -->
<script src="{{ asset('assets/js/bootstrap-checkbox-radio-switch.js') }}"></script>

<!--  Charts Plugin -->
<script src="{{ asset('assets/js/chartist.min.js') }}"></script>

<!-- Sweet Alert 2 plugin -->
<script src="{{ asset('assets/js/sweetalert2.js') }}"></script>

<!-- Scripts -->
<script src="{{ asset('assets/js/app.js') }}"></script>

</html>