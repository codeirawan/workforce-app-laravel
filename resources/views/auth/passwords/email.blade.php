@extends('layouts.auth')

@section('title')
    {{ __('Forgot Password') }} | {{ config('app.name') }}
@endsection

@section('header')
    {{ __('Forgot Password') }}
@endsection

@section('content')
    @include('layouts.inc.alert')

    <div class="form-group">
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}">

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="kt-login-v2__actions">
        <button type="submit" class="btn btn-brand btn-elevate btn-pill w-100"
            id="kt_login_submit">{{ __('Send Password Reset Link') }}</button>
    </div>
@endsection
@section('image')
    <div class="kt-login-v2__image">
        <img src="{{ asset('images/auth/forgot-password.svg') }}" alt="">
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $('#kt_login_form').attr('action', "{{ route('password.email') }}");
    </script>
@endsection
