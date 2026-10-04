{{-- Round-6 UI pass: shared print-sheet styling for hall reservation
     invoices (BanquetHall's hall_booking/reservation-invoice.blade).
     Display-only CSS. Mirrors module/Hotel/views/booking/_css/invoice-sheet.blade.php
     so the on-screen and print look is consistent across Hotel + BanquetHall
     reservation confirmations. The pre-round-6 inline <style> block
     (Calistoga font, col-print-N floats, .print-body border, etc.) has
     been removed; equivalent rules live here. --}}
<style>
    .invoice-doc {
        max-width: 840px;
        margin: 0 auto;
        background: #fff;
        color: #1f2a33;
        font-size: 13px;
        padding: 26px 32px 22px;
        border: 1px solid #e4ebf3;
        border-radius: 4px;
    }

    .invoice-doc .inv-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        border-bottom: 2px solid #2f63a8;
        padding-bottom: 14px;
    }

    .invoice-doc .inv-brand h3 {
        margin: 0 0 4px;
        font-weight: 700;
        font-size: 20px;
        color: #2f4d6b;
    }

    .invoice-doc .inv-brand p {
        margin: 0 0 2px;
        color: #5c6f7f;
        font-size: 12px;
    }

    .invoice-doc .inv-doctitle {
        text-align: right;
        min-width: 190px;
    }

    .invoice-doc .inv-doctitle .inv-kind {
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 3px;
        color: #2f63a8;
        margin: 0 0 6px;
        text-transform: uppercase;
    }

    .invoice-doc .inv-doctitle .inv-no {
        font-size: 14px;
        font-weight: 700;
        color: #1f2a33;
        margin-bottom: 2px;
    }

    .invoice-doc .inv-doctitle .inv-printed {
        font-size: 11px;
        color: #8a9bab;
    }

    .invoice-doc .inv-panels {
        display: flex;
        gap: 16px;
        margin: 16px 0 6px;
    }

    .invoice-doc .inv-panel {
        flex: 1;
        background: #f7fafd;
        border: 1px solid #e4ebf3;
        border-radius: 4px;
        padding: 10px 14px;
    }

    .invoice-doc .inv-panel-title {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #2f63a8;
        font-weight: 700;
        margin: 0 0 8px;
    }

    .invoice-doc .inv-defrow {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 3px;
        font-size: 12.5px;
    }

    .invoice-doc .inv-defrow > span:first-child {
        color: #7a8a99;
        white-space: nowrap;
    }

    .invoice-doc .inv-defrow > span:last-child {
        text-align: right;
        font-weight: 600;
        color: #22364a;
    }

    .invoice-doc .inv-content {
        margin: 16px 0 6px;
    }

    .invoice-doc .inv-greeting {
        margin: 0 0 14px;
        font-size: 13.5px;
        line-height: 1.55;
        color: #1f2a33;
    }

    .invoice-doc .inv-section {
        margin: 18px 0 6px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #2f63a8;
        font-weight: 700;
        border-bottom: 1px solid #e4ebf3;
        padding-bottom: 4px;
    }

    .invoice-doc .inv-notes {
        margin: 0 0 6px 18px;
        padding: 0;
        font-size: 12.5px;
        line-height: 1.6;
        color: #1f2a33;
    }

    .invoice-doc .inv-notes li {
        margin: 0 0 2px;
    }

    .invoice-doc .inv-foot {
        margin-top: 18px;
        border-top: 1px dashed #dbe5f1;
        padding-top: 8px;
    }

    .invoice-doc .inv-foot-note {
        margin-bottom: 10px;
        padding: 8px 12px;
        background: #f7fafd;
        border-left: 3px solid #2f63a8;
        font-size: 12.5px;
        color: #37536a;
    }

    .invoice-doc .inv-closing {
        margin: 6px 0 4px;
        font-size: 12.5px;
        line-height: 1.5;
        color: #1f2a33;
    }

    .invoice-doc .inv-signoff {
        margin: 1px 0;
        font-size: 12.5px;
        color: #1f2a33;
    }

    .invoice-doc .inv-sign {
        margin-top: 52px;
        display: flex;
        justify-content: space-between;
        gap: 18px;
        padding: 0 6px;
    }

    .invoice-doc .inv-sign .sig {
        width: 200px;
        text-align: center;
        font-size: 12px;
        border-top: 1px solid #333;
        padding-top: 6px;
        color: #37536a;
    }

    @media print {
        .invoice-doc {
            border: 0;
            border-radius: 0;
            max-width: none;
            padding: 0;
        }
        .invoice-doc .inv-panel {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .widget-header,
        .page-title,
        .breadcrumb,
        .ace-nav,
        .left-menu,
        .navbar,
        .footer {
            display: none !important;
        }
    }
</style>
