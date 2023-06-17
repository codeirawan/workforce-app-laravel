@extends('layouts.app')

@section('title')
    {{ __('Create') }} {{ __('National Holiday') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.national-holiday.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('National Holiday') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.national-holiday.create') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Create') }} {{ __('National Holiday') }}</a>
@endsection

@section('content')
    <form class="kt-form" id="kt_form_1" action="{{ route('master.national-holiday.store') }}" method="POST">
        @csrf

        <div class="kt-portlet" id="kt_page_portlet">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ __('Create') }} {{ __('National Holiday') }}</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('master.national-holiday.index') }}" class="btn btn-secondary kt-margin-r-10">
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
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="{{ __('National Holiday Name') }}" value="{{ old('name') }}" required
                                    autocomplete="off">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="date">{{ __('Date') }}</label>
                                <input id="date" name="date" type="text"
                                    class="form-control @error('date') is-invalid @enderror"
                                    placeholder="{{ __('Select') }} {{ __('Date') }}" value="{{ old('date') }}"
                                    required>

                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="religion">{{ __('Religion') }}</label>
                                <select class="form-control kt_selectpicker @error('religion') is-invalid @enderror"
                                    id="religion" name="religion" required data-live-search="true"
                                    title="{{ __('Choose') }} {{ __('Religion') }}">
                                    <option value="Muslim" {{ old('religion') == 'Muslim' ? 'selected' : '' }}>
                                        {{ __('Muslim') }}</option>
                                    <option value="Christian" {{ old('religion') == 'Christian' ? 'selected' : '' }}>
                                        {{ __('Christian') }}</option>
                                    <option value="Catholic" {{ old('religion') == 'Catholic' ? 'selected' : '' }}>
                                        {{ __('Catholic') }}</option>
                                    <option value="Hinduism" {{ old('religion') == 'Hinduism' ? 'selected' : '' }}>
                                        {{ __('Hinduism') }}</option>
                                    <option value="Buddhism" {{ old('religion') == 'Buddhism' ? 'selected' : '' }}>
                                        {{ __('Buddhism') }}</option>
                                    <option value="Confucianism" {{ old('religion') == 'Confucianism' ? 'selected' : '' }}>
                                        {{ __('Confucianism') }}</option>
                                    <option value="-" {{ old('religion') == '-' ? 'selected' : '' }}>
                                        {{ __('-') }}</option>
                                </select>
                                @error('religion')
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
    <script type="text/javascript">
        $('.kt_selectpicker').selectpicker({
            noneResultsText: "{{ __('No matching results for') }} {0}"
        });
    </script>
    <script>
        $('#date').datepicker({
            autoclose: true,
            clearBtn: true,
            disableTouchKeyboard: true,
            format: "yyyy-mm-dd",
            language: "{{ config('app.locale') }}",
            startDate: "1d",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            todayBtn: "linked",
            todayHighlight: true
        });
    </script>
@endsection
