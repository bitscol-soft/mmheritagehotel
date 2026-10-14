@extends('layouts.master')


@section('title', 'Restaurant New Sale')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom_css/floating-input.css') }}">
    @include('rst/sales-v2/_inc/style')

@endsection
@section('content')

    <x-mm.styles />
    <x-widget.guest-create-modal />

    <x-mm.page class="mm-rst mm-rst-pos mm-rst-sale" title="Restaurant POS" description="Select guest, room or table, add dishes, fire order or collect payment.">
        <x-slot name="actions">
            <button type="button" id="mm-focused-fullscreen-btn" data-mm-fullscreen-toggle aria-pressed="false" class="mm-button mm-button-secondary mm-focused-fs-btn" title="Toggle distraction-free Focused Fullscreen Mode (Alt+Z)">
                <i class="ace-icon fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen
            </button>
            <a href="{{ route('rst.table-manages.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-th-large" aria-hidden="true"></i> Tables
            </a>
            <a href="{{ route('kit.kitchen.create') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-fire" aria-hidden="true"></i> Kitchen Board
            </a>
            <a href="{{ route('rst.sales.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-list-alt" aria-hidden="true"></i> Sale List
            </a>
        </x-slot>

        <x-alert-message />

        <div id="mm-fullscreen-hud" class="mm-no-print" role="region" aria-label="Focused fullscreen controls">
            <div class="mm-fullscreen-hud-left">
                <span class="mm-fullscreen-hud-badge"><i class="fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen Mode</span>
                <span><strong>Restaurant POS</strong> · Distraction-Free Cashier Terminal (Press <code>Esc</code> or <code>Alt+Z</code> to exit)</span>
            </div>
            <button type="button" id="mm-fullscreen-exit-btn" data-mm-fullscreen-exit class="mm-fullscreen-exit-btn">
                <i class="fa fa-compress" aria-hidden="true"></i> Exit Fullscreen
            </button>
        </div>

        <x-mm.stepper :steps="[
            ['key' => 'courses', 'label' => '1. Guest, Room & Table'],
            ['key' => 'fire', 'label' => '2. Dishes & Courses'],
            ['key' => 'pay', 'label' => '3. Bill & Payment'],
        ]" current="courses" />

        <div class="mm-pos-advanced-bar mm-no-print" role="region" aria-label="Advanced POS controls">
            <div class="mm-pos-adv-group">
                <span class="mm-pos-adv-label"><i class="fa fa-clock-o" aria-hidden="true"></i> Course Pacing</span>
                <select id="mm-pos-course-pacing" class="form-control" aria-label="Course pacing">
                    <option value="starters">Course 1 · Starters First</option>
                    <option value="mains">Course 2 · Main Course Together</option>
                    <option value="dessert">Course 3 · Dessert &amp; Coffee Hold</option>
                    <option value="rush">VIP Rush · Fire All Together</option>
                </select>
            </div>
            <div class="mm-pos-adv-group">
                <span class="mm-pos-adv-label"><i class="fa fa-leaf" aria-hidden="true"></i> Kitchen Modifiers</span>
                <div class="mm-pos-mod-chips">
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="No Nuts / Allergy">No Nuts / Allergy</button>
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="Less Spicy">Less Spicy</button>
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="Extra Hot">Extra Hot</button>
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="VIP Gold Plating">VIP Gold Plating</button>
                </div>
                <span id="mm-pos-active-modifiers" class="mm-pos-split-readout">Active: None</span>
            </div>
            <div class="mm-pos-adv-group">
                <span class="mm-pos-adv-label"><i class="fa fa-users" aria-hidden="true"></i> Split By Seat</span>
                <select id="mm-pos-split-seats" class="form-control" aria-label="Split bill by seats">
                    <option value="1">1 Seat · No Split</option>
                    <option value="2">2 Seats · Equal Split</option>
                    <option value="3">3 Seats · Equal Split</option>
                    <option value="4">4 Seats · Equal Split</option>
                </select>
                <span id="mm-pos-split-readout" class="mm-pos-split-readout">Per seat: ৳ 0.00</span>
            </div>
        </div>

        <x-mm.panel class="mm-pos-workspace tw-p-4">
            <div class="row mm-pos-layout">
                {{-- FOR UPDATING ROUTE ON [EDIT-SALE.BLADE.PHP] WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD --}}
                <input type="hidden" value="{{ route('rst.sales-v2.store') }}" id="storeSaleUrl">

                @include('rst/sales-v2/_inc/left-side')

                @include('rst/sales-v2/_inc/right-side')
            </div>
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('js')

    {{-- <script src="{{ asset('assets/custom_js/guest_filter.js') }}"></script> --}}


    @include('rst/sales-v2/_inc/script')

    @include('sales/_inc/script')

    <script>
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            var el = document.getElementById("current-time");
            if (el) el.innerHTML = d.toLocaleTimeString();
        }
    </script>

    <script>
        (function () {
            var chips = document.querySelectorAll('[data-pos-modifier]');
            var modsOut = document.getElementById('mm-pos-active-modifiers');
            function syncMods() {
                var active = [];
                chips.forEach(function (c) { if (c.classList.contains('is-active')) active.push(c.getAttribute('data-pos-modifier')); });
                if (modsOut) modsOut.textContent = 'Active: ' + (active.length ? active.join(' · ') : 'None');
            }
            chips.forEach(function (c) { c.addEventListener('click', function () { c.classList.toggle('is-active'); syncMods(); }); });
            var seats = document.getElementById('mm-pos-split-seats');
            var splitOut = document.getElementById('mm-pos-split-readout');
            function syncSplit() {
                var totalEl = document.getElementById('grandTotal');
                var total = totalEl ? (parseFloat(totalEl.value) || 0) : 0;
                var n = seats ? (parseInt(seats.value, 10) || 1) : 1;
                if (splitOut) splitOut.textContent = 'Per seat: ৳ ' + (total / n).toFixed(2);
            }
            if (seats) seats.addEventListener('change', syncSplit);
            document.addEventListener('change', function (e) { if (e.target && e.target.id === 'grandTotal') syncSplit(); });
            setInterval(syncSplit, 1500);
            syncMods(); syncSplit();
        })();
    </script>

@endsection
