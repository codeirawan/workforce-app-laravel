@extends('layouts.app')

@section('title')
    {{ __('Edit') }} {{ __('Raw Data') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ route('raw-data.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Raw Data') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="#" class="kt-subheader__breadcrumbs-link">
        Date : {{ \Carbon\Carbon::parse($rawData->date)->format('d M Y') }} | Interval :
        {{ \Carbon\Carbon::parse($rawData->start_time)->format('H:i:s') }} -
        {{ \Carbon\Carbon::parse($rawData->end_time)->format('H:i:s') }}</a>
@endsection

@section('content')
    <form class="kt-form" id="kt_form_1" action="{{ route('raw-data.update', $rawData->id) }}" method="POST">
        @method('PUT')
        @csrf

        <div class="kt-portlet" id="kt_page_portlet">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ __('Edit') }} {{ __('Raw Data') }}</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('raw-data.index') }}" class="btn btn-secondary kt-margin-r-10">
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
                            <div class="form-group col-sm-3">
                                <label for="date">{{ __('Date') }}</label>
                                <input id="date" name="date" type="date"
                                    class="form-control @error('date') is-invalid @enderror" required
                                    value="{{ old('date', $rawData->date) }}">

                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="start_time">{{ __('Start Time') }}</label>
                                <input id="start_time" name="start_time" type="text"
                                    class="form-control @error('start_time') is-invalid @enderror" required
                                    value="{{ old('start_time', $rawData->start_time) }}">

                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="end_time">{{ __('End Time') }}</label>
                                <input id="end_time" name="end_time" type="text"
                                    class="form-control @error('end_time') is-invalid @enderror" required
                                    value="{{ old('end_time', $rawData->end_time) }}">

                                @error('end_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="volume">{{ __('Volume') }}</label>
                                <input id="volume" name="volume" type="number" min="0"
                                    class="form-control @error('volume') is-invalid @enderror" required
                                    value="{{ old('volume', $rawData->volume) }}">

                                @error('volume')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="city_id">{{ __('Site') }}</label>
                                <select id="city_id" name="city_id"
                                    class="form-control kt_selectpicker @error('city_id') is-invalid @enderror" required
                                    data-live-search="true" title="{{ __('Choose') }} {{ __('Site') }}">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id', $rawData->city_id) == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}</option>
                                    @endforeach
                                </select>

                                @error('city_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="project_id">{{ __('Project') }}</label>
                                <select id="project_id" name="project_id"
                                    class="form-control kt_selectpicker @error('project_id') is-invalid @enderror" required
                                    data-live-search="true" title="{{ __('Choose') }} {{ __('Project') }}">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('project_id', $rawData->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="skill_id">{{ __('Skill') }}</label>
                                <select id="skill_id" name="skill_id"
                                    class="form-control kt_selectpicker @error('skill_id') is-invalid @enderror" required
                                    data-live-search="true" title="{{ __('Choose') }} {{ __('Skill') }}">
                                    @foreach ($skills as $skill)
                                        <option value="{{ $skill->id }}"
                                            {{ old('skill_id', $rawData->skill_id) == $skill->id ? 'selected' : '' }}>
                                            {{ $skill->name }}</option>
                                    @endforeach
                                </select>

                                @error('skill_id')
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
@endsection
