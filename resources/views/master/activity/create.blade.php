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

                        <div class="form-group">
                            <label for="nama">{{ __('Name') }}</label>
                            <input id="nama" name="nama" type="text"
                                class="form-control @error('nama') is-invalid @enderror" required
                                value="{{ old('nama') }}">

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
