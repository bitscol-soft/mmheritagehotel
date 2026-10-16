@if (hasModulePermission('CRM', $active_modules)
&& hasAnyPermission(['crm.customers.index', 'crm.mail_templates.index'], $slugs))
    <li class="{{ request()->segment(1) == 'crm' ? 'open' : '' }}">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-handshake-o"></i>
            <span class="menu-text">CRM</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>

        <ul class="submenu">
            @if (hasPermission("crm.customers.index", $slugs))
                <li class="{{ request()->is('crm/customers*') ? 'active' : '' }}">
                    <a href="{{ route('crm.customers.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Customer
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif

            @if (hasPermission("crm.mail_templates.index", $slugs))
                <li class="{{ request()->is('crm/mail-templates*') ? 'active' : '' }}">
                    <a href="{{ route('crm.mail-templates.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Mail Template
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif
        </ul>
    </li>
@endif
