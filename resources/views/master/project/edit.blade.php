@extends('layouts.admin')

@section('title')
    {{ __('Edit') }} {{ __('Project') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Edit') }} {{ __('Project') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.project.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('Project') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('master.project.edit', $project->id) }}" class="kt-subheader__breadcrumbs-link">{{ $project->name }}</a>
@endsection

@section('content')
<form class="kt-form" id="kt_form_1" action="{{ route('master.project.update', $project->id) }}" method="POST">
    @method('PUT')
    @csrf

    <div class="kt-portlet" id="kt_page_portlet">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">{{ __('Edit') }} {{ __('Project') }}</h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <a href="{{ route('master.project.index') }}" class="btn btn-secondary kt-margin-r-10">
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

                    <div class="form-group">
                        <label for="nama">{{ __('Name') }}</label>
                        <input id="nama" name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama', $project->name) }}">

                        @error('nama')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
@endsection