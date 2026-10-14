<div class="pull-left hidden-print" style="margin-top:10px; margin-left:10px">
    @if ($excel == 1)
        <a href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin-right: 5px">
            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
        </a>
    @endif
    @if ($pdf == 1)
        <a href="{{ url()->current() }}?export_type=pdf&{{ request()->getQueryString() }}" target="_blank" style="margin-right: 5px">
            <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}">
        </a>
    @endif
    @if ($print == 1)
        {{-- W3.7: print button now uses data-mm-print (the new pattern
             from W3.1). The data-mm-print hook in public/assets/custom_js/mm-ui.js
             binds the click to window.print(). The ExportService is
             untouched (PDF and Excel still go through the export_type
             query param). --}}
        <button type="button" data-mm-print class="mm-hdr-btn" style="margin-right: 5px; margin-top:5px;" aria-label="Print this report">
            <i class="fa fa-print" aria-hidden="true"></i>
        </button>
    @endif
</div>
