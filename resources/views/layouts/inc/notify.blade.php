<script src="{{ asset('js/vendor/jquery-3.5.1.min.js') }}"></script>

@if ($message = Session::get('success'))
    <script>
        $(".content").removeClass("active");
        $(".quiz").addClass("active");
        $("#content").removeClass("active");
        $("#content").removeClass("show");
        $("#quiz").addClass("active");
        $("#quiz").addClass("show");
    </script>
    <div class="alert alert-success" role="alert">
        <div class="alert-text">
            {{ $message }}
        </div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endif

@if ($message = Session::get('danger'))
    <script>
        $(".content").removeClass("active");
        $(".quiz").addClass("active");
        $("#content").removeClass("active");
        $("#content").removeClass("show");
        $("#quiz").addClass("active");
        $("#quiz").addClass("show");
    </script>
    <div class="alert alert-danger" role="alert">
        <div class="alert-text">
            {{ $message }}
        </div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endif
