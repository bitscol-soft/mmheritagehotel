@php
    $banner     =  'banners.index';
    $feature    =  'features.index';
    $galleries  =  'galleries.index';
    $about      =  'aboutsections.index';
    $privacy    =  'privacypoilicies.create';
    $setting    =  'websitesettings.index';
@endphp

@if (hasAnyPermission([$banner, $feature, $galleries, $about, $privacy, $setting ], $slugs))
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fas fa-globe"></i>
            <span class="menu-text">Website CMS</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">

            <!-- New Sale -->
            @if (hasPermission('banners.index', $slugs))

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Homepage Banner
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('banners.create', $slugs))
                            <li>
                                <a href="{{ route('website-core.banner.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create Banner
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('website-core.banner.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                List Banner
                            </a>
                        </li>
                    </ul>
                </li>
            @endif


            @if (hasPermission('features.index', $slugs))

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Homepage Feature
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('features.create', $slugs))
                            <li>
                                <a href="{{ route('website-core.feature.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Feature Heading
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('features.create', $slugs))
                        <li>
                            <a href="{{ route('website-core.feature_list.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Create Feature
                            </a>
                        </li>
                        @endif

                        <li>
                            <a href="{{ route('website-core.feature_list.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                List Feature
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (hasPermission('features.create', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Our Service
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('features.create', $slugs))
                            <li>
                                <a href="{{ route('website-core.our_service.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Service Heading
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('features.create', $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Service Listting
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <ul class="submenu">
                                    @if (hasPermission('features.create', $slugs))
                                        <li>
                                            <a href="{{ route('website-core.our_service_list.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                        </li>
                                    @endif

                                    @if (hasPermission('features.create', $slugs))
                                        <li>
                                            <a href="{{ route('website-core.our_service_list.index') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                List
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (hasPermission('galleries.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Hotel Gallery
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('galleries.create', $slugs))
                            <li>
                                <a href="{{ route('website-core.gallery.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('website-core.gallery.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                List
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (hasPermission('aboutsections.index', $slugs))
                <li>
                    <a href="{{ route('website-core.about_section.create') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        About Section
                    </a>
                </li>
            @endif

            @if (hasPermission('privacypoilicies.create', $slugs))
                <li>
                    <a href="{{ route('website-core.privacy_poilicy.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Privacy & Policy
                    </a>
                </li>
            @endif
            {{-- @if (hasPermission('privacypoilicies.page', $slugs)) --}}
                <li>
                    <a href="{{ route('website-core.pages.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Pages
                    </a>
                </li>
            {{-- @endif --}}

            @if (hasPermission('galleries.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Appearance
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('galleries.create', $slugs))
                            <li>
                                <a href="{{ route('website-core.gallery.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Theme Setting
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('website-core.settings.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Site Setting
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </li>
@endif
