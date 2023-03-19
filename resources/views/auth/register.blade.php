@extends('layouts.auth')

@section('title')
    {{ __('Sign Up') }} | {{ config('app.name') }}
@endsection

@section('header')
    {{ __('Register Account') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                value="{{ old('name') }}" required autocomplete="name" placeholder="{{ __('Full Name') }}" autofocus>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
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
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password_confirmation" required autocomplete="new-password"
                placeholder="{{ __('Confirm Password') }}" minlength="8">
        </div>

        <div class="kt-login-v2__actions">
            <button type="submit" class="btn btn-brand btn-elevate btn-pill"
                id="kt_login_submit">{{ __('Sign Up') }}</button>

            <a href="{{ route('login') }}" class="kt-link kt-link--brand">{{ __('Already have an account?') }}
                {{ __('Login here!') }}
            </a>
        </div>
    </form>
@endsection
@section('image')
    <div class="kt-login-v2__image">
        <img src="{{ asset('images/auth/register.svg') }}" alt="Login">
    </div>
@endsection