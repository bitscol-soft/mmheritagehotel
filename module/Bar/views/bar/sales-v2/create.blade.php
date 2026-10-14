@extends('layouts.master')

@section('title', 'Bar POS')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom_css/floating-input.css') }}">
    @include('bar/sales-v2/_inc/style')
@endsection

@section('content')
    <x-mm.styles />
    @include('bar/sales/_inc/guest-modal')

    <x-mm.page class="mm-bar mm-rst mm-rst-pos mm-rst-sale" title="Bar POS" description="Select guest, room or table, add drinks, fire order or collect payment.">
        <x-slot name="actions">
            <button type="button" id="mm-focused-fullscreen-btn" data-mm-fullscreen-toggle aria-pressed="false" class="mm-button mm-button-secondary mm-focused-fs-btn" title="Toggle distraction-free Focused Fullscreen Mode (Alt+Z)">
                <i class="ace-icon fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen
            </button>
            <a href="{{ route('bar.table-manages.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-th-large" aria-hidden="true"></i> Tables
            </a>
            <a href="{{ route('bar.sales.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-list-alt" aria-hidden="true"></i> Sale List
            </a>
        </x-slot>

        <div id="mm-fullscreen-hud" class="mm-no-print" role="region" aria-label="Focused fullscreen controls">
            <div class="mm-fullscreen-hud-left">
                <span class="mm-fullscreen-hud-badge"><i class="fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen Mode</span>
                <span><strong>Skyline Rooftop Bar POS</strong> · Distraction-Free Bartender Terminal (Press <code>Esc</code> or <code>Alt+Z</code> to exit)</span>
            </div>
            <button type="button" id="mm-fullscreen-exit-btn" data-mm-fullscreen-exit class="mm-fullscreen-exit-btn">
                <i class="fa fa-compress" aria-hidden="true"></i> Exit Fullscreen
            </button>
        </div>

        <x-mm.stepper :steps="[
            ['key' => 'courses', 'label' => '1. Guest, Room & Table', 'href' => '#courses'],
            ['key' => 'fire', 'label' => '2. Drinks & Items', 'href' => '#fire'],
            ['key' => 'pay', 'label' => '3. Bill & Payment', 'href' => '#pay'],
        ]" current="courses" />

        <div class="mm-pos-advanced-bar mm-no-print" role="region" aria-label="Advanced bar POS controls">
            <div class="mm-pos-adv-group">
                <span class="mm-pos-adv-label"><i class="fa fa-clock-o" aria-hidden="true"></i> Round Pacing</span>
                <select id="mm-pos-course-pacing" class="form-control" aria-label="Round pacing">
                    <option value="sunset">Round 1 · Sunset Cocktails</option>
                    <option value="bottle">Round 2 · Bottle Service &amp; Mixers</option>
                    <option value="tapas">Tapas &amp; Bar Bites Together</option>
                    <option value="vip">VIP Rooftop Priority</option>
                </select>
            </div>
            <div class="mm-pos-adv-group">
                <span class="mm-pos-adv-label"><i class="fa fa-glass" aria-hidden="true"></i> Mixology Modifiers</span>
                <div class="mm-pos-mod-chips">
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="On the Rocks">On the Rocks</button>
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="Neat / No Ice">Neat / No Ice</button>
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="Extra Lime &amp; Mint">Extra Lime &amp; Mint</button>
                    <button type="button" class="mm-pos-mod-chip" data-pos-modifier="VIP Sparklers">VIP Sparklers</button>
                </div>
                <span id="mm-pos-active-modifiers" class="mm-pos-split-readout">Active: None</span>
            </div>
            <div class="mm-pos-adv-group">
                <span class="mm-pos-adv-label"><i class="fa fa-users" aria-hidden="true"></i> Split Tab By Seat</span>
                <select id="mm-pos-split-seats" class="form-control" aria-label="Split tab by seats">
                    <option value="1">1 Seat · No Split</option>
                    <option value="2">2 Seats · Equal Split</option>
                    <option value="3">3 Seats · Equal Split</option>
                    <option value="4">4 Seats · Equal Split</option>
                </select>
                <span id="mm-pos-split-readout" class="mm-pos-split-readout">Per seat: ৳ 0.00</span>
            </div>
        </div>

        <x-mm.panel class="mm-pos-workspace tw-p-4">
            <x-alert-message />

            <div class="row mm-pos-layout">
                {{-- FOR UPDATING ROUTE ON [EDIT-SALE.BLADE.PHP] WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD --}}
                <input type="hidden" value="{{ route('bar.sales-v2.store') }}" id="storeSaleUrl">

                @include('bar/sales-v2/_inc/left-side')

                @include('bar/sales-v2/_inc/right-side')
            </div>
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')
    @include('bar/sales-v2/_inc/script')
    @include('bar/sales/_inc/script')

    <script>
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            document.getElementById("current-time").innerHTML = d.toLocaleTimeString();
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
            setInterval(syncSplit, 1500);
            syncMods(); syncSplit();
        })();
    </script>
@endsection
