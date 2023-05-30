@extends('layouts.master')

@section('title')
    404 | {{ config('app.name') }}
@endsection

@section('style')
    <link href="{{ asset(mix('css/404.css')) }}" rel="stylesheet">
@endsection

@section('body')

    <body
        class="kt-bg-error404-v3 kt-error404-v3--enabled kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">
        <div class="kt-grid kt-grid--ver kt-grid--root">
            <div class="kt-error404-v3">
                <div class="kt-error404-v3__content">
                    <div class="kt-error404-v3__title">{{ __('Page Not Found') }}</div>
                    <a href="#" class="kt-error404-v3__button btn btn-pill btn-glow btn-lg btn-widest"
                        onclick="window.history.back();">{{ __('Return Back') }}</a>
                </div>
            </div>
        </div>

        @include('layouts.inc.script')
    </body>
@endsection
