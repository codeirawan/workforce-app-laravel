@extends('layouts.app')

@section('title')
    {{ __('Create') }} {{ __('Activity') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.activity.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Activity') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.activity.create') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Create') }} {{ __('Activity') }}</a>
@endsection

@section('content')
    <form class="kt-form" id="kt_form_1" action="{{ route('master.activity.store') }}" method="POST">
        @csrf

        <div class="kt-portlet" id="kt_page_portlet">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ __('Create') }} {{ __('Activity') }}</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('master.activity.index') }}" class="btn btn-secondary kt-margin-r-10">
                        <i class="la la-arrow-left"></i>
                        <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-check"></i>
                        <span class="kt-hidden-mobile">{{ __('Save') }}</span>
                    </button>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
                        @include('layouts.inc.alert')
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="name">{{ __('Name') }}</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" required
                                    value="{{ old('name') }}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="duration">{{ __('Time') }}</label>
                                <input type="text" class="form-control @error('duration') is-invalid @enderror"
                                    name="duration" id="duration" placeholder="{{ __('Select') }} {{ __('Time') }}"
                                    readonly value="{{ old('duration') }}" required>

                                @error('duration')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="color">{{ __('Color') }}</label>
                                <input type="color" class="form-control @error('color') is-invalid @enderror"
                                    name="color" id="color" placeholder="{{ __('Select') }} {{ __('Color') }}"
                                    readonly value="{{ old('color') }}" required>

                                @error('color')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
    <script>
        $('#duration').timepicker({
            disableFocus: true,
            disableMousewheel: true,
            minuteStep: 1,
            showMeridian: false,
            defaultTime: '00:00',
        });
    </script>
@endsection
