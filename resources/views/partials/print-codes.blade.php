{{--
    Shared QR + barcode verification block for printed documents
    (POS receipts, booking confirmation, booking receipt, booking
    summary and sale invoices).

    Usage:
        @include('partials.print-codes', [
            'code'  => $sale->invoice_no,        // barcode payload + caption
            'label' => 'Restaurant POS Receipt', // QR payload caption
        ])

    The block is display-only: it adds no form controls, no totals and
    no money expressions to the host document, so the host layout,
    tables and scripts stay untouched. Codes render server-side with
    the existing milon/barcode (DNS1D, Code 128) and
    simplesoftwareio/simple-qrcode (QrCode) packages.
--}}
@php
    $mmPrintCode = trim((string) ($code ?? ''));
    $mmPrintCode = preg_replace('/\s+/', '', $mmPrintCode);
    $mmPrintLabel = trim((string) ($label ?? 'Document'));
    $mmPrintPayload = "M&M HERITAGE HOTEL & SUITES\n" . $mmPrintLabel . "\nRef: " . $mmPrintCode . "\nPlease quote this reference for verification.";
@endphp
@if ($mmPrintCode !== '')
    <div class="mm-print-codes" style="margin-top: 14px; padding-top: 10px; border-top: 1px dashed #000; text-align: center; page-break-inside: avoid;">
        <div style="display: inline-block; vertical-align: top; margin: 0 10px; text-align: center;">
            {!! QrCode::size(72)->margin(0)->generate($mmPrintPayload) !!}
            <div style="font-size: 9px; margin-top: 2px;">Scan to verify</div>
        </div>
        <div style="display: inline-block; vertical-align: top; margin: 0 10px; text-align: center;">
            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($mmPrintCode, 'C128') }}" alt="Barcode {{ $mmPrintCode }}" style="max-width: 180px;">
            <div style="font-size: 10px; letter-spacing: 1px; margin-top: 2px;">{{ $mmPrintCode }}</div>
        </div>
        <div style="font-size: 9px; margin-top: 4px;">{{ $mmPrintLabel }} · Keep this reference for any query.</div>
    </div>
@endif
