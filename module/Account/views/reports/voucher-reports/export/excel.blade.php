<table class="table table-bordered table-striped" style="margin-bottom: 0">
    <thead>
        <tr class="table-header-bg">
            <th class="text-center">Sl</th>
            <th class="text-center">Date</th>
            <th class="text-center">Voucher No</th>
            <th class="text-center">Voucher Type</th>
            <th class="pl-3">Company</th>
            <th class="pl-3">Description</th>
            <th class="text-right pr-1">Amount</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($vouchers as $voucher)
            @php
                $route = 'voucher-' . strtolower($voucher->voucher_type) . 's.show';
            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $voucher->date }}</td>
                <td class="text-center">{{ $voucher->invoice_no }}</td>
                <td class="text-center">{{ $voucher->voucher_type }}</td>
                <td class="pl-3">{{ optional($voucher->company)->name }}</td>
                <td class="pl-3">{{ $voucher->description }}</td>
                <td class="text-right pr-1">
                    <a href="{{ route($route, $voucher->id) }}" target="_blank">
                        {{ number_format($voucher->amount, 2) }}
                    </a>
                </td>
            </tr>
        @endforeach

        <tr>
            <th class="text-right" colspan="6">Total In Page</th>
            <th class="text-right pr-1">{{ number_format($vouchers->sum('amount'), 2) }}</th>
        </tr>
    </tbody>
</table>