<div id="room-detail-modal" data-backdrop="static" class="modal fade in" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: rgb(41, 4, 77)">
                <button type="button" class="close white" data-dismiss="modal">&times;</button>
                <h4 class="white bigger"><i class="fa fa-info-circle"></i> Room Details</h4>
            </div>


            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-sm-offset-3 my-1">
                        <p>
                            <span class="label label-xlg label-warning arrowed arrowed-right">Reservation</span>
                            <span class="label label-xlg label-danger arrowed arrowed-right">Booked</span>
                            <span class="label label-xlg label-success arrowed arrowed-right">Ready Room</span>
                            <span class="label label-xlg label-inverse arrowed arrowed-right">Dirty</span>
                            <span class="label label-xlg label-purple arrowed arrowed-right">Maintenance</span>

                        </p>
                    </div>
                </div>
                <div class="row">

                    @foreach ($audits ?? [] as $audit)
                    @foreach ($audit->room_details ?? [] as $room)
                        <div class="room-search-list mt-1">
                            <div class="room-list">
                                @php
                                    $status = '';
                                    if ($room->status == 'Check In') {
                                        $status = 'booked';
                                    } elseif ($room->status == 'Maintainance') {
                                        $status = 'orange';
                                    } elseif ($room->status == 'Dirty') {
                                        $status = 'inverse';
                                    } elseif ($room->status == 'Check Out') {
                                        $status = 'successs';
                                    } elseif ($room->status == 'Reserved') {
                                        $status = 'reserve';
                                    } elseif ($room->status == 'Ready') {
                                        $status = 'successs';
                                    }

                                @endphp

                                <div class="col-md-1" style="border-radius: 15px">
                                    <div class="room-info {{ $status }}" style="display: block !important">
                                        <p>
                                            {{ optional($room->room)->room_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @endforeach

                </div>
            </div>


        </div>
    </div>
</div>
