<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="blue bigger"><i class="fa fa-eye"></i> Night Audit/ Day Closing Report: {{ request('date', optional($audit->first())->date) }}</h4>
        </div>


        <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <dl id="dt-list-1" class="dl-horizontal">
                            <style>
                                .detail-table tbody tr:nth-child(odd){
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
                                        <th style="border:1px solid #28282B; padding: 5px 6px" class="text-center">Room No.</th>
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
                                            $total_amount     = optional($detail->transaction)->total_amount;
                                            $total_collection += $total_paid_amount = optional($detail->transaction)->collection;
                                            $total_due_amount += $due_amount = optional($detail->transaction)->due_amount;
                                        @endphp

                                        <tr>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">{{ $loop->iteration }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">{{ optional($detail->transaction)->source_type }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">{{ optional(optional($detail->transaction)->account)->name ?? 'N\A' }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">
                                                <label class="label label-default">N\A</label>
                                            </td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px; text-align: center;" class="">
                                                <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                            </td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px; text-align: center;" class="">{{ number_format($total_paid_amount, 2) }}</td>
                                            <td style="border:1px solid #28282B; padding: 5px 6px" class="text-center">{{ number_format($due_amount, 2)  }}</td>
                                        </tr>
                                    @empty
                                        <x-no-table-record />
                                    @endforelse

                                </tbody>

                            </table>

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
