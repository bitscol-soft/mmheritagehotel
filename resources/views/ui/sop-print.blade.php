{{-- money-travel-on-block: printable bilingual SOP quick-reference cheat sheet; no financial calculations --}}
@extends('layouts.master')
@section('title', 'Printable Bilingual SOP Cheat Sheet (EN / বাংলা)')
@section('content')
<x-mm.styles />
<x-mm.scripts />
<x-mm.page title="Bilingual Front-Desk & POS Cheat Sheet · ফ্রন্ট ডেস্ক ও পিওএস কুইক গাইড" description="1-page A4 printable reference sheet for Front Desk, Restaurant POS, Bar Counter, and Night Audit staff.">
    <x-slot name="actions">
        <button type="button" class="mm-button" onclick="window.print()"><i class="fa fa-print"></i> Print A4 Sheet / প্রিন্ট করুন</button>
        <a href="{{ url('/wiki') }}" class="mm-button mm-button-secondary"><i class="fa fa-book"></i> Back to Wiki / উইকি</a>
    </x-slot>

    <div id="print_body" class="invoice-doc">
        <x-mm.print-sheet variant="a4" title="M&M Heritage Hotel · Staff Quick-Reference SOP (EN / বাংলা)">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="22%">Shortcut / শর্টকাট</th>
                        <th width="39%">English Action</th>
                        <th width="39%">বাংলা নির্দেশিকা (Bengali Action)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>Ctrl + K</code> / <code>Cmd + K</code></td>
                        <td>Open Global Command Palette (319 Screens)</td>
                        <td>কমান্ড প্যালেট খুলুন (যেকোনো স্ক্রিনে দ্রুত যান)</td>
                    </tr>
                    <tr>
                        <td><code>g d</code> · <code>g w</code></td>
                        <td>Go to Dashboard (<code>g d</code>) or Booking Wizard (<code>g w</code>)</td>
                        <td>ড্যাশবোর্ড (<code>g d</code>) অথবা ৩-ধাপের বুকিং উইজার্ড (<code>g w</code>)</td>
                    </tr>
                    <tr>
                        <td><code>g r</code> · <code>g b</code> · <code>g k</code></td>
                        <td>Restaurant POS (<code>g r</code>), Bar POS (<code>g b</code>), Kitchen KDS (<code>g k</code>)</td>
                        <td>রেস্তোরাঁ পিওএস (<code>g r</code>), বার পিওএস (<code>g b</code>), কিচেন বোর্ড (<code>g k</code>)</td>
                    </tr>
                    <tr>
                        <td><code>g a</code> · <code>g p</code> · <code>g h</code></td>
                        <td>Account Vouchers (<code>g a</code>), Preview Hub (<code>g p</code>), Wiki (<code>g h</code>)</td>
                        <td>অ্যাকাউন্ট ভাউচার (<code>g a</code>), প্রিভিউ হাব (<code>g p</code>), হেল্প উইকি (<code>g h</code>)</td>
                    </tr>
                    <tr>
                        <td><code>/</code> · <code>?</code> · <code>Esc</code></td>
                        <td>Focus Search (<code>/</code>), Shortcuts Help (<code>?</code>), Close Drawer (<code>Esc</code>)</td>
                        <td>মেনু সার্চ (<code>/</code>), শর্টকাট তালিকা (<code>?</code>), ড্রয়ার বন্ধ (<code>Esc</code>)</td>
                    </tr>
                </tbody>
            </table>
        </x-mm.print-sheet>
    </div>
</x-mm.page>
@endsection
