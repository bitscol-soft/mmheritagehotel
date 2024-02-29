@if (hasPermission('banquet.booking.index', $slugs))
    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fas fa fa-building"></i>
            <span class="menu-text">Banquet Hall</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>

        <b class="arrow"></b>

        <ul class="submenu">


            <li>
                <a href="javascript:void(0)" class="dropdown-toggle">
                    <i class="menu-icon fa fa-caret-right"></i>
                    <span class="menu-text">Hall Booking</span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>

                <ul class="submenu">

                    @if (hasPermission('banquet.booking.create', $slugs))
                        <li class="{{ request()->is('hotel/booking/create') ? 'active' : '' }}">
                            <a href="{{ route('banquet.booking.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Create
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                    @if (hasPermission('banquet.booking.index', $slugs))
                        <li class="{{ request()->is('hotel/booking') ? 'active' : '' }}">
                            <a href="{{ route('banquet.booking.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Booking List
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li
                            class="{{ request()->is('banquet/booking-purpose') && request('type') == 'purpose' ? 'active' : '' }}">
                            <a href="{{ route('banquet.booking-purpose.index') }}?type=purpose">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Booking Purpose
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li
                            class="{{ request()->is('banquet/booking-purpose') && request('type') == 'platform' ? 'active' : '' }}">
                            <a href="{{ route('banquet.booking-purpose.index') }}?type=platform">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Booking Platform
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif


                </ul>
            </li>



            <li>
                <a href="javascript:void(0)" class="dropdown-toggle">
                    <i class="menu-icon fa fa-caret-right"></i>
                    <span class="menu-text">Room Management</span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>

                <ul class="submenu">
                    @if (hasPermission('hall-categories.index', $slugs))
                        <li class="">
                            <a href="javascript:void(0)" class="dropdown-toggle">
                                <i class="menu-icon fa fa-caret-right"></i>
                                <span class="menu-text">Category</span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>
                            <ul class="submenu">
                                @if (hasPermission('hall-categories.create', $slugs))
                                    <li>
                                        <a href="{{ route('banquet.hall-categories.create') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Create
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('banquet.hall-categories.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        List
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (hasPermission('banquet.aminities.index', $slugs))
                        <li class="">
                            <a href="javascript:void(0)" class="dropdown-toggle">
                                <i class="menu-icon fa fa-caret-right"></i>
                                <span class="menu-text">Amenities</span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>

                            <ul class="submenu">
                                <li>
                                    <a href="{{ route('banquet.aminities.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        List
                                    </a>
                                </li>

                                @if (hasPermission('banquet.aminities.create', $slugs))
                                    <li>
                                        <a href="{{ route('banquet.aminities.create') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Create
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (hasPermission('banquet.hall-rooms.index', $slugs))
                        <li class="">
                            <a href="{{ route('banquet.hall-rooms.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Rooms
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif


                </ul>
            </li>

        </ul>
    </li>
@endif
