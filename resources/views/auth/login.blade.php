@extends('layouts.auth')

@section('title')
    {{ __('Sign In') }} | {{ config('app.name') }}
@endsection

@section('header')
    {{ __('Sign In to Account') }}
@endsection

@section('content')
    <div class="form-group">
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}">

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required
            placeholder="{{ __('Password') }}">

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <span>
            <a href="{{ route('password.request') }}" class="kt-link kt-link--brand mt-2">{{ __('Forgot Password?') }}</a>
        </span>
    </div>

    <div class="form-group d-none">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
        </div>
    </div>

    <div class="kt-login-v2__actions">
        <button type="submit" class="btn btn-brand btn-elevate btn-pill" id="kt_login_submit">{{ __('Sign In') }}</button>
    </div>
@endsection
@section('image')
    <div class="kt-login-v2__image">
        <img src="{{ asset('images/auth/login.svg') }}" alt="Login">
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#kt_login_form').attr('action', "{{ route('login') }}");
    </script>
@endsection
