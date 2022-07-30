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
    <div class="app-container app-theme-gray app-sidebar-full {{ Cookie::get('expandNavbar') == 'false' ? 'header-mobile-open' : '' }}"
        id="app-container">
        <div class="app-main">
            <div class="app-sidebar-wrapper">
                <div class="app-sidebar sidebar-text-dark">
                    <div class="app-header__logo d-flex justify-content-between" x-data="navbar()" x-init="mounted()">
                        <a href="{{ route('dashboard') }}" data-toggle="tooltip" data-placement="bottom"
                            title="{{ env('APP_NAME', '') }}" class="">
                            <img src="{{ url('images/default/logo.jpg') }}" width="200px"
                                class="img-responsive" alt="">
                        </a>
                        <button type="button" class="float-right hamburger hamburger--elastic mobile-toggle-nav"
                            id="navbarButton" x-on:click="changeState()">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                    <div class="scrollbar-sidebar scrollbar-container">
                        <div class="app-sidebar__inner">
                            <ul class="vertical-nav-menu">
                                <li class="app-sidebar__heading">Opciones</li>

                                @can('index categories')
                                    <li>
                                        <a href="{{ route('categories.index') }}">
                                            <i class="metismenu-icon fas fa-layer-group">
                                            </i>Categorías
                                        </a>
                                    </li>
                                @endcan
                                @can('index subcategories')
                                    <li>
                                        <a href="{{ route('subcategories.index') }}">
                                            <i class="metismenu-icon fas fa-cubes">
                                            </i>Subcategorías
                                        </a>
                                    </li>
                                @endcan
                                @can('index users')
                                    <li>
                                        <a href="{{ route('users.index') }}">
                                            <i class="metismenu-icon fas fa-users">
                                            </i>Usuarios
                                        </a>
                                    </li>
                                @endcan
                                @can('index questions')
                                    <li>
                                        <a href="{{ route('questions.index') }}">
                                            <i class="metismenu-icon fas fa-question-circle">
                                            </i>Preguntas
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <a href="{{ route('repositories.index') }}">
                                        <i class="metismenu-icon fas fa-box-open"></i>
                                        {{ __('containerNamePlural') }}
                                    </a>
                                </li>
                                @if (config('app.is_evaluable'))
                                    <li>
                                        <a href="{{ route('announcements.index') }}">
                                            <i class="metismenu-icon fas fa-calendar-check">
                                            </i>Convocatorias
                                        </a>
                                    </li>
                                @endif
                                <li class="mt-5 text-center" style="border: 1px solid #c3c6d4 !important;">
                                    <a href="https://dpyx.site/preguntas-frecuentes/" target="_blank">
                                        Más información y FAQ
                                    </a>
                                </li>
                                @if (auth()->user()->is_admin)
                                    <li class="mt-1 text-center" style="border: 1px solid #c3c6d4 !important;">
                                        <a href="https://dpyx.site/administrador/" target="_blank">
                                            Manual de administrador
                                        </a>
                                    </li>
                                @elseif(auth()->user()->is_evaluator)
                                    <li class="mt-1 text-center" style="border: 1px solid #c3c6d4 !important;">
                                        <a href="https://dpyx.site/evaluador/" target="_blank">
                                            Manual de evaluador
                                        </a>
                                    </li>
                                @else
                                    <li class="mt-1 text-center" style="border: 1px solid #c3c6d4 !important;">
                                        <a href="https://dpyx.site/usuario/" target="_blank">
                                            Manual de usuario
                                        </a>
                                    </li>
                                @endif
                                <img src="{{ url('images/default/dpyx.png') }}" width="60px"
                                    class="float-right mt-2 img-responsive" alt="">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="app-sidebar-overlay d-none animated fadeIn"></div>
            <div class="container app-main__outer">
                <div class="container app-main__inner">
                    <div class="header-mobile-wrapper" style="background-color: #e1e6ff;">
                        <div class="app-header__logo">
                            <img src="{{ asset('images/default/logo.png') }}" width="120px" class="img-responsive" alt="">
                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-sidebar-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                            <div class="app-header__menu">
                                <span>
                                    <button type="button"
                                        class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav"
                                        style="background: #b6bad2; border: 1px solid #9da4ca;">
                                        <span class="btn-icon-wrapper">
                                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                                        </span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow-sm app-header">
                        @yield('header')
                        <div class="app-header-right">
                            <div class="pr-0 header-btn-lg">
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left">
                                            <div class="btn-group">
                                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    class="p-0 btn">
                                                    <img width="80" class="rounded"
                                                        src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : '/images/default/avatars/profile.jpg' }}"
                                                        alt="" style="display: inline-block;">
                                                    <i class="ml-2 fa fa-angle-down opacity-8"></i>
                                                </a>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                                    <div class="dropdown-menu-header">
                                                        <div class="dropdown-menu-header-inner bg-info">
                                                            <div class="menu-header-image opacity-2"
                                                                style="background-image: url('/images/dropdown-header/city1.jpg');">
                                                            </div>
                                                            <div class="text-left menu-header-content">
                                                                <div class="p-0 widget-content">
                                                                    <div class="widget-content-wrapper">
                                                                        <div class="mr-3 widget-content-left">
                                                                            <img width="80" class="rounded-circle"
                                                                                src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : '/images/default/avatars/profile.jpg' }}"
                                                                                alt="">
                                                                        </div>
                                                                        <div class="widget-content-left">
                                                                            <div class="widget-heading">
                                                                                {{ auth()->user()->name }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2 widget-content-right">
                                                                            <form method="POST"
                                                                                action="{{ route('logout') }}">
                                                                                @csrf
                                                                                <button
                                                                                    class="btn-pill btn-shadow btn-shine btn btn-focus">Cerrar
                                                                                    sesión
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="scroll-area-xs" style="height: 150px;">
                                                        <div class="scrollbar-container ps">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <a href="javascript:void(0);"
                                                                        class="nav-link float"><span class="text-muted">
                                                                            <i class="fas fa-envelope"></i>
                                                                            {{ auth()->user()->email }}
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item-header nav-item">Mi cuenta
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a href="{{ route('users.account', [auth()->user()]) }}"
                                                                        class="nav-link">
                                                                        <i class="mr-1 fas fa-tools"></i>
                                                                        Configuración
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app-header-overlay d-none animated fadeIn"></div>
                    </div>
                    <div class="app-inner-layout app-inner-layout-page">
                        <div class="app-inner-layout__wrapper">
                            <div class="app-inner-layout__sidebar">
                                <div class="app-layout__sidebar-inner dropdown-menu-rounded">
                                    <div class="nav flex-column">
                                        <div class="nav-item-header text-primary nav-item">
                                            Dashboards Examples
                                        </div>
                                        <a class="dropdown-item active" href="analytics-dashboard.html">Analytics</a>
                                        <a class="dropdown-item" href="management-dashboard.html">Management</a>
                                        <a class="dropdown-item" href="advertisement-dashboard.html">Advertisement</a>
                                        <a class="dropdown-item" href="index.html">Helpdesk</a>
                                        <a class="dropdown-item" href="monitoring-dashboard.html">Monitoring</a>
                                        <a class="dropdown-item" href="crypto-dashboard.html">Cryptocurrency</a>
                                        <a class="dropdown-item" href="pm-dashboard.html">Project Management</a>
                                        <a class="dropdown-item" href="product-dashboard.html">Product</a>
                                        <a class="dropdown-item" href="statistics-dashboard.html">Statistics</a>
                                    </div>
                                </div>
                            </div>
                            <div class="container app-inner-layout__content">
                                <div class="tab-content">
                                    <div class="container">
                                        {{ $slot }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="">
                            <div class="app-footer__inner">
                                <div class="app-footer-left">

                                    <a href="https://creativecommons.org/licenses/by/2.5/mx/" target="_blank">
                                        <img src="{{ asset('images/default/footer-left.png') }}" width="100px">
                                    </a>
                                </div>
                                <div class="app-footer-right">
                                    <span class="text-muted">Consultoría Tecnologías y Gestión del Conocimiento S.A. de
                                        C.V. | eScire</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="app-drawer-wrapper">
        <div class="drawer-nav-btn">
            <button type="button" class="hamburger hamburger--elastic is-active">
                <span class="hamburger-box"><span class="hamburger-inner"></span></span></button>
        </div>
        <div class="drawer-content-wrapper">
            <div class="scrollbar-container">
                <h3 class="drawer-heading">Servers Status</h3>
                <div class="drawer-section">
                    <div class="row">
                        <div class="col">
                            <div class="progress-box">
                                <h4>Server Load 1</h4>
                                <div class="mx-auto circle-progress circle-progress-gradient-xl">
                                    <small></small>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="progress-box">
                                <h4>Server Load 2</h4>
                                <div class="mx-auto circle-progress circle-progress-success-xl">
                                    <small></small>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="progress-box">
                                <h4>Server Load 3</h4>
                                <div class="mx-auto circle-progress circle-progress-danger-xl">
                                    <small></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <div class="mt-3">
                        <h5 class="text-center card-title">Live Statistics</h5>
                        <div id="sparkline-carousel-3"></div>
                        <div class="row">
                            <div class="col">
                                <div class="p-0 widget-chart">
                                    <div class="widget-chart-content">
                                        <div class="widget-numbers text-warning fsize-3">43</div>
                                        <div class="pt-1 widget-subheading">Packages</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-0 widget-chart">
                                    <div class="widget-chart-content">
                                        <div class="widget-numbers text-danger fsize-3">65</div>
                                        <div class="pt-1 widget-subheading">Dropped</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="p-0 widget-chart">
                                    <div class="widget-chart-content">
                                        <div class="widget-numbers text-success fsize-3">18</div>
                                        <div class="pt-1 widget-subheading">Invalid</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div class="mt-2 text-center d-block">
                            <button class="mr-2 border-0 btn-transition btn btn-outline-danger">Escalate Issue</button>
                            <button class="border-0 btn-transition btn btn-outline-success">Support Center</button>
                        </div>
                    </div>
                </div>
                <h3 class="drawer-heading">File Transfers</h3>
                <div class="p-0 drawer-section">
                    <div class="files-box">
                        <ul class="list-group list-group-flush">
                            <li class="pt-2 pb-2 pr-2 list-group-item">
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div
                                            class="mr-3 widget-content-left opacity-6 fsize-2 text-primary center-elem">
                                            <i class="fa fa-file-alt"></i>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading font-weight-normal">TPSReport.docx</div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <button class="btn-icon btn-icon-only btn btn-link btn-sm">
                                                <i class="fa fa-cloud-download-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="pt-2 pb-2 pr-2 list-group-item">
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div
                                            class="mr-3 widget-content-left opacity-6 fsize-2 text-warning center-elem">
                                            <i class="fa fa-file-archive"></i>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading font-weight-normal">Latest_photos.zip</div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <button class="btn-icon btn-icon-only btn btn-link btn-sm">
                                                <i class="fa fa-cloud-download-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="pt-2 pb-2 pr-2 list-group-item">
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="mr-3 widget-content-left opacity-6 fsize-2 text-danger center-elem">
                                            <i class="fa fa-file-pdf"></i>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading font-weight-normal">Annual Revenue.pdf</div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <button class="btn-icon btn-icon-only btn btn-link btn-sm">
                                                <i class="fa fa-cloud-download-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="pt-2 pb-2 pr-2 list-group-item">
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div
                                            class="mr-3 widget-content-left opacity-6 fsize-2 text-success center-elem">
                                            <i class="fa fa-file-excel"></i>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading font-weight-normal">Analytics_GrowthReport.xls
                                            </div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <button class="btn-icon btn-icon-only btn btn-link btn-sm">
                                                <i class="fa fa-cloud-download-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <h3 class="drawer-heading">Tasks in Progress</h3>
                <div class="p-0 drawer-section">
                    <div class="todo-box">
                        <ul class="todo-list-wrapper list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="todo-indicator bg-warning"></div>
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="mr-2 widget-content-left">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" id="exampleCustomCheckbox1266"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="exampleCustomCheckbox1266">&nbsp;</label>
                                            </div>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Wash the car
                                                <div class="ml-2 badge badge-danger">Rejected</div>
                                            </div>
                                            <div class="widget-subheading"><i>Written by Bob</i></div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <button class="border-0 btn-transition btn btn-outline-success">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button class="border-0 btn-transition btn btn-outline-danger">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="todo-indicator bg-focus"></div>
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="mr-2 widget-content-left">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" id="exampleCustomCheckbox1666"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="exampleCustomCheckbox1666">&nbsp;</label>
                                            </div>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Task with hover dropdown menu</div>
                                            <div class="widget-subheading">
                                                <div>By Johnny
                                                    <div class="ml-2 badge badge-pill badge-info">NEW</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <div class="d-inline-block dropdown">
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" class="border-0 btn-transition btn btn-link">
                                                    <i class="fa fa-ellipsis-h">
                                                    </i>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true"
                                                    class="dropdown-menu dropdown-menu-right">
                                                    <h6 tabindex="-1" class="dropdown-header">Header</h6>
                                                    <button type="button" disabled="" tabindex="-1"
                                                        class="disabled dropdown-item">Action</button>
                                                    <button type="button" tabindex="0" class="dropdown-item">Another
                                                        Action</button>
                                                    <div tabindex="-1" class="dropdown-divider"></div>
                                                    <button type="button" tabindex="0" class="dropdown-item">Another
                                                        Action</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="todo-indicator bg-primary"></div>
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="mr-2 widget-content-left">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" id="exampleCustomCheckbox4777"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="exampleCustomCheckbox4777">&nbsp;</label>
                                            </div>
                                        </div>
                                        <div class="widget-content-left flex2">
                                            <div class="widget-heading">Badge on the right task</div>
                                            <div class="widget-subheading">This task has show on hover actions!</div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <button class="border-0 btn-transition btn btn-outline-success">
                                                <i class="fa fa-check">
                                                </i>
                                            </button>
                                        </div>
                                        <div class="ml-3 widget-content-right">
                                            <div class="badge badge-pill badge-success">Latest Task</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="todo-indicator bg-info"></div>
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="mr-2 widget-content-left">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" id="exampleCustomCheckbox2444"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="exampleCustomCheckbox2444">&nbsp;</label>
                                            </div>
                                        </div>
                                        <div class="mr-3 widget-content-left">
                                            <div class="widget-content-left"></div>
                                        </div>
                                        <div class="widget-content-left">
                                            <div class="widget-heading">Go grocery shopping</div>
                                            <div class="widget-subheading">A short description ...</div>
                                        </div>
                                        <div class="widget-content-right widget-content-actions">
                                            <button class="border-0 btn-transition btn btn-sm btn-outline-success">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button class="border-0 btn-transition btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="todo-indicator bg-success"></div>
                                <div class="p-0 widget-content">
                                    <div class="widget-content-wrapper">
                                        <div class="mr-2 widget-content-left">
                                            <div class="custom-checkbox custom-control">
                                                <input type="checkbox" id="exampleCustomCheckbox3222"
                                                    class="custom-control-input">
                                                <label class="custom-control-label"
                                                    for="exampleCustomCheckbox3222">&nbsp;</label>
                                            </div>
                                        </div>
                                        <div class="widget-content-left flex2">
                                            <div class="widget-heading">Development Task</div>
                                            <div class="widget-subheading">Finish React ToDo List App</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <div class="mr-2 badge badge-warning">69</div>
                                        </div>
                                        <div class="widget-content-right">
                                            <button class="border-0 btn-transition btn btn-outline-success">
                                                <i class="fa fa-check">
                                                </i>
                                            </button>
                                            <button class="border-0 btn-transition btn btn-outline-danger">
                                                <i class="fa fa-trash-alt">
                                                </i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="app-drawer-overlay d-none animated fadeIn"></div>



    @livewireScripts
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

    <script>
        function navbar() {
            return {
                errors: @json($errors->all()),
                expandNavbar: JSON.parse(localStorage.getItem('expandNavbar')) ? true : false,
                appContainer: document.getElementById('app-container'),
                navbarButton: document.getElementById('navbarButton'),

                /**
                 *
                 *
                 *
                 */

                mounted() {
                    document.cookie = `expandNavbar=${this.expandNavbar}`;

                    if (this.errors.length > 0) {
                        this.showErrors();
                    }
                },

                /**
                 *
                 *
                 *
                 */

                changeState() {
                    this.expandNavbar = !this.expandNavbar;
                    localStorage.setItem('expandNavbar', this.expandNavbar);
                    document.cookie = `expandNavbar=${this.expandNavbar}`;
                },


                showErrors() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: this.errors[0],
                    })
                },

            }
        }

    </script>

    @include('sweetalert::alert')

    @stack('modals')
    @stack('scripts')

</body>

</html>
