

<table class="table table-striped table-bordered table-hover">
      <thead>
            <tr>
                <th style="text-align:center; font-size:30px" colspan="15">{{ optional($selected_item->company)->name }}</th>
            </tr>
            <tr>
                <th style="text-align:center; font-size:20px" colspan="15">'{{ $selected_item->name }}' - Stock Details</th>
            </tr>
            <tr>
                  <th rowspan="2" style="text-align:center; font-size:15px">Date</th>
                  <th colspan="3" style="text-align:center; font-size:15px">Opening Balance</th>
                  <th colspan="4" style="text-align:center; font-size:15px">Receive</th>
                  <th colspan="4" style="text-align:center; font-size:15px">Issue</th>
                  <th colspan="3" style="text-align:center; font-size:15px">Closing Balance</th>
            </tr>
            <tr>
                  <th style="text-align:center; font-size:13px">Qty</th>
                  <th style="text-align:center; font-size:13px">Rate</th>
                  <th style="text-align:center; font-size:13px">Amount</th>
                  <th style="text-align:center; font-size:13px">GRN</th>
                  <th style="text-align:center; font-size:13px">Qty</th>
                  <th style="text-align:center; font-size:13px">Rate</th>
                  <th style="text-align:center; font-size:13px">Amount</th>
                  <th style="text-align:center; font-size:13px">GIN</th>
                  <th style="text-align:center; font-size:13px">Qty</th>
                  <th style="text-align:center; font-size:13px">Rate</th>
                  <th style="text-align:center; font-size:13px">Amount</th>
                  <th style="text-align:center; font-size:13px">Qty</th>
                  <th style="text-align:center; font-size:13px">Rate</th>
                  <th style="text-align:center; font-size:13px">Amount</th>
            </tr>
      </thead>

      <tbody>
            @if (isset($item_stock_details))
                  @php
                        $opening_qty   = $opening_stock;
                        $opening_cost    = $opening_rate;
                        $opening_amount  = $opening_rate * $opening_stock;
                  @endphp
                  @forelse ($item_stock_details as $key => $details)
                        <tr>
                              <td>{{ \Carbon\Carbon::parse($details->created_at)->format('Y-m-d') }}</td>
                              <td>{{ number_format($opening_qty, 2)  }}</td>
                              <td>{{ number_format($opening_cost, 2) }}</td>
                              <td>{{ round($opening_amount, 2)   }}</td>

                              @if ($details->type == "Purchase Receive")
                                    <td>{{ $details->source_number }}</td>
                                    <td>{{ number_format($details->credit_qty, 2) }}</td>
                                    <td>{{ number_format($details->credit_rate, 2) }}</td>
                                    <td>{{ number_format($details->credit_qty * $details->credit_rate, 2)  }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                              @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $details->source_number }}</td>
                                    <td>{{ number_format($details->debit_qty, 2) }}</td>
                                    <td>{{ number_format($details->debit_rate, 2) }}</td>
                                    <td>{{ number_format(($details->debit_qty * $details->debit_rate), 2) }}</td>
                              @endif

                              @php

                              $final_qty = $opening_qty + $details->credit_qty - $details->debit_qty;
                              $final_amount = (($opening_qty * $opening_cost) + ($details->credit_qty * $details->credit_rate) - ($details->debit_qty * $details->debit_rate));
                              if ($final_qty != 0) {
                                    $final_rate = $final_amount / $final_qty;
                              } else {
                                    $final_rate = 0;
                                    $final_amount = 0;
                              }

                              $opening_qty     = $final_qty;
                              $opening_cost    = $final_rate;
                              $opening_amount  = $final_amount;
                              @endphp

                              <td>{{ number_format($final_qty, 2) }}</td>
                              <td>{{ number_format($final_rate, 2) }}</td>
                              <td>{{ number_format($final_amount, 2) }}</td>

                        </tr>
                        @empty
                        <tr>
                              <td>{{ \Carbon\Carbon::parse($selected_item ? $selected_item->created_at : '')->format('y-m-d') }}</td>
                              <td>{{ number_format($opening_stock, 2) }}</td>
                              <td>{{ number_format($opening_cost, 2) }}</td>
                              <td>{{ number_format($opening_rate, 2) }}</td>


                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>

                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>


                              <td>{{ number_format($opening_stock, 2) }}</td>
                              <td>{{ number_format($opening_rate, 2) }}</td>
                              <td>{{ number_format($opening_rate, 2) }}</td>

                        </tr>
                  @endforelse
            @endif

      </tbody>
</table>
