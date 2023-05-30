@extends('layouts.app')

@section('title')
    {{ __('Verify Email Address') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Verify Email Address') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('verification.notice') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Verify Email Address') }}</a>
@endsection

@section('content')
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-portlet__content">
                @include('layouts.inc.alert')

                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email, click') }}

                <a href="#" onclick="$('#resend').submit();"><strong>{{ __('here') }}</strong></a>
                {{ __('to request another.') }}

                <form id="resend" class="d-none" method="POST" action="{{ route('verification.resend') }}">@csrf</form>
            </div>
        </div>
    </div>
@endsection
