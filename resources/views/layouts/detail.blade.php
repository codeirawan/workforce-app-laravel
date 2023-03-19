@extends('layouts.app')

@section('body')

    <body oncontextmenu="return true;">
        <div class="container-scroller">
            @include('layouts.inc.navbar')
            <div class="container-fluid page-body-wrapper">
                <div class="main-panel">
                    <div class="content-wrapper pb-1">
                        @yield('content')
                    </div>
                </div>
            </div>
            @include('landing-page.footer')
        </div>
        @include('layouts.app.script')
    </body>
@endsection
