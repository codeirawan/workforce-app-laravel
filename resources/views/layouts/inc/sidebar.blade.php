<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
        <div class="kt-aside__brand-logo">
            <a href="{{ url('/') }}">
                <img alt="Logo" src="{{ asset('images/logo/brand.png') }}" width="155px" height="60px" />
            </a>
        </div>
        <div class="kt-aside__brand-tools">
            <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left"
                id="kt_aside_toggler"><span></span></button>
        </div>
    </div>

    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1"
            data-ktmenu-dropdown-timeout="500">
            <ul class="kt-menu__nav">
                <li class="kt-menu__item @if (Request::is('/dashboard')) kt-menu__item--here @endif"
                    aria-haspopup="true">
                    <a href="{{ route('dashboard') }}" class="kt-menu__link">
                        <i class="kt-menu__link-icon flaticon2-graphic"></i>
                        <span class="kt-menu__link-text">{{ __('Dashboard') }}</span>
                    </a>
                </li>

                @permission(['view-forecast'])
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">{{ __('Forecast Management') }}</h4>
                        <i class="kt-menu__section-icon flaticon-more-v2"></i>
                    </li>

                    <li class="kt-menu__item @if (Request::is('raw-data*')) kt-menu__item--here @endif"
                        aria-haspopup="true">
                        <a href="{{ route('raw-data.index') }}" class="kt-menu__link">
                            <i class="kt-menu__link-icon fa-solid fa-database"></i>
                            <span class="kt-menu__link-text">{{ __('Raw Data') }}</span>
                        </a>
                    </li>

                    <li class="kt-menu__item kt-menu__item--submenu @if (Request::is('history*')) kt-menu__item--open kt-menu__item--here @endif"
                        aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <i class="kt-menu__link-icon fa-solid fa-calendar-week"></i>
                            <span class="kt-menu__link-text">{{ __('History Data') }}</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                    <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">{{ __('History Data') }}</span>
                                    </span>
                                </li>
                                <li class="kt-menu__item @if (Request::is('history/daily*')) kt-menu__item--here @endif"
                                    aria-haspopup="true">
                                    <a href="{{ route('history.daily') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">{{ __('Daily') }}</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item @if (Request::is('history/weekly*')) kt-menu__item--here @endif"
                                    aria-haspopup="true">
                                    <a href="{{ route('history.weekly') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">{{ __('Weekly') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="kt-menu__item @if (Request::is('forecast*')) kt-menu__item--here @endif"
                        aria-haspopup="true">
                        <a href="{{ route('forecast.index') }}" class="kt-menu__link">
                            <i class="kt-menu__link-icon fa-solid fa-magnifying-glass-chart"></i>
                            <span class="kt-menu__link-text">{{ __('Forecast') }}</span>
                        </a>
                    </li>
                @endpermission

                @permission(['view-user', 'view-role'])
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">{{ __('User Management') }}</h4>
                        <i class="kt-menu__section-icon flaticon-more-v2"></i>
                    </li>
                @endpermission
                @permission('view-user')
                    <li class="kt-menu__item @if (Request::is('user*')) kt-menu__item--here @endif"
                        aria-haspopup="true">
                        <a href="{{ route('user.index') }}" class="kt-menu__link">
                            <i class="kt-menu__link-icon flaticon2-user"></i>
                            <span class="kt-menu__link-text">{{ __('User') }}</span>
                        </a>
                    </li>
                @endpermission
                @permission('view-role')
                    <li class="kt-menu__item @if (Request::is('role*')) kt-menu__item--here @endif"
                        aria-haspopup="true">
                        <a href="{{ route('role.index') }}" class="kt-menu__link">
                            <i class="kt-menu__link-icon flaticon2-user-1"></i>
                            <span class="kt-menu__link-text">{{ __('Role') }}</span>
                        </a>
                    </li>
                @endpermission

                @permission(['view-master'])
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">{{ __('Master Data Management') }}</h4>
                        <i class="kt-menu__section-icon flaticon-more-v2"></i>
                    </li>
                    <li class="kt-menu__item kt-menu__item--submenu @if (Request::is('master*')) kt-menu__item--open kt-menu__item--here @endif"
                        aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
                            <i class="kt-menu__link-icon flaticon2-layers-1"></i>
                            <span class="kt-menu__link-text">{{ __('Master Data') }}</span>
                            <i class="kt-menu__ver-arrow la la-angle-right"></i>
                        </a>
                        <div class="kt-menu__submenu">
                            <span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true">
                                    <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">{{ __('Master Data') }}</span>
                                    </span>
                                </li>
                                <li class="kt-menu__item @if (Request::is('master/project*')) kt-menu__item--here @endif"
                                    aria-haspopup="true">
                                    <a href="{{ route('master.project.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">{{ __('Project') }}</span>
                                    </a>
                                </li>
                                <li class="kt-menu__item @if (Request::is('master/skill*')) kt-menu__item--here @endif"
                                    aria-haspopup="true">
                                    <a href="{{ route('master.skill.index') }}" class="kt-menu__link">
                                        <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                                        <span class="kt-menu__link-text">{{ __('Skill') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endpermission
            </ul>
        </div>
    </div>
</div>
