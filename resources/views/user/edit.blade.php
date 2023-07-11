@extends('layouts.app')

@section('title')
    {{ __('Edit') }} {{ __('User') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('user.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('User') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('user.show', $user->id) }}"
        class="kt-subheader__breadcrumbs-link">{{ $user->email }}</a>
@endsection

@section('content')
    <form class="kt-form" id="kt_form_1" action="{{ route('user.update', $user->id) }}" method="POST">
        @method('PUT')
        @csrf

        <div class="kt-portlet" id="kt_page_portlet">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">{{ __('Edit') }} {{ __('User') }}</h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="{{ route('user.index') }}" class="btn btn-secondary kt-margin-r-10">
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
                                <label for="name">{{ __('Name') }}</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" required
                                    value="{{ old('name', $user->name) }}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="nik">ID</label>
                                <input id="nik" name="nik" type="text"
                                    class="form-control @error('nik') is-invalid @enderror" required
                                    value="{{ old('nik', $user->nik) }}">

                                @error('nik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="gender">{{ __('Gender') }}</label>
                                <select class="form-control kt_selectpicker @error('gender') is-invalid @enderror"
                                    id="gender" name="gender" data-live-search="true"
                                    title="{{ __('Choose') }} {{ __('Gender') }}">
                                    <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>
                                        {{ __('Male') }}
                                    </option>
                                    <option value="Female"
                                        {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>
                                        {{ __('Female') }}
                                    </option>
                                </select>

                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="religion">{{ __('Religion') }}</label>
                                <select class="form-control kt_selectpicker @error('religion') is-invalid @enderror"
                                    id="religion" name="religion" data-live-search="true"
                                    title="{{ __('Choose') }} {{ __('Religion') }}">
                                    <option value="Muslim"
                                        {{ old('religion', $user->religion) == 'Muslim' ? 'selected' : '' }}>
                                        {{ __('Muslim') }}
                                    </option>
                                    <option value="Christian"
                                        {{ old('religion', $user->religion) == 'Christian' ? 'selected' : '' }}>
                                        {{ __('Christian') }}
                                    </option>
                                    <option value="Hinduism"
                                        {{ old('religion', $user->religion) == 'Hinduism' ? 'selected' : '' }}>
                                        {{ __('Hinduism') }}
                                    </option>
                                    <option value="Buddhism"
                                        {{ old('religion', $user->religion) == 'Buddhism' ? 'selected' : '' }}>
                                        {{ __('Buddhism') }}
                                    </option>
                                    <option value="Confucianism"
                                        {{ old('religion', $user->religion) == 'Confucianism' ? 'selected' : '' }}>
                                        {{ __('Confucianism') }}
                                    </option>
                                    <option value="Other"
                                        {{ old('religion', $user->religion) == 'Other' ? 'selected' : '' }}>
                                        {{ __('Other') }}
                                    </option>
                                </select>

                                @error('religion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="role">{{ __('Role') }}</label>
                                <select id="role" name="role"
                                    class="form-control kt_selectpicker @error('role') is-invalid @enderror"
                                    data-live-search="true" title="{{ __('Choose') }} {{ __('Role') }}">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role', $userRole) == $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name }}</option>
                                    @endforeach
                                </select>

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="team_leader">{{ __('Team Leader') }}</label>
                                <input id="team_leader" name="team_leader" type="text"
                                    class="form-control @error('team_leader') is-invalid @enderror"
                                    value="{{ old('team_leader', $user->team_leader_name) }}">

                                @error('team_leader')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="supervisor">{{ __('Supervisor') }}</label>
                                <input id="supervisor" name="supervisor" type="text"
                                    class="form-control @error('supervisor') is-invalid @enderror"
                                    value="{{ old('supervisor', $user->supervisor_name) }}">

                                @error('supervisor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="city_id">{{ __('Site') }}</label>
                                <select id="city_id" name="city_id"
                                    class="form-control kt_selectpicker @error('city_id') is-invalid @enderror"
                                    data-live-search="true">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>
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
                                            {{ old('project_id', $user->project_id) == $project->id ? 'selected' : '' }}>
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
                                <label for="skill_id">{{ __('Skill') }}</label>
                                <select id="skill_id" name="skill_id"
                                    class="form-control kt_selectpicker @error('skill_id') is-invalid @enderror"
                                    data-live-search="true">
                                    @foreach ($skills as $skill)
                                        <option value="{{ $skill->id }}"
                                            {{ old('skill_id', $user->skill_id) == $skill->id ? 'selected' : '' }}>
                                            {{ $skill->name }}</option>
                                    @endforeach
                                </select>

                                @error('skill_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="join_date">{{ __('Join Date') }}</label>
                                <input id="join_date" name="join_date" type="text"
                                    class="form-control @error('join_date') is-invalid @enderror" required
                                    value="{{ old('join_date', $user->join_date) }}">

                                @error('join_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="initial_leave">{{ __('Initial Leave') }}</label>
                                <input id="initial_leave" name="initial_leave" type="number" min="0"
                                    class="form-control @error('initial_leave') is-invalid @enderror" required
                                    value="{{ old('initial_leave', $user->initial_leave) }}">

                                @error('initial_leave')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="used_leave">{{ __('Used Leave') }}</label>
                                <input id="used_leave" name="used_leave" type="number" min="0"
                                    class="form-control @error('used_leave') is-invalid @enderror" required
                                    value="{{ old('used_leave', $user->used_leave) }}">

                                @error('used_leave')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                        </div>

                        <div class="form-group">
                            <label for="aktif">{{ __('Active') }}</label><br>
                            <span class="kt-switch kt-switch--icon kt-switch--primary kt-switch--outline">
                                <label class="mb-0">
                                    <input id="aktif" name="aktif" type="checkbox"
                                        {{ old('aktif', $user->active) ? 'checked' : '' }}>
                                    <span class="m-0"></span>
                                </label>
                            </span>
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
        $('#join_date').datepicker({
            autoclose: true,
            clearBtn: true,
            disableTouchKeyboard: true,
            format: "yyyy-mm-dd",
            language: "{{ config('app.locale') }}",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            todayBtn: "linked",
            todayHighlight: true
        });
    </script>
@endsection
