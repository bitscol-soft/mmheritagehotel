{{-- money-travel-on-block: bilingual user wiki and help documentation only; no financial calculations --}}
@extends('layouts.master')
@section('title', 'User Wiki & Help (EN / বাংলা)')
@section('content')
<x-mm.styles />
<x-mm.scripts />
<x-mm.page title="User Documentation, Wiki & Help · ব্যবহারকারী ম্যানুয়াল ও সহায়িকা (EN / বাংলা)" description="Bilingual operational guide, SOP checklists, and keyboard shortcuts across all 10 ERP modules.">
    <x-slot name="actions">
        <a href="{{ url('/sop-print') }}" class="mm-button mm-button-secondary"><i class="fa fa-print"></i> Printable SOP Cheat Sheet / প্রিন্ট শিট</a>
        <button type="button" class="mm-button" data-toggle="modal" data-target="#wiki-drawer-audit"><i class="fa fa-check-square-o"></i> Night Audit SOP / নাইট অডিট চেকলিস্ট</button>
    </x-slot>

    <div class="mm-table-kpi-grid" aria-label="Wiki and help summary">
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-book"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Bilingual Wiki / দ্বিভাষিক উইকি</span>
                <div class="mm-table-kpi-value">English + বাংলা <span class="mm-table-kpi-sub">10 Modules Covered</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-th-large"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Interactive Screens / মোট স্ক্রিন</span>
                <div class="mm-table-kpi-value">319 Routes <span class="mm-table-kpi-sub">Full ERP Coverage</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-keyboard-o"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Quick Shortcut / শর্টকাট</span>
                <div class="mm-table-kpi-value">Press g h <span class="mm-table-kpi-sub">Or Ctrl+K Search</span></div>
            </div>
        </div>
        <div class="mm-table-kpi-card">
            <span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-columns"></i></span>
            <div class="mm-table-kpi-meta">
                <span class="mm-table-kpi-label">Slide-Over Drawers / ড্রয়ার</span>
                <div class="mm-table-kpi-value">45 Partials <span class="mm-table-kpi-sub">Zero Page Reload</span></div>
            </div>
        </div>
    </div>

    <x-mm.panel>
        <div class="mm-preview-hub-bar">
            <div class="input-group" style="max-width:440px;flex:1 1 280px">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="search" id="mm-wiki-search" class="form-control" placeholder="Search help topics in English or বাংলা (e.g., বুকিং, পিওএস, Night Audit)...">
            </div>
            <div class="mm-preview-filter-pills" style="margin:0">
                <button type="button" class="mm-preview-pill is-active" data-lang-mode="both">Both (EN + বাংলা)</button>
                <button type="button" class="mm-preview-pill" data-lang-mode="en">English Only</button>
                <button type="button" class="mm-preview-pill" data-lang-mode="bn">শুধু বাংলা (Bengali Only)</button>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:14px" id="mm-wiki-grid">
            <article class="mm-panel tw-p-5 wiki-card" data-wiki-search="front desk room board booking wizard রুম বোর্ড বুকিং উইজার্ড">
                <span class="mm-badge mm-badge-paid">Front Desk / ফ্রন্ট ডেস্ক</span>
                <div class="wiki-lang-en" style="margin:10px 0">
                    <h3 class="tw-m-0 tw-mb-1 tw-text-base tw-font-bold">1. Room Board &amp; 3-Step Booking Wizard</h3>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Select stay dates on the Room Status Board, pick rooms from the interactive Floor 1–3 Map, attach guest NID/passport details, and record advance deposits.</p>
                </div>
                <div class="wiki-lang-bn" style="border-top:1px dashed rgba(148,163,184,0.35);padding-top:10px">
                    <h4 class="tw-m-0 tw-mb-1 tw-text-sm tw-font-bold">১. রুম স্ট্যাটাস বোর্ড ও ৩-ধাপের বুকিং উইজার্ড</h4>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">রুম বোর্ডে তারিখ নির্বাচন করে খালি রুম দেখুন। এরপর ৩-ধাপের বুকিং উইজার্ডে ফ্লোর ম্যাপ থেকে রুম সিলেক্ট করুন, গেস্টের এনআইডি/পাসপোর্ট তথ্য দিন এবং অগ্রিম পেমেন্ট গ্রহণ করুন।</p>
                </div>
            </article>

            <article class="mm-panel tw-p-5 wiki-card" data-wiki-search="restaurant bar pos kot kitchen kds রেস্তোরাঁ বার পিওএস কিচেন">
                <span class="mm-badge mm-badge-paid">POS &amp; Kitchen / রেস্তোরাঁ ও বার</span>
                <div class="wiki-lang-en" style="margin:10px 0">
                    <h3 class="tw-m-0 tw-mb-1 tw-text-base tw-font-bold">2. Restaurant &amp; Bar POS + 80mm Thermal KOT</h3>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Follow the 3-step POS workflow (Courses → Fire KOT → Pay), monitor live cooking timers on the Kitchen Display Board (KDS), or transfer bills to guest room folios.</p>
                </div>
                <div class="wiki-lang-bn" style="border-top:1px dashed rgba(148,163,184,0.35);padding-top:10px">
                    <h4 class="tw-m-0 tw-mb-1 tw-text-sm tw-font-bold">২. রেস্তোরাঁ ও বার পিওএস এবং ৮০ মিমি থার্মাল রিসিপ্ট</h4>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">টেবিল বা গেস্ট রুম সিলেক্ট করে অর্ডার কিচেন বোর্ডে (KDS) পাঠান এবং ৮০ মিমি থার্মাল রিসিপ্ট প্রিন্ট করুন অথবা রুম ফোলিওতে বিল ট্রান্সফার করুন।</p>
                </div>
            </article>
        </div>
    </x-mm.panel>

    <div id="wiki-drawer-audit" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-moon-o"></i> Daily Night Audit SOP · নাইট অডিট চেকলিস্ট</h4>
                </div>
                <div class="modal-body">
                    <div class="well">
                        <strong>English SOP:</strong><br>
                        1. Verify all expected departures have checked out or extended.<br>
                        2. Close all open Restaurant &amp; Bar POS tables.<br>
                        3. Reconcile Cash, Card POS, and bKash collections.<br>
                        4. Run Night Audit to advance the hotel business date.
                    </div>
                    <div class="well">
                        <strong>বাংলা নির্দেশিকা:</strong><br>
                        ১. আজকের সব চেক-আউট সম্পন্ন বা মেয়াদ বৃদ্ধি হয়েছে কিনা যাচাই করুন।<br>
                        ২. রেস্তোরাঁ ও বার পিওএস-এর সব চলমান টেবিল বিল ক্লোজ করুন।<br>
                        ৩. ক্যাশ, কার্ড ও মোবাইল ব্যাংকিং কালেকশন মিলিয়ে নিন।<br>
                        ৪. নাইট অডিট সম্পন্ন করে পরবর্তী বিজনেস তারিখে যান।
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close / বন্ধ করুন</button>
                </div>
            </div>
        </div>
    </div>
</x-mm.page>
@endsection
