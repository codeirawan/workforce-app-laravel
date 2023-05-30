@extends('layouts.app')

@section('title')
    {{ __('Role Details') }} | {{ config('app.name') }}
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
            <div class="kt-portlet" id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ __('Role Details') }}</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('role.index') }}" class="btn btn-secondary">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">{{ __('Back') }}</span>
                        </a>
                        @if (Laratrust::isAbleTo('update-role'))
                            <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary kt-margin-l-10">
                                <i class="la la-edit"></i>
                                <span class="kt-hidden-mobile">{{ __('Edit') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="kt-section__body">
                            <div class="form-group form-group-last">
                                <label>{{ __('Name') }}</label>
                                <input type="text" class="form-control" value="{{ $role->name }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                    <div class="kt-section">
                        <div class="kt-section__body">
                            <h3 class="kt-section__title kt-section__title-lg">{{ __('Permissions') }}</h3>

                            @foreach ($permissionGroups as $group => $permissions)
                                <h5 data-group="{{ $group }}">{{ __($group) }}</h5>
                                <table class="table">
                                    @foreach ($permissions as $i => $permission)
                                        <tr>
                                            <td class="w-100 align-middle">{{ __($permission->display_name) }}</td>
                                            <td class="align-middle">
                                                <span
                                                    class="kt-switch kt-switch--sm kt-switch--icon kt-switch--primary kt-switch--outline">
                                                    <label class="mb-0">
                                                        <input type="checkbox"
                                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                            disabled>
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
    <script type="text/javascript">
        $('.index').click(function() {
            index = $(this).data('index');
            jQuery("html, body").animate({
                scrollTop: $('[data-group="' + index + '"]').offset().top - 210
            }, "slow");
        });
    </script>
@endsection
