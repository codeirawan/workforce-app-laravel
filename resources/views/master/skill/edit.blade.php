@extends('layouts.app')

@section('title')
    {{ __('Edit') }} {{ __('Skill') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.skill.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Skill') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.skill.edit', $skill->id) }}"
        class="kt-subheader__breadcrumbs-link">{{ $skill->name }}</a>
@endsection

@section('content')
    <form class="kt-form" id="kt_form_1" action="{{ route('master.skill.update', $skill->id) }}" method="POST">
        @method('PUT')
        @csrf

        <div class="kt-portlet" id="kt_page_portlet">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ __('Edit') }} {{ __('Skill') }}</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('master.skill.index') }}" class="btn btn-secondary kt-margin-r-10">
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
                                <label for="city_id">{{ __('Site') }}</label>
                                <select id="city_id" name="city_id"
                                    class="form-control kt_selectpicker @error('city_id') is-invalid @enderror"
                                    data-live-search="true">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id', $skill->city_id) == $city->id ? 'selected' : '' }}>
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
                                    class="form-control kt_selectpicker @error('project_id') is-invalid @enderror"
                                    data-live-search="true">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}"
                                            {{ old('project_id', $skill->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}</option>
                                    @endforeach
                                </select>

                                @error('project_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="name">{{ __('Skill Name') }}</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" required
                                    value="{{ old('name', $skill->name) }}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="kt-section">
                            <div class="kt-section__body">
                                <h5 class="kt-section__title kt-section__title-sm">{{ __('Select Shift') }}</h5>
                                <table class="table table-striped text-center">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th>Shift Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Checklist</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shifting as $i => $shift)
                                            <tr>
                                                <td>{{ __($shift->name) }}</td>
                                                <td>{{ __($shift->start_time) }}</td>
                                                <td>{{ __($shift->end_time) }}</td>
                                                <td>
                                                    <span
                                                        class="kt-switch kt-switch--sm kt-switch--icon kt-switch--primary kt-switch--outline">
                                                        <label class="mb-0">
                                                            <input type="checkbox" value="{{ $shift->id }}"
                                                                name="shift_skill[{{ $i }}]"
                                                                {{ old('shift_skill.' . $i) == $shift->id ? 'checked' : (in_array($shift->id, $shiftSkills) ? 'checked' : '') }}>
                                                            <span class="m-0"></span>
                                                        </label>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
