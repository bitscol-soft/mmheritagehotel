@php
    $rstInventory = 'resturant.inventories.index';
    $barInventory = 'bar.inventories.index';
    $stkInventory = 'rst.stock-adjustment.index';

@endphp

@if (hasAnyPermission([$rstInventory, $barInventory, $stkInventory], $slugs) && setting('mother_inventory'))

    <!-- Mother Inventory Management -->
    @if (hasPermission('resturant.inventories.index', $slugs))
        <li>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-archive"></i>
                Inventory
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>


            <!-- Inventory Sub menu -->
            <ul class="submenu">


                @if (hasPermission('resturant.purchases.create', $slugs))
                    <!-- All Product -->
                    <li>
                        <a href="{{ route('rst.get-all-product') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            All Products
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <!-- Inventory Product Create menu -->
                    <li>
                        <a href="{{ route('rst.products.create') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Create Restaurant Product
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="{{ route('rst.products.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Restaurant Product List
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <!-- Inventory Product Create menu -->
                    {{-- <li>
                        <a href="{{ route('rst.mat-products.create') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Create Metrial
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li>
                        <a href="{{ route('rst.mat-products.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Metrial List
                        </a>
                        <b class="arrow"></b>
                    </li> --}}
                    @if (active_modules()->where('name', 'Bar')->count() == 1)
                        <li class="hasQuery">
                            <a href="{{ route('bar.products.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Create Bar Product
                            </a>
                            <b class="arrow"></b>
                        </li>
                        <li>
                            <a href="{{ route('bar.products.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Bar Product List
                            </a>
                            <b class="arrow"></b>
                        </li>
                    @endif

                    <li>
                        <a href="{{ route('bar.packages.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Product Package
                        </a>
                        <b class="arrow"></b>
                    </li>

                    <!-- Inventory Category menu -->
                    <li>
                        <a href="{{ route('rst.product-categories.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Categories
                        </a>
                        <b class="arrow"></b>
                    </li>


                    <!-- Inventory Suppliers menu -->
                    <li>
                        {{-- <a href="{{ route('rst.manufacturers.index') }}"> --}}
                        <a href="{{ route('rst.suppliers.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            {{-- Manufacturers --}}
                            Suppliers
                        </a>
                        <b class="arrow"></b>
                    </li>


                    <!-- Inventory Unit Management menu -->
                    <li>
                        <a href="{{ route('rst.product-units.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Unit Management
                        </a>
                        <b class="arrow"></b>
                    </li>
                @endif







                {{--  Stock Adjustment  --}}
                @if (hasPermission('rst.stock-adjustment.index', $slugs) && setting('mother_inventory'))
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-balance-scale"></i>
                            Stock Adjustment
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>

                        <!-- Adjustment -->
                        <ul class="submenu">

                            @if (hasPermission('rst.stock-adjustment.create', $slugs))
                                <li>
                                    <a href="{{ route('rst.stock-adjustment.create') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        Adjustment Create
                                    </a>
                                    <b class="arrow"></b>
                                </li>
                            @endif
                            @if (hasPermission('rst.stock-adjustment.index', $slugs))
                                <li>
                                    <a href="{{ route('rst.stock-adjustment.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        Adjustment List
                                    </a>
                                    <b class="arrow"></b>
                                </li>
                            @endif

                        </ul>


                    </li>
                @endif



               {{-- Rest Production --}}



                {{-- purchases --}}
                @if (hasPermission('bar.purchases.index', $slugs) ||
                        (hasPermission('rst.purchases.index', $slugs) && setting('mother_inventory')))
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Bar Purchases
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

                @if (hasPermission('rst.purchases.index', $slugs) ||
                        (hasPermission('rst.purchases.index', $slugs) && setting('mother_inventory')))
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Rst Purchases
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>

                        <!-- Purchase Sub menu -->
                        <ul class="submenu">

                            @if (hasPermission('rst.purchases.create', $slugs))
                                <li>
                                    <a href="{{ route('rst.purchases.create') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        New Purchase
                                    </a>
                                    <b class="arrow"></b>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('rst.purchases.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Purchase List
                                </a>
                                <b class="arrow"></b>
                            </li>


                        </ul>


                    </li>
                @endif







                <!-- REPORT -->
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Reports
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <!-- Purchase Sub menu -->
                    <ul class="submenu">

                        @if (hasPermission('resturant.purchases.create', $slugs))
                            <!-- Inventory Products -->
                            <li>
                                <a href="{{ route('rst.report.inventory') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Product Inventory
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('rst.report.inventory-ledger') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Stock Ledger
                            </a>
                            <b class="arrow"></b>
                        </li>


                    </ul>


                </li>
            </ul>

        </li>
    @endif

@endif
<!-- Pharmacy Management Sidebar End -->

