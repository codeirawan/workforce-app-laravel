@extends('layouts.app')

@section('title')
    {{ __('Edit') }} {{ __('Role') }} | {{ config('app.name') }}
@endsection

@section('breadcrumb')
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('role.index') }}"
        class="kt-subheader__breadcrumbs-link">{{ __('Role') }}</a>
    <span class="kt-subheader__breadcrumbs-separator"></span><a href="{{ route('role.show', $role->id) }}"
        class="kt-subheader__breadcrumbs-link">{{ $role->display_name }}</a>
@endsection

@section('content')
    <div class="row" data-sticky-container>
        <div class="col-lg-9">
            <form class="kt-form" id="kt_form_1" action="{{ route('role.update', $role->id) }}" method="POST">
                @method('PUT')
                @csrf

                <div class="kt-portlet" id="kt_page_portlet">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('Edit') }} {{ __('Role') }}</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <a href="{{ route('role.index') }}" class="btn btn-secondary kt-margin-r-10">
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

                                <div class="form-group form-group-last">
                                    <label for="nama">{{ __('Name') }}</label>
                                    <input id="nama" name="nama" type="text"
                                        class="form-control @error('nama') is-invalid @enderror" required
                                        placeholder="{{ __('role_name') }}" value="{{ old('nama', $role->name) }}">

                                    @error('nama')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <div class="kt-section">
                            <div class="kt-section__body">
                                <h3 class="kt-section__title kt-section__title-lg">{{ __('Permissions') }}</h3>

                                @foreach ($permissionGroups as $group => $permissions)
                                    <h5>
                                        {{ __($group) }}
                                        <div class="pull-right" style="padding-right: 9px;">
                                            <span
                                                class="kt-switch kt-switch--sm kt-switch--icon kt-switch--primary kt-switch--outline">
                                                <label>
                                                    <input type="checkbox" class="check-all"
                                                        data-group="{{ $group }}">
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </h5>
                                    <table class="table">
                                        @foreach ($permissions as $i => $permission)
                                            <tr>
                                                <td class="w-100 align-middle">{{ __($permission->display_name) }}</td>
                                                <td class="align-middle">
                                                    <span
                                                        class="kt-switch kt-switch--sm kt-switch--icon kt-switch--primary kt-switch--outline">
                                                        <label class="mb-0">
                                                            <input type="checkbox" value="{{ $permission->id }}"
                                                                data-group="{{ $group }}"
                                                                name="hak_akses[{{ $group }}][{{ $i }}]"
                                                                {{ old('hak_akses.' . $group . '.' . $i) == $permission->id ? 'checked' : (in_array($permission->id, $rolePermissions) ? 'checked' : '') }}>
                                                            <span class="m-0"></span>
                                                        </label>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-3">
            <div class="kt-portlet sticky" data-sticky="true" data-margin-top="130" data-sticky-for="1023"
                data-sticky-class="kt-sticky">
                <div class="kt-portlet__body kt-portlet__body--fit">
                    <div data-scroll="true" class="kt-scroll" style="height: calc(100vh - 130px); overflow: hidden;">
                        <div class="kt-portlet__content">
                            <ul class="kt-nav kt-nav--bold kt-nav--md-space kt-nav--v3 kt-margin-t-20 kt-margin-b-20 nav nav-tabs"
                                role="tablist">
                                @foreach ($permissionGroups as $group => $permissions)
                                    <li class="kt-nav__item">
                                        <a class="kt-nav__link active index" href="#{{ str_replace(' ', '-', $group) }}"
                                            data-index="{{ $group }}">
                                            <span class="kt-nav__link-text">{{ __($group) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset(mix('js/form/validation.js')) }}"></script>
    <script type="text/javascript">
        $('.check-all').change(function() {
            var group = $(this).data('group');

            if ($(this).is(':checked'))
                $('[data-group="' + group + '"]').attr('checked', '');
            else
                $('[data-group="' + group + '"]').removeAttr('checked');
        });
        $('.index').click(function() {
            index = $(this).data('index');
            jQuery("html, body").animate({
                scrollTop: $('[data-group="' + index + '"]').offset().top - 210
            }, "slow");
        });
    </script>
@endsection
