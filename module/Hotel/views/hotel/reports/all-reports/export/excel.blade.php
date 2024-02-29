<table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
     <!------- BOOKING START ------->
    <h6 style="width: 100%;text-align: center" class="mb-2">
        <b
            style="padding: 10px 20px; border-radius: 10px; color: #000; border:1px solid #ddd;">
            BOOKING
        </b>
    </h6>
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="7">Booking Report {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th width="3%">SL</th>
            <th>Date</th>
            <th>Invoice</th>
            <th>Payment Way</th>
            <th>Description</th>
            <th>Room No</th>
            <th class="text-right">Balance<span class="currency-sign"></span></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $total_collection = $total_due_amount = 0;
            $sub_total_amount = $total_paid_amount = $total_due_amount = 0;
            $total_discount = 0;
            $total_null = 0;
        @endphp


        @forelse ($transactions->where('source_type', 'Booking') as $key => $transaction)
            @php
                $total += $collection = $transaction->collection ?? 0;
                $sub_total_amount += $total_amount = $transaction->total_amount;
                $total_collection += $paid_amount = $transaction->collection;
                $total_discount += $transaction->discount;


                // $total_null += $transaction->transaction_ledgers->account == null ?  $transaction->collection: 0;


                $total_due_amount += $due_amount = $total_amount - $paid_amount;
                // $total_due_amount += $due_amount = $transaction->due_amount;
                @endphp

            <tr class="odd gradeX">
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ fdate($transaction->date, 'Y-m-d') }}
                </td>
                <td>
                    {{ $transaction->invoice_no }}
                <td>

                    @foreach ($transaction->transaction_ledgers as $trans)
                    {{ $trans->account->name ?? 'N/A' }}
                    @endforeach
                </td>
                <td>
                    {{ $transaction->source_type }}
                </td>
                <td>

                    @foreach ($transaction->source->details as $details )
                    <label class="label label-success" >{{ $details->roomNumber->room_number }}</label>
                    @endforeach
                </td>
                <td class="text-right">
                    {{ calculateCurrencyAmount($collection) }}
                </td>

            </tr>
        @empty
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse


    </tbody>
    @if (request('export_type') != 'excel')
    <tfoot>
        <tr>
            <th colspan="3">
                @foreach ($account_types as $id => $account_type)
                    <div class="row ml-0" style="display: flex; justify-content: space-between;">
                        <div class="left-side" style="width: 60%; text-align: left;">
                            <p><b>{{ $account_type }} Sale Amount</b></p>
                        </div>
                        <div class="right-side" style="width: 40%; text-align: left;">
                            <p><b>: {{ getTotalPaymentTypeAmount($id, 'Booking')['collection'] }}</b></p>
                        </div>
                    </div>
                @endforeach
                <div class="row ml-0" style="display: flex; justify-content: space-between;">
                    <div class="left-side" style="width: 60%; text-align: left;">
                        <p><b> Total Sale Due Amount </b></p>
                    </div>
                    <div class="right-side" style="width: 40%; text-align: left;">
                        <p><b>: {{ getTotalPaymentDueAmount('Booking')['totalDue'] }}</b></p>
                    </div>
                </div>

            </th>
            <th colspan="3" class="text-right">Total this page:</th>
            <th class="text-right">
                {{ calculateCurrencyAmount($total) }}
            </th>
        </tr>
    </tfoot>
    @endif
</table>



<table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
     <!------- BOOKING START ------->
    <h6 style="width: 100%;text-align: center" class="mb-2">
        <b
            style="padding: 10px 20px; border-radius: 10px; color: #000; border:1px solid #ddd;">
            BAR SALE
        </b>
    </h6>
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="7">Booking Report {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th width="3%">SL</th>
            <th>Date</th>
            <th>Invoice</th>
            <th>Payment Way</th>
            <th>Description</th>
            <th>Guest Name</th>
            <th class="text-right">Balance<span class="currency-sign"></span></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $total_collection = $total_due_amount = 0;
            $sub_total_amount = $total_paid_amount = $total_due_amount = 0;
            $total_discount = 0;
            $total_null_bar = 0;
        @endphp


        @forelse ($transactions->where('source_type', 'Bar Sale') as $key => $transaction)

            @php
                $total += $collection = $transaction->collection ?? 0;
                $sub_total_amount += $total_amount = $transaction->total_amount;
                $total_collection += $paid_amount = $transaction->collection;
                $total_discount += $transaction->discount;
                $total_due_amount += $due_amount = $total_amount - $paid_amount;
                // $total_due_amount += $due_amount = $transaction->due_amount;
                // $total_null_bar += $transaction->transaction_ledgers->account == null ?  $transaction->collection: 0;
            @endphp
            <tr class="odd gradeX">
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ fdate($transaction->date, 'Y-m-d') }}
                </td>
                <td>
                    {{ $transaction->invoice_no }}
                <td>
                    @foreach ($transaction->transaction_ledgers as $trans)
                    {{ $trans->account->name ?? 'N/A' }}
                    @endforeach
                    {{-- {{ optional($transaction->transaction_ledgers)->account->name ?? 'N/A' }} --}}
                </td>
                <td>
                    {{ $transaction->source_type }}
                </td>
                <td>
                    {{ optional($transaction->source)->guest_name }}
                </td>
                <td class="text-right">
                    {{ calculateCurrencyAmount($collection) }}
                </td>

            </tr>
        @empty
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse


    </tbody>

    @if (request('export_type') != 'excel')
    <tfoot>
        <tr>
            <th colspan="3">
                @foreach ($account_types as $id => $account_type)
                    <div class="row ml-0" style="display: flex; justify-content: space-between;">
                        <div class="left-side" style="width: 60%; text-align: left;">
                            <p><b>{{ $account_type }} Sale Amount</b></p>
                        </div>

                        <div class="right-side" style="width: 40%; text-align: left;">
                            <p><b>: {{ getTotalPaymentTypeAmount($id, 'Bar Sale')['collection'] }}</b></p>
                        </div>
                    </div>
                @endforeach
                <div class="row ml-0" style="display: flex; justify-content: space-between;">
                    <div class="left-side" style="width: 60%; text-align: left;">
                        <p><b> Total Sale Due Amount </b></p>
                    </div>
                    <div class="right-side" style="width: 40%; text-align: left;">
                        <p><b>: {{ getTotalPaymentDueAmount('Bar Sale')['due'] }}</b></p>
                    </div>
                </div>
            </th>
            <th colspan="3" class="text-right">Total this page:</th>
            <th class="text-right">
                {{ calculateCurrencyAmount($total) }}
            </th>
        </tr>
    </tfoot>
    @endif
</table>
