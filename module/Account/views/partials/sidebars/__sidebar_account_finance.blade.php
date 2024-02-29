@php
    $hasAccountSetupPermission  = hasAnyPermission(['account-setups.index', 'accounts.index'], $slugs);
    $hasVoucherPermission       = hasAnyPermission(['voucher-payments.index', 'voucher-receives.index', 'voucher-contras.index', 'voucher-journals.index'], $slugs);
    $hasProductPermission       = hasAnyPermission(['account-products.index', 'account-units.index', 'account-categories.index'], $slugs);
    $hasPartyPermission         = hasAnyPermission(['account-customers.index', 'account-suppliers.index'], $slugs);
    $hasSalePurchasePermission  = hasPermission('account-purchases.index', $slugs) || hasPermission('account-sales.index', $slugs);
@endphp

@if (($hasAccountSetupPermission || $hasVoucherPermission || $hasProductPermission || $hasPartyPermission || $hasSalePurchasePermission || hasPermission('account-ledgers.index', $slugs)) && hasModulePermission('Account & Finance', $active_modules))

    <li>
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-bank" style=" font-weight:bolder"></i>
            <span class="menu-text">Account</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>

        <ul class="submenu">








            <!-- Account Setup -->
            @if ($hasAccountSetupPermission)
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-circle"></i>
                        Setup
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">

                        @if (hasPermission('account-groups.index', $slugs))
                            <li>
                                <a href="{{ route('account-groups.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Account Group
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account-controls.index', $slugs))
                            <li>
                                <a href="{{ route('account-controls.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Account Control
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account-subsidiaries.index', $slugs))
                            <li>
                                <a href="{{ route('account-subsidiaries.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Acc. Subsidiary
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.index', $slugs))
                            <li>
                                <a href="{{ route('accounts.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Account Charts
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('account.index', $slugs))
                            <li>
                                <a href="{{ route('account-opening-balances.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Opening Balance
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif











            <!-- Voucher -->
            @if ($hasVoucherPermission)
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-circle"></i>
                        Voucher
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>







                    <!-- Payment Voucher -->
                    <ul class="submenu">
                        @if(hasPermission('voucher-payments.index', $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Payment
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>


                                <ul class="submenu">
                                    @if(hasPermission('voucher-payments.create', $slugs))
                                        <li>
                                            <a href="{{ route('voucher-payments.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('voucher-payments.index') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            List
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif







                        <!-- Receive Voucher -->
                        @if(hasPermission('voucher-receives.index', $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Receive
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>


                                <ul class="submenu">
                                    @if(hasPermission('voucher-receives.create', $slugs))
                                        <li>
                                            <a href="{{ route('voucher-receives.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('voucher-receives.index') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            List
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif







                        <!-- Contra Voucher -->
                        @if(hasPermission('voucher-contras.index', $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Contra
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>


                                <ul class="submenu">
                                    @if(hasPermission('voucher-contras.create', $slugs))
                                        <li>
                                            <a href="{{ route('voucher-contras.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('voucher-contras.index') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            List
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif







                        <!-- Journal Voucher -->
                        @if(hasPermission('voucher-journals.index', $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Journal
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>


                                <ul class="submenu">
                                    @if(hasPermission('voucher-journals.create', $slugs))
                                        <li>
                                            <a href="{{ route('voucher-journals.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('voucher-journals.index') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            List
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif










            <!-- Products -->
            {{-- @if ($hasProductPermission)
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-circle"></i>
                        Product
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('account-products.create', $slugs))
                            <li>
                                <a href="{{ route('products.create') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Product Create
                                </a>
                            </li>
                        @endif
                        @if (hasPermission('account-products.index', $slugs))
                            <li>
                                <a href="{{ route('products.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Product List
                                </a>
                            </li>
                        @endif



                        @if (hasPermission('account-categories.index', $slugs))
                            <li>
                                <a href="{{ route('categories.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Category
                                </a>
                            </li>
                        @endif



                        @if (hasPermission('account-units.index', $slugs))
                            <li>
                                <a href="{{ route('units.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Units
                                </a>
                            </li>
                        @endif



                        @if (hasPermission('account-units.index', $slugs))
                            <li>
                                <a href="{{ route('damages.index') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Damage
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif --}}







            <!-- Party -->
            @if ($hasPartyPermission)

                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-circle"></i>
                        Party
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">




                        <!-- Customer -->
                        @if (hasPermission('account-customers.index', $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-circle"></i>
                                    Customer
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>

                                <ul class="submenu">
                                    @if (hasPermission('account-customers.create', $slugs))
                                        <li>
                                            <a href="{{ route('acc-customers.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('acc-customers.index') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            List
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif




                        <!-- Supplier -->
                        @if (hasPermission('account-suppliers.index', $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle">
                                    <i class="menu-icon fa fa-circle"></i>
                                    Supplier
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>

                                <ul class="submenu">
                                    @if (hasPermission('account-suppliers.index', $slugs))
                                        <li>
                                            <a href="{{ route('acc-suppliers.create') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Create
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('acc-suppliers.index') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            List
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif









            <!-- Purchase -->
            {{-- @if (hasPermission('account-purchases.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-circle"></i>
                        <span class="menu-text">Purchases</span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-circle"></i>
                                <span class="menu-text">Purchase</span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>

                            <ul class="submenu">
                                @if (hasPermission('account-purchases.create', $slugs))
                                    <li>
                                        <a href="{{ route('acc-purchases.create') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Create
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('acc-purchases.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-circle"></i>
                                <span class="menu-text">Purchase Return</span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>

                            <ul class="submenu">
                                @if (hasPermission('account-purchases.create', $slugs))
                                    <li>
                                        <a href="{{ route('acc-purchase-returns.create') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Create
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('acc-purchase-returns.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        List
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endif --}}








            <!-- Sale -->
            {{-- @if (hasPermission('account-sales.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-circle"></i>
                        <span class="menu-text">Sales</span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-circle"></i>
                                <span class="menu-text">Sale</span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>

                            <ul class="submenu">
                                @if (hasPermission('account-sales.create', $slugs))
                                    <li>
                                        <a href="{{ route('acc-sales.create') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Create
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('acc-sales.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        List
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="dropdown-toggle">
                                <i class="menu-icon fa fa-circle"></i>
                                <span class="menu-text">Sale Return</span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>

                            <ul class="submenu">
                                @if (hasPermission('account-sales.create', $slugs))
                                    <li>
                                        <a href="{{ route('acc-sale-returns.create') }}">
                                            <i class="menu-icon fa fa-caret-right"></i>
                                            Create
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('acc-sale-returns.index') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        List
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endif --}}










            <!-- Reports -->
            @if (hasPermission('account-ledgers.index', $slugs))
                <li>
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa fa-circle"></i>
                        Report
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>

                    <ul class="submenu">
                        @if (hasPermission('report.chart-of-account', $slugs))
                            <li>
                                <a href="{{ route('report.chart-of-account') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Chart Of Account
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('report.ledger-journal', $slugs))
                            <li>
                                <a href="{{ route('report.ledger-journal') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Ledger Journal
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('report.voucher-report') }}"
                                    title="Voucher Wise Report">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Voucher Report
                                </a>
                            </li>
                        @endif

                        @if (hasPermission('report.account-ledger', $slugs))
                            <li>
                                <a href="{{ route('report.account-ledger') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Account Ledger
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('report.account-ledger') }}?report=daily&from={{ date('Y-m-d') }}&to={{ date('Y-m-d') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Daily Account Ledger
                                </a>
                            </li>
                            <li>
                                @php
                                    $from   = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                                    $to     = \Carbon\Carbon::now()->endOfMonth()->toDateString();
                                @endphp
                                <a href="{{ route('report.account-ledger') }}?report=monthly&from={{ $from }}&to={{ $to }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Monthly Account Ledger
                                </a>
                            </li>
                        @endif


                        {{-- @if (hasPermission('report.customer-ledger', $slugs))
                            <li>
                                <a href="{{ route('report.customer-ledger') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Customer Ledger
                                </a>
                            </li>
                        @endif --}}

                        @if (hasPermission('report.supplier-ledger', $slugs))
                            <li>
                                <a href="{{ route('report.supplier-ledger') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Supplier Ledger
                                </a>
                            </li>
                        @endif


                        @if (hasPermission('report.supplier-ledger', $slugs))
                            <li>
                                <a href="{{ route('report.supplier') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Supplier Report
                                </a>
                            </li>
                        @endif


                        @if (hasPermission('report.supplier-ledger', $slugs))
                            <li>
                                <a href="{{ route('report.account-receivable') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Acc. Receivable
                                </a>
                            </li>
                        @endif


                        @if (hasPermission('report.supplier-ledger', $slugs))
                            <li>
                                <a href="{{ route('report.account-payable') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Acc. Payable
                                </a>
                            </li>
                        @endif


                        @if (hasPermission('report.subsidiary-wise-ledger', $slugs))
                            <li>
                                <a href="{{ route('report.subsidiary-wise-ledger') }}"
                                    title="Subsidiary Wise Ledger">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Subsidiary Ledger
                                </a>
                            </li>
                        @endif














                        <!-- financial report -->
                        @if (hasAnyPermission(['account.trial.balance.reports', 'account.balance.sheet.reports', 'account.profit.and.loss.reports', 'financial-reports.index'], $slugs))
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="tooltip" title="Financial Statements">
                                    <i class="menu-icon fa fa-circle"></i>
                                    Financial
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <b class="arrow"></b>

                                <ul class="submenu">
                                    @if (hasPermission('report.trial-balance', $slugs))
                                        <li>
                                            <a href="{{ route('report.trial-balance') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Trial Balance
                                            </a>
                                        </li>
                                    @endif

                                    @if (hasPermission('report.income-statement', $slugs))
                                        <li>
                                            <a href="{{ route('report.income-statement') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Income Statement
                                            </a>
                                        </li>
                                    @endif

                                    @if (hasPermission('report.income-statement', $slugs))
                                        <li>
                                            <a href="{{ route('report.income-expense-statement') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Income Expense Statement
                                            </a>
                                        </li>
                                    @endif

                                    @if (hasPermission('report.equity-statement', $slugs))
                                        <li>
                                            <a href="{{ route('report.equity-statement') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Equity Statement
                                            </a>
                                        </li>
                                    @endif

                                    @if (hasPermission('report.balance-sheet', $slugs))
                                        <li>
                                            <a href="{{ route('report.balance-sheet') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Balance Sheet
                                            </a>
                                        </li>
                                    @endif

                                    @if (hasPermission('report.cash.flow', $slugs))
                                        <li>
                                            <a href="{{ route('report.cash.flow') }}">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Cash Flow
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        {{-- <li>
                            <a href="#" class="dropdown-toggle" data-toggle="tooltip" title="Financial Statements">
                                <i class="menu-icon fa fa-circle"></i>
                                Inventory
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>

                            <ul class="submenu">
                                <li>
                                    <a href="{{ route('account.stock-in-hand') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        Stock In Hand
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('account.item-ledger') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        Item Ledger
                                    </a>
                                </li>
                            </ul>
                        </li> --}}

                    </ul>
                </li>
            @endif
        </ul>
    </li>
@endif
