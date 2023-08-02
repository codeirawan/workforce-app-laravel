@extends('layouts.app')

@section('title')
    {{ __('Edit') }} {{ __('Raw Data') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('forecast.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Forecast') }}</a>
@endsection

@section('content')
    <form class="kt-form" id="kt_form_1" action="{{ route('forecast.update', $params->id) }}" method="POST">
        @method('PUT')
        @csrf

        <div class="kt-portlet" id="kt_page_portlet">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ __('Edit') }} {{ __('Params') }}</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('forecast.index') }}" class="btn btn-secondary kt-margin-r-10">
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
                            <div class="form-group col-sm-6">
                                <label for="skill_id">{{ __('Skill') }}</label>
                                <input type="text" class="form-control @error('skill_id') is-invalid @enderror"
                                    name="skill_id" id="skill_id" placeholder="{{ __('Select') }} {{ __('Date') }}"
                                    disabled
                                    value="{{ old('skill_id', $skill->skill . ' - ' . $skill->project . ' ' . $skill->site) }}">


                                @error('skill_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="start_date">{{ __('Start Date') }}</label>
                                <input type="text" class="form-control @error('start_date') is-invalid @enderror"
                                    name="start_date" id="start_date"
                                    placeholder="{{ __('Select') }} {{ __('Date') }}" readonly
                                    value="{{ old('start_date', $params->start_date) }}" required>

                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="end_date">{{ __('End Date') }}</label>
                                <input type="text" class="form-control @error('end_date') is-invalid @enderror"
                                    name="end_date" id="end_date" placeholder="{{ __('Select') }} {{ __('Date') }}"
                                    readonly value="{{ old('end_date', $params->end_date) }}" required>

                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="week">{{ __('Week') }}</label>
                                <select class="form-control kt_selectpicker @error('week') is-invalid @enderror"
                                    id="week" name="week" data-live-search="true">
                                    <option value="1" {{ old('week', $params->week) == '1' ? 'selected' : '' }}>
                                        {{ __('Week 1') }}</option>
                                    <option value="2" {{ old('week', $params->week) == '2' ? 'selected' : '' }}>
                                        {{ __('Week 2') }}</option>
                                    <option value="3" {{ old('week', $params->week) == '3' ? 'selected' : '' }}>
                                        {{ __('Week 3') }}</option>
                                    <option value="4" {{ old('week', $params->week) == '4' ? 'selected' : '' }}>
                                        {{ __('Week 4') }}</option>
                                    <option value="5" {{ old('week', $params->week) == '5' ? 'selected' : '' }}>
                                        {{ __('Week 5') }}</option>
                                </select>

                                @error('week')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="avg_handling_time">{{ __('Avg Handling Time') }}</label>
                                <input type="number" class="form-control" min="0" id="avg_handling_time"
                                    name="avg_handling_time" autocomplete="off"
                                    value="{{ old('avg_handling_time', $params->avg_handling_time) }}" required>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="reporting_period">{{ __('Reporting Period') }}</label>
                                <select class="form-control kt_selectpicker @error('reporting_period') is-invalid @enderror"
                                    id="reporting_period" name="reporting_period" data-live-search="true">
                                    <option value="3600"
                                        {{ old('reporting_period', $params->reporting_period) == '3600' ? 'selected' : '' }}>
                                        {{ __('3600') }}</option>
                                    <option value="1800"
                                        {{ old('reporting_period', $params->reporting_period) == '1800' ? 'selected' : '' }}>
                                        {{ __('1800') }}</option>
                                    <option value="900"
                                        {{ old('reporting_period', $params->reporting_period) == '900' ? 'selected' : '' }}>
                                        {{ __('900') }}</option>
                                </select>

                                @error('reporting_period')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="service_level">{{ __('Service Level') }}</label>
                                <input type="number" class="form-control" min="0" id="service_level"
                                    name="service_level" autocomplete="off"
                                    value="{{ old('service_level', $params->service_level) }}" required>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="target_answer_time">{{ __('Target Answer Time') }}</label>
                                <input type="number" class="form-control" min="0" id="target_answer_time"
                                    name="target_answer_time" autocomplete="off"
                                    value="{{ old('target_answer_time', $params->target_answer_time) }}" required>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="shrinkage">{{ __('Shrinkage') }}</label>
                                <input type="number" class="form-control" min="0" id="shrinkage"
                                    name="shrinkage" autocomplete="off"
                                    value="{{ old('shrinkage', $params->shrinkage) }}" required>
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
        $('#start_date').datepicker({
            autoclose: true,
            clearBtn: true,
            disableTouchKeyboard: true,
            format: "yyyy-mm-dd",
            language: "{{ config('app.locale') }}",
            startDate: "0d",
            weekStart: 1,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            todayBtn: "linked",
            todayHighlight: true
        });
        $('#end_date').datepicker({
            autoclose: true,
            clearBtn: true,
            disableTouchKeyboard: true,
            format: "yyyy-mm-dd",
            language: "{{ config('app.locale') }}",
            startDate: "0d",
            weekStart: 1,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            todayBtn: "linked",
            todayHighlight: true
        });
    </script>
@endsection
