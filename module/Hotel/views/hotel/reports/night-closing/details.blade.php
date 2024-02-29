<div class="modal fade" id="audit-view-details{{ $audit->id }}" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="blue bigger"><i class="fa fa-eye"></i> Night Audit/ Day Closing Report: {{ request('date', optional($audit)->date) }}</h4>
        </div>


        <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <dl id="dt-list-1" class="dl-horizontal">
                            <style>
                                .detail-table tbody tr:nth-child(even){
                                    background-color: #f4f4f4;
                                }
                            </style>
                            <table class="table-responsive detail-table" style="width: 100%;">
                                <thead style="border: 1px solid #28282B;">
                                    <tr>
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">SL</th>
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">Type</th>
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">Invoice No</th>
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">Payment Type</th>
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">Total Amount (৳)</th>
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">Paid Amount (৳)</th>
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">Due Amount (৳)</th>
                                    </tr>
                                </thead>

                                @php
                                    $total_collection   = $total_due_amount = 0;
                                    $total_amount       = $total_paid_amount = $total_due_amount = 0;
                                @endphp

                                <tbody style="border: 1px solid #28282B;">

                                    @forelse ($audit->details ?? [] as $key => $detail)

                                        @php
                                            $total_amount     = amount($detail->total_amount, optional($detail->transaction)->total_amount);
                                            $total_collection += $total_paid_amount = amount($detail->collection, optional($detail->transaction)->collection);
                                            $total_due_amount += $due_amount = amount($detail->due, optional($detail->transaction)->due_amount);
                                        @endphp

                                        <tr>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">{{ $loop->iteration }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">{{ optional($detail->transaction)->source_type }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">
                                                @foreach (optional($detail->transaction)->transaction_ledgers ?? [] as $ledger)
                                                    {{ optional($ledger->account)->name ?? 'N\A' }}
                                                    @if (!$loop->last) , @endif

                                                @endforeach
                                            </td>

                                            <td style="border:1px solid #28282B; padding: 5px 6px; text-align: center;" class="">
                                                <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                            </td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px; text-align: center;" class="">{{ number_format($total_paid_amount, 2) }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">{{ number_format($due_amount > 0 ? $due_amount : 0, 2)  }}</td>
                                        </tr>
                                    @empty
                                        <x-no-table-record />
                                    @endforelse

                                </tbody>

                            </table>

                            <div class="row" >
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="row" style="display: flex; justify-content: space-between">

                                        <div class="col-sm-8 col-lg-8 col-md-8">
                                            <div class="invoice-price" style="width: 300px; margin-top: 5px;">

                                                @foreach ($account_types as $id => $account_type)
                                                    <div class="row" style="display: flex; justify-content: space-between;">
                                                        <div class="left-side" style="width: 60%; text-align: left;">
                                                            <p><b>{{ $account_type }} Sale Amount</b></p>
                                                        </div>
                                                        <div class="right-side" style="width: 40%; text-align: left;">
                                                            <p><b>: {{ getTotalPaymentAmount($audit->id, $id, 'Booking') }}</b></p>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-lg-4 col-md-4">
                                            <div class="invoice-price" style="width: 315px; margin-top: 5px;">
                                                <div class="row" style="display: flex; justify-content: space-between;">
                                                    <div class="left-side" style="width: 60%; text-align: left;">
                                                        <p><b>Total Collection</b></p>
                                                        <p><b>Total Due</b></p>
                                                    </div>
                                                    <div class="right-side" style="width: 35%; text-align: left;">
                                                        <p><b>: {{ number_format($total_collection, 2) }}</b></p>
                                                        <p><b>: {{ number_format($total_due_amount, 2) }}</b></p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </dl>
                    </div>
                </div>
            </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>


      </div>

    </div>
  </div>
