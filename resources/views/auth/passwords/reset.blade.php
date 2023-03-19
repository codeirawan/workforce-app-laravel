@extends('layouts.auth')

@section('title')
    {{ __('Reset Password') }} | {{ config('app.name') }}
@endsection

@section('header')
    {{ __('Reset Password') }}
@endsection

@section('content')
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="{{ $email ?? old('email') }}" required autocomplete="email" placeholder="{{ __('Email Address') }}">

        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required
            autocomplete="new-password" placeholder="{{ __('Password') }}" minlength="8">

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
        <button type="submit" class="btn btn-brand btn-elevate btn-pill w-100"
            id="kt_login_submit">{{ __('Reset Password') }}</button>
    </div>
@endsection
@section('image')
    <div class="kt-login-v2__image">
    <img src="{{ asset('images/auth/reset-password.svg') }}" alt="">
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#kt_login_form').attr('action', "{{ route('password.update') }}");
    </script>
@endsection
