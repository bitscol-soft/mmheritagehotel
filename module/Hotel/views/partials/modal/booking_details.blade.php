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
                                    <th>Room Category</th>
                                    <th>Room Number</th>
                                    <th>Total Guest</th>
                                    <th>Total Infant</th>
                                    <th>Total Night</th>
                                    <th>Discount Amount<span class="currency-sign"></span></th>
                                    <th>Breakfast</th>
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
                                        <td>{{ optional($data->roomCategory)->name }}</td>
                                        <td>{{ optional($data->roomNumber)->room_number ?? '' }}</td>
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
                                        <td>{{ $data->infant_count }}</td>
                                        <td>{{ $data->night_count }}</td>
                                        <td>{{ calculateCurrencyAmount($data->discount_amount) }}</td>
                                        <td class="text-center">
                                            @if ($data->allow_breakfast)
                                                <span
                                                    class="label label-xs label-primary arrowed arrowed-right">Yes</span>
                                            @else
                                                <span
                                                    class="label label-xs label-danger arrowed arrowed-right">No</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ calculateCurrencyAmount($data->total_amount) }}</td>
                                    </tr>
                                    <tr class="detail-row guest-details-tr">
                                        <td colspan="8">
                                            {{-- @dd($book->booking_guests); --}}
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($book->booking_members as $guest)
                                                        <tr>
                                                            <td>{{ $guest->name }}</td>
                                                            <td>{{ $guest->phone }}</td>
                                                            <td>{{ $guest->email }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="8" class="text-right">Extra Charge</td>
                                    <td class="text-right">
                                        {{ calculateCurrencyAmount(optional($book->bookingExtraCharge)->extra_amount ?? 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-right">Total Amount</td>
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
