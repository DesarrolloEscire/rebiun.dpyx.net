<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Meta --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="This is a project about repositories.">
    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="shortcut icon" href="{{ asset('images/default/logo.png') }}" type='image/png' />

    {{-- Title --}}
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/app.css?20210529') }}">
    <link rel="stylesheet" href="{{ asset('css/kero.css') }}">

    <style>
        .app-sidebar {
            background: {{ config('dpyx.menu_color') }} !important;
        }

        .bg-info {
            background: {{ config('dpyx.dropdown_menu_header_background_color') }} !important;
        }

        .d-border-top {
            border-top: 5px solid {{ config('dpyx.border_top_color') }};
        }

        body {}

    </style>

    @livewireStyles

</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 no-gutters row">
                    <div
                        class="h-100 d-md-flex d-sm-block bg-white justify-content-center align-items-center col-md-12 col-lg-7">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            {{-- <h4>
                                <div>Bienvenido,</div>
                                <span>
                                    Solamente te tomará <span class="text-success">algunos segundos</span> crear tu
                                    cuenta
                                </span>
                            </h4> --}}
                            <div>
                                <form class="" action="{{ route('users.register.request') }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="exampleEmail"
                                                    class=""><span class="text-danger">*</span> Correo electrónico</label><input
                                                    name="email" id="exampleEmail" placeholder="correo@ejemplo.com"
                                                    type="email" class="form-control"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleName" class="">
                                                    <span class="text-danger">*</span>
                                                    Nombre del contacto responsable
                                                </label>
                                                <input name="name" id="exampleName" placeholder="Nombre completo"
                                                    type="text" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group"><label for="exampleName" class="">
                                                    <span class="text-danger">*</span> Nombre del repositorio
                                                </label><input name="repository_name" id="exampleName"
                                                    placeholder="Nombre de tu repositorio" type="text"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="examplePassword"
                                                    class=""><span class="text-danger">* </span>Contraseña</label>
                                                <input name="password" id="examplePassword" placeholder="Contraseña"
                                                    type="password" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="examplePasswordRep"
                                                    class=""><span class="text-danger">*</span>
                                                    Repetir contraseña
                                                </label>
                                                <input name="password_repeated" id="examplePasswordRep"
                                                    placeholder="Repetir contraseña" type="password"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 d-flex align-items-center">
                                        <h5 class="mb-0">¿Ya tienes una cuenta? <a href="{{ route('login') }}"
                                                class="text-primary">Inicia sesión</a></h5>
                                        <div class="ml-auto">
                                            <button class="btn-wide btn-pill btn-shadow btn btn-danger btn-lg">
                                                Solicitar cuenta
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="d-lg-flex d-xs-none col-lg-5">
                        <div class="slider-light">
                            <div class="slick-slider slick-initialized">
                                <div>
                                    <div class="h-100 d-flex justify-content-center align-items-center" tabindex="-1">
                                        <div class="slider-content">
                                            <img src="{{ asset('images/default/logo.jpg') }}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <svg id="SvgjsSvg1001" width="2" height="0" xmlns="http://www.w3.org/2000/svg" version="1.1"
        xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"
        style="overflow: hidden; top: -100%; left: -100%; position: absolute; opacity: 0;">
        <defs id="SvgjsDefs1002"></defs>
        <polyline id="SvgjsPolyline1003" points="0,0"></polyline>
        <path id="SvgjsPath1004" d="M0 0 "></path>
    </svg>
    <div class="jvectormap-tip"></div>

    @livewireScripts
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    @include('sweetalert::alert')

    @stack('modals')
    @stack('scripts')

</body>

</html>
