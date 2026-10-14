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
    $mmPrintCompact = !empty($compact);
    $mmPrintPayload = "M&M HERITAGE HOTEL & SUITES\n" . $mmPrintLabel . "\nRef: " . $mmPrintCode . "\nPlease quote this reference for verification.";
    // Rendered via the generator classes directly (not the QrCode / DNS1D
    // facade aliases) so the block works in every rendering context.
    $mmPrintQr = $mmPrintCode !== '' ? (new \SimpleSoftwareIO\QrCode\Generator())->format('svg')->size($mmPrintCompact ? 54 : 72)->margin(0)->generate($mmPrintPayload) : '';
    $mmPrintBarcode = $mmPrintCode !== '' ? \Milon\Barcode\DNS1D::getBarcodePNG($mmPrintCode, 'C128') : '';
@endphp
@if ($mmPrintCode !== '' && $mmPrintCompact)
    <div class="mm-print-codes-top" style="display: inline-flex; align-items: center; gap: 10px; text-align: center; page-break-inside: avoid; vertical-align: middle;">
        <span style="display: inline-block;">{!! $mmPrintQr !!}</span>
        <span style="display: inline-block; text-align: center;">
            <img src="data:image/png;base64,{{ $mmPrintBarcode }}" alt="Barcode {{ $mmPrintCode }}" style="width: 132px; max-width: 132px; display: block;">
            <span style="font-size: 8.5px; letter-spacing: 0.06em; display: block; margin-top: 2px;">{{ $mmPrintCode }} · Scan to verify</span>
        </span>
    </div>
@elseif ($mmPrintCode !== '')
    <div class="mm-print-codes" style="margin-top: 14px; padding-top: 10px; border-top: 1px dashed #000; text-align: center; page-break-inside: avoid;">
        <div style="display: inline-block; vertical-align: top; margin: 0 10px; text-align: center;">
            {!! $mmPrintQr !!}
            <div style="font-size: 9px; margin-top: 2px;">Scan to verify</div>
        </div>
        <div style="display: inline-block; vertical-align: top; margin: 0 10px; text-align: center;">
            <img src="data:image/png;base64,{{ $mmPrintBarcode }}" alt="Barcode {{ $mmPrintCode }}" style="max-width: 180px;">
            <div style="font-size: 10px; letter-spacing: 1px; margin-top: 2px;">{{ $mmPrintCode }}</div>
        </div>
        <div style="font-size: 9px; margin-top: 4px;">{{ $mmPrintLabel }} · Keep this reference for any query.</div>
    </div>
@endif
