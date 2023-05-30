<div class="form-group form-group-last kt-hidden">
    <div class="alert alert-danger" role="alert" id="kt_form_1_msg">
        <div class="alert-text">
            {{ __('There are some invalid data, please correct it and try again.') }}
        </div>
    </div>
</div>

@foreach ($errors->all() as $error)
    <div class="alert alert-danger" role="alert">
        <div class="alert-text">{{ $error }}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endforeach

@if (session('status') || session('resent'))
    <div class="alert alert-success" role="alert">
        <div class="alert-text">
            {{ session('status') ?: __('A fresh verification link has been sent to your email address.') }}
        </div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endif
