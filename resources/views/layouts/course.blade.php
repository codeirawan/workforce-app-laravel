@extends('layouts.app')

@section('body ')

    <body oncontextmenu="return true;">
        <div class="container-scroller">
            @include('layouts.inc.navbar')
            <div class="container-fluid page-body-wrapper">
                @include('layouts.inc.sidebar-course')
                <div class="main-panel">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.app.script')
    </body>
@endsection
