@extends('layouts.app')

@section('title')
    {{ __('Create') }} {{ __('Shift') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.shift.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Shift') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.shift.create') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Create') }} {{ __('Shift') }}</a>
@endsection

@section('content')
    <form class="kt-form" id="kt_form_1" action="{{ route('master.shift.store') }}" method="POST">
        @csrf

        <div class="kt-portlet" id="kt_page_portlet">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ __('Create') }} {{ __('Shift') }}</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('master.shift.index') }}" class="btn btn-secondary kt-margin-r-10">
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
                            <div class="form-group col-md-4">
                                <label for="name">{{ __('Name') }}</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required autocomplete="off">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="start_time">{{ __('Start Time') }}</label>
                                <input id="start_time" name="start_time" type="text"
                                    class="form-control @error('start_time') is-invalid @enderror"
                                    placeholder="{{ __('Select') }} {{ __('Start Time') }}"
                                    value="{{ old('start_time') }}" required>

                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_time">{{ __('End Time') }}</label>
                                <input id="end_time" name="end_time" type="text"
                                    class="form-control @error('end_time') is-invalid @enderror"
                                    placeholder="{{ __('Select') }} {{ __('End Time') }}" value="{{ old('end_time') }}"
                                    required>

                                @error('end_time')
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
        $('#start_time').timepicker({
            defaultTime: '00:00:00',
            disableFocus: true,
            disableMousewheel: true,
            minuteStep: 1,
            secondStep: 1,
            showMeridian: false,
            showSeconds: true
        });
        $('#end_time').timepicker({
            defaultTime: '00:00:00',
            disableFocus: true,
            disableMousewheel: true,
            minuteStep: 1,
            secondStep: 1,
            showMeridian: false,
            showSeconds: true
        });
    </script>
@endsection
