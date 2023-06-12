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
                                <label for="nama">{{ __('Name') }}</label>
                                <input id="nama" name="nama" type="text"
                                    class="form-control @error('nama') is-invalid @enderror" required
                                    value="{{ old('nama', $user->name) }}">

                                @error('nama')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="wewenang">{{ __('Role') }}</label>
                                <select id="wewenang" name="wewenang"
                                    class="form-control kt_selectpicker @error('wewenang') is-invalid @enderror" required
                                    data-live-search="true" title="{{ __('Choose') }} {{ __('Role') }}">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('wewenang', $userRole) == $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name }}</option>
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
                                <input id="nik" name="nik" type="text"
                                    class="form-control @error('nik') is-invalid @enderror" required
                                    value="{{ old('nik', $user->nik) }}">

                                @error('nik')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
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
@endsection
