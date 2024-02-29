{{-- @dd($sales); --}}
<table class="table table-striped table-bordered" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
        <tr>
            <th colspan="8" style="text-align: center">Sale Report</th>
        </tr>
        @endif
        <tr>
            <th width="3%" class="text-center">Sl</th>
            <th class="text-left">Date</th>
            <th class="text-left" >Invoice</th>
            <th class="text-left">Customer</th>
            <th class="text-right">Total Amount</th>
            <th class="text-right">Vat</th>
            <th class="text-right">Service Charge</th>
            <th class="text-right">Waiter Name</th>
            <th class="text-right">Room</th>
            <th class="text-right">Discount</th>
            <th class="text-right">Total Payable</th>
            <th class="text-right">Paid</th>
            <th class="text-right">Due</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_amount = $total_vat_amount = $total_service_amount = $total_payable_amount = $total_discount = $total_paid = $total_due = 0;
        @endphp
        @forelse ($sales as $sale)
        @php
            $total_amount += $amount = $sale->subtotal;
            $total_discount += $discount = $sale->discount;
            $total_vat_amount += $vat_amount = $sale->vat_amount;
            $total_service_amount += $service_amount = $sale->service_amount;
            $total_payable_amount += $payable_amount = $sale->payable_amount;
            $total_paid += $paid_amount = $sale->paid_amount;
            $total_due += $due_amount = $sale->payable_amount - $sale->paid_amount;
        @endphp
            <tr class="odd gradeX">
                <td class="text-center">
                    {{ $loop->iteration }}
                </td>
                <td>{{ $sale->date }}</td>
                <td>{{ $sale->invoice_no }}</td>
                <td>{{ $sale->guest_name }}</td>

                <td class="text-right">
                    {{ number_format($amount, 2) }}
                </td>

                <td class="text-right">
                    {{ number_format($vat_amount, 2) }}
                </td>
                <td class="text-right">
                    {{ number_format($service_amount, 2) }}
                </td>
                <td class="text-right">
                    {{ $sale->waiter_no }}
                </td>
                <td class="text-right">
                    <span class="label label-xs today-checkout">
                        {{ optional(optional(optional($sale->booking)->bookingDetail)->roomNumber)->room_number ?? "N/A" }}
                    </span>
                </td>
                <td class="text-right">
                    {{ number_format($discount, 2) }}
                </td>
                <td class="text-right">
                    {{ number_format($payable_amount, 2) }}
                </td>
                <td class="text-right">
                    {{ number_format($paid_amount, 2) }}
                </td>
                <td class="text-right">
                    {{ $sale->payable_amount > $sale->paid_amount ? number_format(( $sale->payable_amount - $sale->paid_amount ), 2) : '0' }}
                    {{-- {{ number_format($sale->due_amount, 2) }} --}}
                </td>
            </tr>
        @empty
            <x-no-table-record />
        @endforelse
        @if (request('export_type') != 'excel')
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total:</th>
                <th class="text-right"><strong style="font-size:16px">{{ number_format($total_amount, 2) }}</strong></th>
                <th class="text-right"><strong style="font-size:16px">{{ number_format($total_vat_amount, 2) }}</strong></th>
                <th class="text-right"><strong style="font-size:16px">{{ number_format($total_service_amount, 2) }}</strong></th>
                <th class="text-right"><strong style="font-size:16px"></strong></th>
                <th class="text-right"><strong style="font-size:16px"></strong></th>
                <th class="text-right"><strong style="font-size:16px">{{ number_format($total_discount, 2) }}</strong></th>
                <th class="text-right"><strong style="font-size:16px">{{ number_format($total_payable_amount, 2) }}</strong></th>
                <th class="text-right"><strong style="font-size:16px">{{ number_format($total_paid, 2) }}</strong></th>
                <th class="text-right"><strong style="font-size:16px">{{ number_format($total_due, 2) }}</strong></th>
            </tr>
        </tfoot>
        @endif
    </tbody>
</table>


