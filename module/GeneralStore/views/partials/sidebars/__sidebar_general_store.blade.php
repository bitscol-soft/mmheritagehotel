@if (hasAnyPermission(['items.index', 'purchases.index', 'gs.reports.index', 'create.requisitions.index'], $slugs) && hasModulePermission('General Store', $active_modules))
    <li class="{{ request()->segment(1) == 'gs' ? 'open' : '' }}">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-shopping-cart"></i>
            <span class="menu-text">
                General Store
            </span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>

        <ul class="submenu">


            <!-- item unit -->
            @if (in_array('items.index', $slugs) || auth()->id() == 1)
                <li class="{{ request()->segment(2) == 'item_unit*' ? 'active' : '' }}">
                    <a href="{{ route('item-units.index') }}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Item Unit
                    </a>
                </li>
            @endif

            <!-- item -->
            @if (in_array('items.index', $slugs) || auth()->id() == 1)
                <li class="{{ request()->segment(2) == 'items' ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Items
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (in_array('items.create', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/items/create') ? 'active' : '' }}">
                                <a href="{{ route('items.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create Item
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (in_array('items.index', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/items') ? 'active' : '' }}">
                                <a href="{{ route('items.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Item List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif

            <!-- supplier -->
            @if (hasPermission('suppliers.index', $slugs))

                <li class="{{ request()->is('global-setting/supplie*') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Suppliers
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('supplier.types.index', $slugs))
                            <li class="{{ request()->segment(2) == 'supplier-type' ? 'active' : '' }}">
                                <a href="{{ route('supplier-types.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Supplier Type
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        <li class="{{ request()->is('global-setting/suppliers/create') ? 'active' : '' }}">
                            <a href="{{ route('suppliers.create') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Add New
                            </a>
                            <b class="arrow"></b>
                        </li>

                        <li
                            class="{{ request()->segment(2) == 'suppliers' && request()->segment(3) != 'create' ? 'active' : '' }}">
                            <a href="{{ route('suppliers.index') }}">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Supplier List
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
            @endif


            <!-- purchase -->
            @if (in_array('purchases.index', $slugs) || auth()->id() == 1)
                <li
                    class="{{ request()->segment(2) == 'purchases' || request()->is('gs/grn-list') || request()->is('gs/purchase_receives/*') || request()->is('gs/purchase_approve/*') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Purchase
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (in_array('purchases.create', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/purchases/create') ? 'active' : '' }}">
                                <a href="{{ route('purchases.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Create Purchase
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (in_array('purchases.view', $slugs) || auth()->id() == 1)
                            <li
                                class="{{ request()->is('gs/purchases') || request()->is('gs/purchase_approve/*') ? 'active' : '' }}">
                                <a href="{{ route('purchases.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Purchase List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (in_array('purchases.grn.list', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/grn-list') ? 'active' : '' }}">
                                <a href="{{ route('grn.list') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    GRN List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- general store -->
            @if (in_array('create.requisitions.index', $slugs) || auth()->id() == 1)
                <li
                    class="{{ request()->segment(2) == 'goods-requisitions' || request()->is('gs/gin-list') || request()->is('gs/goods-requisition/approve/*') ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Requisition
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (in_array('create.requisitions.create', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/goods-requisitions/create') ? 'active' : '' }}">
                                <a href="{{ route('goods-requisitions.create') }}" title="Create Requisition">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    C. Requisition
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (in_array('create.requisitions.view', $slugs) || auth()->id() == 1)
                            <li
                                class="{{ request()->is('gs/goods-requisitions') || request()->is('gs/goods-requisitions/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('goods-requisitions.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Requisition List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif

                        @if (in_array('create.requisitions.gin.list', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/gin-list') ? 'active' : '' }}">
                                <a href="{{ route('gin.list') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    GIN List
                                </a>
                                <b class="arrow"></b>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif


            <!-- general store report -->
            @if (in_array('gs.reports.index', $slugs) || auth()->id() == 1)
                <li class="{{ request()->segment(2) == 'gs-reports' ? 'open' : '' }}">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-caret-right"></i>
                        GS Reports
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        @if (in_array('gs.reports.item.ledger', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/gs-reports/items_stock') ? 'active' : '' }}">
                                <a href="{{ route('items_stock') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Stock In Hand
                                </a>
                            </li>
                        @endif

                        @if (in_array('gs.reports.item.details', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/gs-reports/item_details') ? 'active' : '' }}">
                                <a href="{{ route('item_details') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Item Ledger
                                </a>
                            </li>
                        @endif

                        @if (in_array('gs.reports.item.details', $slugs) || auth()->id() == 1)
                            <li class="{{ request()->is('gs/gs-reports/weakly/movement/issue') ? 'active' : '' }}">
                                <a href="{{ route('weakly.movement.issue') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Weekly Movement
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </li>
@endif
