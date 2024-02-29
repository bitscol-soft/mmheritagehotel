<div id="project-details{{ $book->id }}" class="modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-eye"></i> Booking Details</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <dl id="dt-list-1" class="dl-horizontal">
                            <table class="table table-bordered">
                                <tr>
                                    <th>SL</th>
                                    <th>Room Number</th>
                                    <th>Total Guest</th>
                                    <th>Booked Date</th>
                                    <th>Booked Time</th>
                                    <th>Discount Amount<span class="currency-sign"></span></th>
                                    <th class="text-right">Total Amount<span class="currency-sign"></span></th>
                                </tr>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($book->bookingDetails as $i => $data)
                                    @php
                                        $total += $data->total_amount;
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ optional($data->hall)->room_number ?? '' }}</td>
                                        <td>

                                            <div class="action-buttons">
                                                <a href="javascript:void(0)" class="green bigger-140 show-details-btn"
                                                    title="Show Details">
                                                    <i class="ace-icon fa fa-angle-double-down"></i>
                                                    {{ $data->guest_count }}
                                                    <span class="sr-only"> Details</span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $book->check_in_date }}</td>
                                        <td>{{ $book->booked_time }}</td>
                                        <td>{{ calculateCurrencyAmount($data->discount_amount) }}</td>
                                        <td class="text-right">{{ calculateCurrencyAmount($data->total_amount) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" class="text-right">Extra Charge</td>
                                    <td class="text-right">
                                        {{ calculateCurrencyAmount(optional($book->bookingExtraCharge)->extra_amount ?? 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right">Total Amount</td>
                                    <td class="text-right">{{ calculateCurrencyAmount($total) }}</td>
                                </tr>
                            </table>

                        </dl>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
