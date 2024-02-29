@if (hasPermission('hotel.services.index', $slugs))
<li>
    <a href="#" class="dropdown-toggle">
        <i class="menu-icon fas fa-swimming-pool"></i>
        <span class="menu-text">H. Service</span>
        <b class="arrow fa fa-angle-down"></b>
    </a>

    <b class="arrow"></b>

    <ul class="submenu">

        <!-- New Sale -->
        @if (hasPermission('sales.create', $slugs))
            <li>
                <a href="{{ route('hotelservice.service-sales.create') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    New Sale
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        <!-- Sale List -->
        @if (hasPermission('sales.index', $slugs))
            <li>
                <a href="{{ route('hotelservice.service-sales.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Sale List
                </a>
                <b class="arrow"></b>
            </li>
        @endif

        <!-- Services -->
        <li>
            <a href="{{ route('hotelservice.services.index') }}">
                <i class="menu-icon fa fa-caret-right"></i>
                Services
            </a>
            <b class="arrow"></b>
        </li>


        @if (hasPermission('hotel.night-audit.index', $slugs))
            <li>
                <a href="{{ route('hotelservice.night-audits.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Night Audits
                </a>
                <b class="arrow"></b>
            </li>
        @endif


    </ul>
</li>
@endif
