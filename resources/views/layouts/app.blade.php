@extends('layouts.master')

@section('body')

    <body
        class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
        <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
            <div class="kt-header-mobile__logo">
                <a href="{{ url('/') }}">
                    <img alt="Logo" src="{{ asset('images/logo/brand.png') }}" width="104px" height="40px" />
                </a>
            </div>
            <div class="kt-header-mobile__toolbar">
                <button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left"
                    id="kt_aside_mobile_toggler"><span></span></button>
                <button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                        class="flaticon-more"></i></button>
            </div>
        </div>

        <div class="kt-grid kt-grid--hor kt-grid--root">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                <button class="kt-aside-close" id="kt_aside_close_btn"><i class="la la-close"></i></button>

                @include('layouts.inc.sidebar')

                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    @include('layouts.inc.topbar')
                    @include('layouts.inc.content')

                    <div class=" kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop">
                        <div class="kt-container  kt-container--fluid ">
                            <div class="kt-footer__copyright justify-content-end">
                                {{ date('Y') }}&nbsp;&copy;&nbsp;<a href="https://www.linkedin.com/in/codeirawan/"
                                    class="kt-link">made from scratch by codeirawan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="kt_scrolltop" class="kt-scrolltop">
            <i class="la la-arrow-up"></i>
        </div>

        @include('layouts.inc.script')
    </body>
@endsection
