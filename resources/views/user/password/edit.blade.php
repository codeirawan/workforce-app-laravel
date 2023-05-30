@extends('layouts.app')

@section('title')
    {{ __('Change Password') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Change Password') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('password.edit') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Change Password') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <form class="kt-form" id="kt_form_1" action="{{ route('password.store') }}" method="POST">
            @csrf

            <div class="kt-portlet__body">
                @include('layouts.inc.alert')

                <div class="form-group">
                    <label for="kata_sandi_lama">{{ __('Current Password') }}</label>
                    <input id="kata_sandi_lama" name="kata_sandi_lama" type="password"
                        class="form-control @error('kata_sandi_lama') is-invalid @enderror" required>

                    @error('kata_sandi_lama')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kata_sandi_baru">{{ __('New Password') }}</label>
                    <input id="kata_sandi_baru" name="kata_sandi_baru" type="password"
                        class="form-control @error('kata_sandi_baru') is-invalid @enderror" required minlength="8">

                    @error('kata_sandi_baru')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group form-group-last">
                    <label for="kata_sandi_baru_confirmation">{{ __('New Password Confirmation') }}</label>
                    <input id="kata_sandi_baru_confirmation" name="kata_sandi_baru_confirmation" type="password"
                        class="form-control" required minlength="8">
                </div>
            </div>

            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    <a onclick="window.history.back();" class="btn btn-secondary kt-margin-l-10">
                        <span class="kt-hidden-mobile">{{ __('Cancel') }}</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('image')
    <div class="kt-login-v2__image">
        <img src="{{ asset('images/auth/change-password.svg') }}" alt="">
    </div>
@endsection

@section('script')
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
@endsection
