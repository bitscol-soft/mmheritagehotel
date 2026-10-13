{{-- money-travel-on-block: reservation tape chart UI view; no financial calculations --}}
@extends('layouts.master')
@section('title', '7-Day Reservation Tape Chart (Plan B · F6)')
@section('content')
<x-mm.styles />
<x-mm.scripts />
<x-mm.page title="7-Day Reservation Tape Chart · ৭-দিনের রুম ক্যালেন্ডার গ্রিড (F6)" description="Visual room × day reservation grid across Floors 1–3 with instant Slide-Over Booking inspection.">
    <x-slot name="actions">
        <a href="{{ url('/wiki') }}" class="mm-button mm-button-secondary"><i class="fa fa-book"></i> Wiki &amp; Help</a>
        <button type="button" class="mm-button" data-toggle="modal" data-target="#tape-booking-drawer"><i class="fa fa-calendar-plus-o"></i> Quick Reservation Drawer</button>
    </x-slot>

    <div class="mm-table-kpi-grid" aria-label="Tape chart summary">
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-calendar"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">7-Day Occupancy / গড় অকুপেন্সি</span>
                <div class="mm-table-kpi-value">78.4% <span class="mm-table-kpi-sub">14 Oct – 20 Oct 2026</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">In-House Stays / অবস্থানরত</span>
                <div class="mm-table-kpi-value">42 Suites <span class="mm-table-kpi-sub">Checked-In</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-sign-in"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Confirmed Arrivals / আগমন</span>
                <div class="mm-table-kpi-value">14 Bookings <span class="mm-table-kpi-sub">Next 72 Hours</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-wrench"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Housekeeping / মেইনটেন্যান্স</span>
                <div class="mm-table-kpi-value">2 Rooms <span class="mm-table-kpi-sub">Inspection Ready</span></div>
            </div>
        </div>
    </div>

    <x-mm.panel>
        <div class="mm-table-scroll">
            <table class="mm-data-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="16%">Suite / Room</th>
                        <th>Wed 14 Oct</th>
                        <th>Thu 15 Oct</th>
                        <th>Fri 16 Oct</th>
                        <th>Sat 17 Oct</th>
                        <th>Sun 18 Oct</th>
                        <th>Mon 19 Oct</th>
                        <th>Tue 20 Oct</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>#101 · Deluxe Twin</strong></td>
                        <td colspan="3"><span class="mm-badge mm-badge-paid">Tanvir Chowdhury (#BK-104 · Checked-In)</span></td>
                        <td colspan="2"><span class="mm-badge mm-badge-partial">Available</span></td>
                        <td colspan="2"><span class="mm-badge mm-badge-paid">Orion Pharma (#BK-118 · Confirmed)</span></td>
                    </tr>
                    <tr>
                        <td><strong>#204 · Heritage Suite</strong></td>
                        <td colspan="4"><span class="mm-badge mm-badge-paid">Brig. Gen. Mahmud (#BK-109 · VIP Stay)</span></td>
                        <td><span class="mm-badge mm-badge-unpaid">HK Turnaround</span></td>
                        <td colspan="2"><span class="mm-badge mm-badge-paid">Diplomatic Envoy (#BK-122)</span></td>
                    </tr>
                    <tr>
                        <td><strong>#302 · Presidential</strong></td>
                        <td colspan="2"><span class="mm-badge mm-badge-partial">Available</span></td>
                        <td colspan="5"><span class="mm-badge mm-badge-paid">Horizon Aviation Crew (#BK-115 · 5 Nights)</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-mm.panel>

    <div id="tape-booking-drawer" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-calendar-check-o"></i> Tape Chart Reservation Inspector</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Selected Suite</label>
                        <input type="text" class="form-control" value="#204 · Heritage Double Suite (Floor 2)" readonly>
                    </div>
                    <div class="form-group">
                        <label>Stay Window</label>
                        <input type="text" class="form-control" value="2026-10-14 → 2026-10-18 (4 Nights)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-mm.page>
@endsection
