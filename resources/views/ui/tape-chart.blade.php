{{-- money-travel-on-block: reservation tape chart UI view with 20 sample rooms and guests; no financial calculations --}}
@extends('layouts.master')
@section('title', '7-Day Reservation Tape Chart (Plan B · F6)')
@section('content')
<x-mm.styles />
<x-mm.scripts />
<x-mm.page title="7-Day Reservation Tape Chart · ৭-দিনের রুম ক্যালেন্ডার গ্রিড (F6)" description="Visual 20-Room × 7-Day availability and stay grid across Floors 1–4 with interactive floor filtering, live search, and Slide-Over Booking inspection.">
    <x-slot name="actions">
        <a href="{{ url('/preview/rooms') }}" class="mm-button mm-button-secondary"><i class="fa fa-bed"></i> 20 Rooms Directory</a>
        <a href="{{ url('/preview/guests') }}" class="mm-button mm-button-secondary"><i class="fa fa-users"></i> 20 Guests CRM</a>
        <a href="{{ url('/preview/bookings') }}" class="mm-button mm-button-secondary"><i class="fa fa-list-alt"></i> 20 Active Bookings</a>
        <button type="button" class="mm-button" data-toggle="modal" data-target="#tape-booking-drawer"><i class="fa fa-calendar-plus-o"></i> Quick Reservation Drawer</button>
    </x-slot>

    <div class="mm-table-kpi-grid" aria-label="Tape chart summary">
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-calendar"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">7-Day Occupancy / গড় অকুপেন্সি</span>
                <div class="mm-table-kpi-value">85.0% <span class="mm-table-kpi-sub">20 Rooms · Floors 1–4</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">In-House Stays / অবস্থানরত গেস্ট</span>
                <div class="mm-table-kpi-value">14 Rooms <span class="mm-table-kpi-sub">Checked-In Guests</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-sign-in"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Confirmed Arrivals / আসন্ন আগমন</span>
                <div class="mm-table-kpi-value">4 Rooms <span class="mm-table-kpi-sub">Pre-Registered VIPs</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-wrench"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Vacant / Turnaround</span>
                <div class="mm-table-kpi-value">2 Rooms <span class="mm-table-kpi-sub">Ready for Walk-In</span></div>
            </div>
        </div>
    </div>

    @php
        $tapeRooms = [
            ['#101', '1', 'Deluxe Twin', '3,600', 'Tanvir Ahmed Chowdhury (#BK-101 · Checked-In)', 3, 'Available', 2, 'Orion Pharma (#BK-121 · Confirmed)', 2, 'paid'],
            ['#102', '1', 'Deluxe King', '4,500', 'Nadia Islam (#BK-102 · Acme Global)', 4, 'Available', 1, 'Dr. Mahmudul Karim (#BK-122)', 2, 'paid'],
            ['#103', '1', 'Deluxe King', '4,500', 'Dr. Farhana Rahman (#BK-103 · VIP Platinum)', 5, 'HK Turnaround', 1, 'Available', 1, 'paid'],
            ['#104', '1', 'Executive Twin', '3,800', 'Available', 1, 'Rafiqul Islam Bhuiyan (#BK-104 · Beximco)', 4, 'Available', 2, 'partial'],
            ['#105', '1', 'Superior Double', '4,200', 'Tahmina Akter (#BK-105 · BRAC Bank)', 3, 'HK Turnaround', 1, 'Anisur Rahman (#BK-123 · Confirmed)', 3, 'paid'],
            ['#106', '1', 'Standard Single', '2,800', 'Available', 2, 'Syed Kamrul Hasan (#BK-106 · Grameenphone)', 3, 'Available', 2, 'partial'],
            ['#201', '2', 'Heritage Suite', '6,500', 'Nusrat Jahan (#BK-107 · UNICEF Mission)', 4, 'Available', 1, 'UN Delegation (#BK-124 · Confirmed)', 2, 'paid'],
            ['#202', '2', 'Heritage Suite', '6,500', 'HK Turnaround', 1, 'Engr. Tariqul Alam (#BK-108 · Summit Power)', 4, 'Available', 2, 'unpaid'],
            ['#203', '2', 'Superior Twin', '3,900', 'Shabnam Mustari (#BK-109 · Biman Crew)', 2, 'Available', 2, 'Biman Flight Crew (#BK-125)', 3, 'paid'],
            ['#204', '2', 'Royal Family Suite', '8,500', 'Brig. Gen. Mahmudul Hasan (#BK-110 · VIP Stay)', 4, 'HK Turnaround', 1, 'Diplomatic Envoy (#BK-126)', 2, 'paid'],
            ['#205', '2', 'Deluxe Double', '4,400', 'Ashraful Alam Siddiqui (#BK-111 · Walton)', 3, 'Available', 2, 'Walton Executive Team (#BK-127)', 2, 'paid'],
            ['#206', '2', 'Executive King', '5,200', 'Available', 1, 'Barrister Zaima Rahman (#BK-112 · Confirmed)', 4, 'Available', 2, 'partial'],
            ['#301', '3', 'Diplomatic Suite', '9,500', 'Amb. Kenji Takahashi (#BK-113 · Embassy of Japan)', 5, 'HK Turnaround', 1, 'JICA Mission (#BK-128)', 1, 'paid'],
            ['#302', '3', 'Presidential Suite', '14,500', 'Available', 2, 'Capt. Jonathan Vance (#BK-114 · Horizon Crew)', 5, '', 0, 'paid'],
            ['#303', '3', 'Executive Twin', '4,800', 'Meher Afroz Shaon (#BK-115 · Berger Paints)', 3, 'Available', 2, 'Berger Regional Summit (#BK-129)', 2, 'paid'],
            ['#304', '3', 'Heritage Double Suite', '7,200', 'Salma Khatun (#BK-116 · UNDP · Departing)', 2, 'Available', 2, 'UNDP Country Team (#BK-130)', 3, 'paid'],
            ['#305', '3', 'Family Interconnecting', '8,200', 'Imran Hossain & Family (#BK-117 · City Group)', 4, 'Available', 3, '', 0, 'paid'],
            ['#401', '4', 'Royal Penthouse', '12,000', 'Available', 1, 'Dr. Ahsan Habib (#BK-118 · icddr,b Keynote)', 4, 'Available', 2, 'partial'],
            ['#402', '4', 'Skyview Executive', '9,800', 'Elena Rostova (#BK-119 · World Bank)', 5, 'Available', 2, '', 0, 'paid'],
            ['#403', '4', 'Grand Presidential', '16,500', 'Rubana Huq (#BK-120 · Mohammadi Group VIP)', 6, 'HK Turnaround', 1, '', 0, 'paid'],
        ];
    @endphp

    <x-mm.panel>
        <div class="mm-preview-hub-bar" style="margin-bottom:12px">
            <div class="input-group" style="max-width:380px;flex:1 1 260px">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="search" id="mm-tape-search" class="form-control" placeholder="Search 20 rooms or guests (e.g., #204, Tanvir, Diplomatic, Penthouse)...">
            </div>
            <div class="mm-preview-filter-pills">
                <button type="button" class="mm-preview-pill is-active" data-tape-floor="all">All Floors (20)</button>
                <button type="button" class="mm-preview-pill" data-tape-floor="1">Floor 1 · Deluxe (6)</button>
                <button type="button" class="mm-preview-pill" data-tape-floor="2">Floor 2 · Suites (6)</button>
                <button type="button" class="mm-preview-pill" data-tape-floor="3">Floor 3 · Diplomatic (5)</button>
                <button type="button" class="mm-preview-pill" data-tape-floor="4">Floor 4 · Penthouse (3)</button>
            </div>
        </div>

        <div class="mm-table-scroll">
            <table class="mm-data-table table table-bordered table-striped" id="mm-tape-table">
                <thead>
                    <tr>
                        <th width="18%">Suite / Room (20)</th>
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
                    @foreach ($tapeRooms as [$rm, $fl, $cat, $rate, $s1, $c1, $s2, $c2, $s3, $c3, $b1])
                        <tr class="tape-room-row" data-floor="{{ $fl }}" data-search="{{ strtolower($rm . ' ' . $cat . ' ' . $s1 . ' ' . $s2 . ' ' . $s3) }}">
                            <td>
                                <strong>{{ $rm }} · {{ $cat }}</strong>
                                <div class="tw-text-xs tw-text-muted">Floor {{ $fl }} · ৳ {{ $rate }}/nt</div>
                            </td>
                            <td colspan="{{ $c1 }}">
                                <span class="mm-badge mm-badge-{{ $s1 === 'Available' ? 'partial' : ($s1 === 'HK Turnaround' ? 'unpaid' : $b1) }}">{{ $s1 }}</span>
                            </td>
                            @if ($c2 > 0)
                                <td colspan="{{ $c2 }}">
                                    <span class="mm-badge mm-badge-{{ $s2 === 'Available' ? 'partial' : ($s2 === 'HK Turnaround' ? 'unpaid' : 'paid') }}">{{ $s2 }}</span>
                                </td>
                            @endif
                            @if ($c3 > 0)
                                <td colspan="{{ $c3 }}">
                                    <span class="mm-badge mm-badge-{{ $s3 === 'Available' ? 'partial' : ($s3 === 'HK Turnaround' ? 'unpaid' : 'paid') }}">{{ $s3 }}</span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
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
                        <input type="text" class="form-control" value="#204 · Royal Family Suite (Floor 2 · ৳ 8,500/nt)" readonly>
                    </div>
                    <div class="form-group">
                        <label>Primary Guest &amp; Booking Reference</label>
                        <input type="text" class="form-control" value="Brig. Gen. Mahmudul Hasan · #BK-2026-110 (VIP Stay)" readonly>
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
{{-- money-travel-on-end --}}
