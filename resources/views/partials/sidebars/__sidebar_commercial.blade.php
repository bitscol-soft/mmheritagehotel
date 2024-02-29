
@if (hasAnyPermission(["remarks.index", "mids.index", 'bb.lcs.index'], $slugs) && hasModulePermission('Commercial', $active_modules))
    <li class="{{ request()->segment('2') == 'commercial'  ? 'open' : '' }}">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-briefcase"></i>
            <span class="menu-text"> Commercial </span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">

            @if(hasPermission('remarks.index',$slugs))
                <li class="{{ request()->is('garments/commercial/payments') || request()->is('garments/commercial/remarks') || request()->is('garments/commercial/payment-type') || request()->is('garments/commercial/port-of-loading') || request()->is('garments/commercial/port-of-discharge') || request()->is('garments/commercial/lc-periods') || request()->is('garments/commercial/document-presentation-time') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle" title="Commercial Setup">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Comm. Setup
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">

                        @if(hasPermission('remarks.index',$slugs))
                            <li class="{{ request()->is('garments/commercial/payment-type') ? 'active' : '' }}">
                                <a href="{{ route('payment-type.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Payment-type
                                </a>
                                <b class="arrow"></b>
                            </li>

                            <li class="{{ request()->is('garments/commercial/port-of-loading') ? 'active' : '' }}">
                                <a href="{{ route('port-of-loading.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Port Of Loading
                                </a>
                                <b class="arrow"></b>
                            </li>

                            <li class="{{ request()->is('garments/commercial/port-of-discharge') ? 'active' : '' }}">
                                <a href="{{ route('port-of-discharge.index') }}" title="Port Of Discharge">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Port Of Disc.
                                </a>
                                <b class="arrow"></b>
                            </li>

                            <li class="{{ request()->is('garments/commercial/lc-periods') ? 'active' : '' }}">
                                <a href="{{ route('lc-periods.index') }}" title="Port Of Discharge">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Lc Periods
                                </a>
                                <b class="arrow"></b>
                            </li>

                            <li class="{{ request()->is('garments/commercial/document-presentation-time') ? 'active' : '' }}">
                                <a href="{{ route('document-presentation-time.index') }}" title="Document Presentation Time">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Doc.Presentation
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if (hasPermission('mids.index', $slugs))
                <li class="{{ request()->segment(2) == "mid-generate" ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Master Id
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (hasPermission('mids.create', $slugs))
                            <li class="{{ request()->is('garments/commercial/mid-generate/create') ? 'active' : '' }}">
                                <a href="{{ route('mid-generate.create') }}" title="Generate Sales Id">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Gen. Master Id
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (hasPermission('mids.index', $slugs))
                            <li class="{{ request()->is('garments/commercial/mid-generate') ? 'active' : '' }}">
                                <a href="{{ route('mid-generate.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Master Id List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if (hasAnyPermission(['mid.transfers.index', 'mid.transfers.create'], $slugs))
                <li class="{{ request()->segment(2) == "mid-transfer" ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        MID Transfer
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (hasPermission('mid.transfers.create', $slugs))
                            <li class="{{ request()->is('garments/commercial/mid-transfer/create') ? 'active' : '' }}">
                                <a href="{{ route('mid-transfer.create') }}" title="Generate Sales Id">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (hasPermission('mid.transfers.index', $slugs))
                            <li class="{{ request()->is('garments/commercial/mid-transfer') ? 'active' : '' }}">
                                <a href="{{ route('mid-transfer.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Transfer List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            @if (hasAnyPermission(['bb.lcs.index', 'bb.lcs.irregular.index'], $slugs))
                <li class="{{ request()->segment(2) == "bblc" ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        BB LC
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    @if (hasPermission('bb.lcs.index', $slugs))
                        <ul class="submenu">
                            <li class="{{ request()->segment(3) == "regular" ? 'open' : '' }}">
                                <a href="#" class="dropdown-toggle" title="Generate BBLC">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Regular
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>

                                <ul class="submenu">
                                    @if (hasPermission('bb.lcs.create', $slugs))
                                        <li class="{{ request()->is('garments/commercial/bblc/regular/create') ? 'active' : '' }}">
                                            <a href="{{ route('bblc.regular.create') }}" title="Generate BBLC">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                            <b class="arrow"></b>
                                        </li>
                                    @endif

                                    @if (hasPermission('bb.lcs.index', $slugs))
                                        <li class="{{ request()->is('garments/commercial/bblc/regular') ? 'active' : '' }}">
                                            <a href="{{ route('bblc.regular.index') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                List
                                            </a>
                                            <b class="arrow"></b>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    @endif

                    @if (hasPermission('bb.lcs.irregular.index', $slugs))
                        <ul class="submenu">
                            <li class="{{ request()->segment(3) == "irregular" ? 'open' : '' }}">
                                <a href="#" class="dropdown-toggle" title="Generate BBLC">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Irregular
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>

                                <ul class="submenu">
                                    @if (hasPermission('bb.lcs.irregular.create', $slugs))
                                        <li class="{{ request()->is('garments/commercial/bblc/irregular/create') ? 'active' : '' }}">
                                            <a href="{{ route('bblc.irregular.create') }}" title="Generate BBLC">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                            <b class="arrow"></b>
                                        </li>
                                    @endif

                                    @if (hasPermission('bb.lcs.irregular.index', $slugs))
                                        <li class="{{ request()->is('garments/commercial/bblc/irregular') ? 'active' : '' }}">
                                            <a href="{{ route('bblc.irregular.index') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                List
                                            </a>
                                            <b class="arrow"></b>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    @endif
                </li>
            @endif


            @if (hasPermission('commercial.invoices.index', $slugs))
                <li class="{{ request()->segment(2) == "invoice" ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Invoice
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (hasPermission('commercial.invoices.create', $slugs))
                            <li class="{{ request()->is('garments/commercial/invoice/create') ? 'active' : '' }}">
                                <a href="{{ route('invoice.create') }}" title="Create Invoice">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (hasPermission('commercial.invoices.index', $slugs))
                            <li class="{{ request()->is('garments/commercial/invoice') ? 'active' : '' }}">
                                <a href="{{ route('invoice.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Invoice List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </li>
@endif
