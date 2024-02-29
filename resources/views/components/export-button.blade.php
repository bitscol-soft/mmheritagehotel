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
        <span class="only-print" onclick="window.print()" style="margin-right: 5px; margin-top:5px; cursor: pointer;">
            <img src="{{ asset('assets/images/export-icons/printer-icon.png') }}">
        </span>
    @endif
</div>
