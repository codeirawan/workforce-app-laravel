@extends('layouts.admin')

@section('title')
    {{ __('Create') }} {{ __('User') }} | {{ config('app.name') }}
@endsection

@section('subheader')
    {{ __('Create') }} {{ __('User') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('user.index') }}" class="kt-subheader__breadcrumbs-link">{{ __('User') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('user.create') }}" class="kt-subheader__breadcrumbs-link">{{ __('Create') }} {{ __('User') }}</a>
@endsection

@section('content')
<form class="kt-form" id="kt_form_1" action="{{ route('user.store') }}" method="POST">
    @csrf

    <div class="kt-portlet" id="kt_page_portlet">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">{{ __('Create') }} {{ __('User') }}</h3>
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
                            <label for="nama">{{ __('Name') }}</label>
                            <input id="nama" name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" required value="{{ old('nama') }}">

                            @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="wewenang">{{ __('Role') }}</label>
                            <select id="wewenang" name="wewenang" class="form-control kt_selectpicker @error('wewenang') is-invalid @enderror" required data-live-search="true" title="{{ __('Choose') }} {{ __('Role') }}">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('wewenang') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                @endforeach
                            </select> 

                            @error('wewenang')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="nik">NIK</label>
                            <input id="nik" name="nik" type="text" class="form-control @error('nik') is-invalid @enderror" required value="{{ old('nik') }}">

                            @error('nik')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="email">Email</label>

                            <div class="input-group">
                                <input id="email" name="email" type="text" class="form-control @error('email') is-invalid @enderror" required value="{{ old('email') }}">
                            </div>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="kata_sandi">{{ __('Password') }}</label>
                            <input id="kata_sandi" name="kata_sandi" type="password" class="form-control @error('kata_sandi') is-invalid @enderror" required minlength="8">

                            @error('kata_sandi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="kata_sandi_confirmation">{{ __('Confirm Password') }}</label>
                            <input id="kata_sandi_confirmation" name="kata_sandi_confirmation" type="password" class="form-control" required minlength="8">
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