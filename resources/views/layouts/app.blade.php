@extends('layouts.master-app')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/fontawesome/css/all.min.css') }}">
@endsection

@section('body')

    <body oncontextmenu="return true;">
        <div class="container-scroller">
            @include('layouts.inc.navbar')
            <div class="container-fluid page-body-wrapper">
                <div class="main-panel">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                    @yield('footer')
                </div>
            </div>
        </div>
        @include('layouts.app.script')
    </body>
@endsection
