@extends('layouts.master')
@section('title', 'UI component library')
@section('content')
<x-mm.styles />
<x-mm.scripts />
<x-mm.page title="Component library" description="Laravel + Blade + Tailwind · isolated renovation layer">
    <x-slot name="actions">
        <a href="#fields" class="mm-button">Explore components</a>
    </x-slot>

    {{-- ------------------------------------------------------------ Tiles --}}
    <x-mm.panel id="tiles">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Tiles</h2>
        <p class="tw-text-muted tw-mb-4">Used in night-audit, today-activities, and dashboard surfaces. Variants change the top accent.</p>
        <div class="tw-grid tw-gap-3 sm:tw-grid-cols-2 lg:tw-grid-cols-4">
            <x-mm.tile label="Occupied" value="42" hint="of 60 rooms" />
            <x-mm.tile label="Arrivals" value="8" hint="today" variant="brand" />
            <x-mm.tile label="Revenue" value="$1,240" hint="this week" variant="success" />
            <x-mm.tile label="Overdue" value="2" hint="checkouts" variant="danger" /> {{-- money-travel-on: demo tile label "Overdue" + hint "checkouts" — no real money math, just a status tile. --}}
        </div>
    </x-mm.panel>

    {{-- ------------------------------------------------------------- Empty --}}
    <x-mm.panel id="empty">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Empty state</h2>
        <x-mm.empty text="No bookings match the current filter." {{-- money-travel-on: ui-kit demo string contains the substring "rent" inside "current" — no real money. --}}
            :action="['label' => 'Clear filter', 'href' => '#']" />
    </x-mm.panel>

    {{-- --------------------------------------------------------- Chips etc --}}
    <x-mm.panel id="chips">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Chips &amp; badges</h2>
        <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mb-3">
            <x-mm.badge>Default badge</x-mm.badge>
            <x-mm.chip>Default chip</x-mm.chip>
            <x-mm.chip variant="brand">Brand</x-mm.chip>
            <x-mm.chip variant="success">Confirmed</x-mm.chip>
            <x-mm.chip variant="warning">Pending</x-mm.chip>
            <x-mm.chip variant="danger">Cancelled</x-mm.chip>
            <x-mm.chip variant="muted">Archived</x-mm.chip>
            <x-mm.chip variant="brand" removable>Removable</x-mm.chip>
        </div>
    </x-mm.panel>

    {{-- ---------------------------------------------------------- Toolbar --}}
    <x-mm.panel id="toolbar">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Toolbar</h2>
        <x-mm.toolbar label="List actions">
            <x-slot:leading>
                <input type="search" class="mm-input" placeholder="Search…">
            </x-slot:leading>
            <button type="button" class="mm-button mm-button-secondary">Export</button>
            <button type="button" class="mm-button">+ New booking</button>
        </x-mm.toolbar>
    </x-mm.panel>

    {{-- --------------------------------------------------------- Stepper --}}
    <x-mm.panel id="stepper">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Stepper</h2>
        <p class="tw-text-muted tw-mb-3">3-step visual progression. Keeps the existing form-submit flow — the steps are visual + <code>data-step</code> nav only.</p>
        <x-mm.stepper :steps="[
            ['key' => 'rooms', 'label' => 'Rooms'],
            ['key' => 'guest', 'label' => 'Guest'],
            ['key' => 'pay', 'label' => 'Payment'],
            ['key' => 'review', 'label' => 'Review'],
        ]" current="guest" />
    </x-mm.panel>

    {{-- ------------------------------------------------------ Filter bar --}}
    <x-mm.panel id="filter-bar">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Filter bar</h2>
        <p class="tw-text-muted tw-mb-3">Reuses the <code>apply → #searchForm submit</code> flow from the proven booking-list filter.</p>
        <x-mm.filter-bar action="" method="GET" :search-form-id="'searchForm'">
            <x-mm.field label="Guest name" id="flt-name" name="name" />
            <x-mm.field label="Reference" id="flt-ref" name="ref" />
            <x-mm.select name="status" placeholder="Any status" :options="['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled']" />
            <x-slot:reset>
                <button type="reset" class="mm-button mm-button-ghost">Reset</button>
            </x-slot:reset>
        </x-mm.filter-bar>
    </x-mm.panel>

    {{-- ------------------------------------------------------- Daterange --}}
    <x-mm.panel id="daterange">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Date range</h2>
        <p class="tw-text-muted tw-mb-3">From/To inputs with quick-range chips (today, week, month, weekend).</p>
        <x-mm.daterange name="stay" :from="null" :to="null" />
    </x-mm.panel>

    {{-- --------------------------------------------------- Data table --}}
    <x-mm.panel id="data-table">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Data table</h2>
        <p class="tw-text-muted tw-mb-3">Columns with <code>data-priority="2"</code> / <code>data-priority="3"</code> hide at ≤767px. The first column (no priority) becomes the row label.</p>
        <x-mm.data-table :columns="[
            ['label' => 'Reference', 'priority' => 1],
            ['label' => 'Guest', 'priority' => 1],
            ['label' => 'Check-in', 'priority' => 2],
            ['label' => 'Check-out', 'priority' => 2],
            ['label' => 'Amount', 'priority' => 3, 'align' => 'right'],
            ['label' => 'Status', 'priority' => 1],
        ]" label="Demo bookings" sticky>
            <tr><td>BK-001</td><td>Sample guest</td><td>2026-10-04</td><td>2026-10-07</td><td class="mm-text-right">$240.00</td><td><x-mm.chip variant="success">Confirmed</x-mm.chip></td></tr>
            <tr><td>BK-002</td><td>Another guest</td><td>2026-10-05</td><td>2026-10-08</td><td class="mm-text-right">$320.00</td><td><x-mm.chip variant="warning">Pending</x-mm.chip></td></tr>
            <tr><td>BK-003</td><td>Walk-in</td><td>2026-10-05</td><td>2026-10-06</td><td class="mm-text-right">$80.00</td><td><x-mm.chip variant="success">Confirmed</x-mm.chip></td></tr>
        </x-mm.data-table>
    </x-mm.panel>

    {{-- ------------------------------------------------------- Modal --}}
    <x-mm.panel id="modal">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Modal</h2>
        <p class="tw-text-muted tw-mb-3">Focus trap, ESC close, backdrop click, three sizes (s/m/l/xl).</p>
        <button type="button" class="mm-button" data-mm-modal-open="demo-modal">Open demo modal</button>
        <x-mm.modal id="demo-modal" title="Add a note" size="m">
            <p class="tw-m-0 tw-mb-3">A short form goes here.</p>
            <x-mm.field label="Note" id="note" name="note" />
            <x-slot:footer>
                <button type="button" class="mm-button mm-button-ghost" data-mm-modal-close>Cancel</button>
                <button type="button" class="mm-button">Save</button>
            </x-slot:footer>
        </x-mm.modal>
    </x-mm.panel>

    {{-- ----------------------------------------------------- Print sheet --}}
    <x-mm.panel id="print-sheet">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Print sheet</h2>
        <p class="tw-text-muted tw-mb-3">Three sheet variants: A4 (default), thermal 80mm, thermal 58mm.</p>
        <div class="tw-grid tw-gap-4 lg:tw-grid-cols-2">
            <x-mm.print-sheet title="Reservation invoice (A4)"> {{-- money-travel-on: demo title for the A4 print sheet variant — no money math, just labelling the demo. --}}
                <p>Sample reservation document. Print footer is hidden on paper.</p>
            </x-mm.print-sheet>
            <x-mm.print-sheet sheet="thermal-80mm" title="Kitchen ticket">
                <p>80mm thermal slip — used for kitchen tickets and bar chits.</p>
            </x-mm.print-sheet>
        </div>
    </x-mm.panel>

    {{-- ------------------------------------------------- Original examples --}}
    <x-mm.panel id="fields">
        <h2 class="tw-m-0 tw-mb-4 tw-text-xl tw-font-semibold">Fields and actions</h2>
        <div class="tw-grid tw-gap-4 sm:tw-grid-cols-2">
            <x-mm.field label="Guest name" id="demo-name" name="demo_name" placeholder="Enter a name" />
            <x-mm.field label="Email" id="demo-email" name="demo_email" type="email" value="invalid" error="Enter a valid email address." />
        </div>
        <div class="tw-flex tw-flex-wrap tw-gap-2 tw-mt-4">
            <button type="button" class="mm-button">Primary action</button>
            <button type="button" class="mm-button mm-button-secondary">Secondary action</button>
            <button type="button" class="mm-button mm-button-danger">Danger</button>
            <button type="button" class="mm-button mm-button-ghost">Ghost</button>
        </div>
    </x-mm.panel>
</x-mm.page>
@endsection
