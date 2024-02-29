<!-- Pharmacy Management  Sidebar Start -->
@php
    $sales          = 'bar.sales.create';
    $purchase       = 'bar.purchases.index';
    $inventory      = 'bar.inventories.index';
    $reports        = 'bar.reports.index';
    $cashFlowReport = 'bar.cash-flow.index';
    $saleReport     = 'bar.sale-report.index';
@endphp

@if (hasAnyPermission([$sales, $purchase, $inventory, $reports, $cashFlowReport, $saleReport], $slugs))

    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fas fa-glass-cheers"></i>
            <span class="menu-text">Bar</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">


            <!-- BAR Sale Product -->
            @if (hasPermission('bar.sales.create', $slugs))
                <li>

                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Sales
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>


                    <!-- Sale Sub menu -->
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('bar.sales.create') }}">
                            <a href="{{ route('bar.sales-v2.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                New Sale
                            </a>
                            <b class="arrow"></b>
                        </li>

                        {{-- <li class="{{ request()->routeIs('bar.sales.create') }}">
                            <a href="{{ route('bar.sales.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Sale Create
                            </a>
                            <b class="arrow"></b>
                        </li> --}}


                        <li>
                            <a href="{{ route('bar.sales.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Sale List
                            </a>
                            <b class="arrow"></b>
                        </li>


                        <li>
                            <a href="{{ route('bar.sale-returns.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                New Sale Return
                            </a>
                            <b class="arrow"></b>
                        </li>


                        <li>
                            <a href="{{ route('bar.sale-returns.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Sale Return List
                            </a>
                            <b class="arrow"></b>
                        </li>


                    </ul>

                </li>
            @endif





            <!-- BAR Purchase Product -->
            @if (hasPermission('bar.purchases.index', $slugs) && !setting('mother_inventory'))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Purchases
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <!-- Purchase Sub menu -->
                    <ul class="submenu">

                        @if (hasPermission('bar.purchases.create', $slugs))
                            <li>
                                <a href="{{ route('bar.purchases.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    New Purchase
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('bar.purchases.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Purchase List
                            </a>
                            <b class="arrow"></b>
                        </li>


                    </ul>


                </li>
            @endif


            <!-- BAR Inventory Management -->
            @if (hasPermission('bar.inventories.index', $slugs) && !setting('mother_inventory'))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Inventory
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>


                    <!-- Inventory Sub menu -->
                    <ul class="submenu">


                        <!-- Inventory Products -->
                        <li>
                            <a href="{{ route('bar.report.inventory') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Product Inventory
                            </a>
                            <b class="arrow"></b>
                        </li>

                        @if (hasPermission('bar.purchases.create', $slugs))
                            <!-- All Product -->
                            <li>
                                <a href="{{ route('bar.products.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    All Products
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <!-- Inventory Product Create menu -->
                            <li>
                                <a href="{{ route('bar.products.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create Product
                                </a>
                                <b class="arrow"></b>
                            </li>



                            <!-- Inventory Category menu -->
                            <li>
                                <a href="{{ route('bar.product-categories.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Categories
                                </a>
                                <b class="arrow"></b>
                            </li>


                            <!-- Inventory Suppliers menu -->
                            <li>
                                {{-- <a href="{{ route('bar.manufacturers.index') }}"> --}}
                                <a href="{{ route('bar.suppliers.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    {{-- Manufacturers --}}
                                    Suppliers
                                </a>
                                <b class="arrow"></b>
                            </li>




                            <!-- Inventory Unit Management menu -->
                            <li>
                                <a href="{{ route('bar.product-units.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Unit Management
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>

                </li>
            @endif



            <li>
                <a href="{{ route('bar.table-manages.index') }}">
                    <i class="menu-icon fa fa-caret-right"></i>
                    Table Manage
                </a>
                <b class="arrow"></b>
            </li>

            @if (hasPermission('hotel.night-audit.index', $slugs))
                <li>
                    <a href="{{ route('bar.night-audits.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Night Audits
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif


            <!-- Report -->
            @if (hasPermission('bar.reports.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Reports
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <!-- Report Sub menu -->
                    <ul class="submenu">

                        @if (hasPermission('bar.cash-flow.index', $slugs))
                            <li>
                                <a href="{{ route('bar.report.cash-flows') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Cash Flow
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (hasPermission('bar.sale-report.index', $slugs))
                            <li>
                                <a href="{{ route('bar.report.sales') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Sales Report
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (hasPermission('bar.reports.index', $slugs))
                            <li>
                                <a href="{{ route('bar.report.today') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Today Report
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
<!-- BAR Sidebar End -->
