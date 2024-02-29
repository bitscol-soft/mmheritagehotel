<!-- Pharmacy Management  Sidebar Start -->
@php
    $sales = 'resturant.sales.create';
    $purchase = 'resturant.purchases.index';
    $inventory = 'resturant.inventories.index';
    $reports = 'resturant.reports.index';
@endphp

@if (hasAnyPermission([$sales, $purchase, $inventory, $reports], $slugs))

    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fas fa-utensils"></i>
            <span class="menu-text">Restaurant</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">


            <!-- Pharmacy Sale Product -->
            @if (hasPermission('resturant.sales.create', $slugs))
                <li>

                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Sales
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>


                    <!-- Sale Sub menu -->
                    <ul class="submenu">
                        <li class="{{ request()->routeIs('rst.sales.create') }}">
                            <a href="{{ route('rst.sales-v2.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                New Sale
                            </a>
                            <b class="arrow"></b>
                        </li>

                        {{-- <li class="{{ request()->routeIs('rst.sales.create') }}">
                            <a href="{{ route('rst.sales.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Sale Create
                            </a>
                            <b class="arrow"></b>
                        </li> --}}


                        <li>
                            <a href="{{ route('rst.sales.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Sale List
                            </a>
                            <b class="arrow"></b>
                        </li>


                        <li>
                            <a href="{{ route('rst.sale-returns.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                New Sale Return
                            </a>
                            <b class="arrow"></b>
                        </li>


                        <li>
                            <a href="{{ route('rst.sale-returns.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Sale Return List
                            </a>
                            <b class="arrow"></b>
                        </li>


                    </ul>

                </li>
            @endif




            <!-- PAYMENT COLLECTION -->
            @if (hasPermission('resturant.payment-collection', $slugs))
                <li>
                    <a href="{{ route('rst.sales.payment-collection') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Payment Collection
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif



            <!-- Pharmacy Purchase Product -->
            @if (hasPermission('resturant.purchases.index', $slugs) && !setting('mother_inventory'))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Purchases
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <!-- Purchase Sub menu -->
                    <ul class="submenu">

                        @if (hasPermission('resturant.purchases.create', $slugs))
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




            <!-- RESTAURANT Inventory Management -->
            @if (hasPermission('resturant.inventories.index', $slugs) && !setting('mother_inventory'))
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
                            <a href="{{ route('rst.report.inventory') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Product Inventory
                            </a>
                            <b class="arrow"></b>
                        </li>

                        @if (hasPermission('resturant.purchases.create', $slugs))
                            <!-- All Product -->
                            <li>
                                <a href="{{ route('rst.products.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    All Products
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <!-- Inventory Product Create menu -->
                            <li>
                                <a href="{{ route('rst.products.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create Product
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
                    </ul>

                </li>
            @endif




            @if (hasPermission('rst.table-manages.index', $slugs))
                <li>
                    <a href="{{ route('rst.table-manages.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Table Manage
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif





            @if (hasPermission('hotel.night-audit.index', $slugs))
                <li>
                    <a href="{{ route('rst.night-audits.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Night Audits
                    </a>
                    <b class="arrow"></b>
                </li>
            @endif



            <!-- Report -->
            @if (hasPermission('resturant.reports.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Reports
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <!-- Report Sub menu -->
                    <ul class="submenu">

                        @if (hasPermission('resturant.reports.create', $slugs))
                            <li>
                                <a href="{{ route('rst.report.cash-flows') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Cash Flow
                                </a>
                                <b class="arrow"></b>
                            </li>


                            <li>
                                <a href="{{ route('rst.report.sales') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Sales Report
                                </a>
                                <b class="arrow"></b>
                            </li>
                            <li>
                                <a href="{{ route('rst.report.today') }}">
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
<!-- Restautant Management Sidebar End -->



@if (setting('rst_use_kitchen_module') == 1)

<!-- Kitchen Management  Sidebar Start -->
@php
    $kitchen = 'kit.kitchen.create';
    $index = 'kit.kitchen.index';
@endphp

    @if (hasAnyPermission([$kitchen, $index], $slugs))

        <li>
            <a href="#" class="dropdown-toggle">
                <span class='fa-stack'>
                    <i class='far fa-square fa-stack-2x'></i>
                    <i class='fas fa-utensils fa-stack-1x'></i>
                </span>
                <span class="menu-text">Kitchen</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">

                <!-- Pharmacy Sale Product -->
                @if (hasPermission('kit.kitchen.create', $slugs))
                    <li>
                        <a href="{{ route('kit.kitchen.create') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            New Order
                        </a>
                        <b class="arrow"></b>
                        <a href="{{ route('kit.kitchen.index') }}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            All Order
                        </a>
                        <b class="arrow"></b>

                    </li>
                @endif






                @if (hasPermission('resturant.reports.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Production
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <!-- Report Sub menu -->
                    <ul class="submenu">

                        @if (hasPermission('rst.production.index', $slugs))
                            <li>
                                <a href="{{ route('rst.production.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create
                                </a>
                                <b class="arrow"></b>
                            </li>


                            <li>
                                <a href="{{ route('rst.production.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Production List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif



            @if (hasPermission('rst.productiont.index', $slugs) && setting('mother_inventory'))
                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Rest  Metrial
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>

                        <!-- Adjustment -->
                        <ul class="submenu">

                            {{-- @if (hasPermission('rst.material-unit.index', $slugs))
                                <li>
                                    <a href="{{ route('rst.material-unit.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        Material Unit
                                    </a>
                                    <b class="arrow"></b>
                                </li>
                            @endif
                            @if (hasPermission('rst.material.index', $slugs))
                                <li>
                                    <a href="{{ route('rst.material.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        Material
                                    </a>
                                    <b class="arrow"></b>
                                </li>
                            @endif --}}
                            @if (hasPermission('rst.material.index', $slugs))
                                <li>
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
                                </li>
                            @endif

                            <!-- Purchase -->
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Purchases
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>

                                <!-- Purchase Sub menu -->
                                <ul class="submenu">

                                    @if (hasPermission('rst.purchase.create', $slugs))
                                        <!-- Purchase Products -->
                                        <li>
                                            <a href="{{ route('rst.purchase.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create Purchases
                                            </a>
                                            <b class="arrow"></b>
                                        </li>
                                    @endif

                                    @if (hasPermission('rst.purchase.index', $slugs))
                                        <li>
                                            <a href="{{ route('rst.purchase.index') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Purchases List
                                            </a>
                                            <b class="arrow"></b>
                                        </li>
                                    @endif

                                </ul>


                            </li>

                        </ul>


                    </li>
                    @endif


            </ul>

        </li>



    @endif
<!-- Restautant Management Sidebar End -->
@endif

