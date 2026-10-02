<div id="guest_image_change{{ $book->id }}" class="modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-eye"></i> Update image of {{ optional($book->guestInfo)->name }}</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <dl id="dt-list-1" class="dl-horizontal">
                            <form action="{{ route('guest-image-update') }}" id="updateGuestImage{{ $book->id }}" class="change-image-of" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $book->id }}">
                                <input type="hidden" name="booking_number" value="{{ $book->booking_number }}">
                                <input type="hidden" name="customer_id" value="{{ $book->customer_id }}">

                                <div class="form-group" style="height: 300px">
                                    <label class="col-sm-1 control-label">Image</label>
                                    <div class="col-xs-5 col-sm-5 image-section"
                                        style="position: relative">

                                        <input type="file" name="image" class="image{{ $book->id }} id-input-file-5">

                                        @include('booking._modal.webcam-modal')

                                        <!-- Button trigger modal for WEBCAM -->
                                        <button type="button" class="btn btn-primary btn-sm webcam-modal-btn"
                                            onclick="configure({{ $book->id }})"
                                            style="position: absolute;top:1px;right:14px;border: none;"
                                            data-toggle="modal" data-target="#webcam-modal{{ $book->id }}">
                                            <i class="fa fa-camera"></i>
                                        </button>


                                        <input type="hidden" name="web_cam" value="0" class="is_web_cam_or_not{{ $book->id }}">
                                        <input type="hidden" name="image" class="image-tag{{ $book->id }}">
                                    </div>
                                    <div class="col-xs-6 col-sm-6 image-section" style="position: relative">
                                        <div id="results"></div>
                                        <a href="javascript:void(0)" class="delete-snap{{ $book->id }}" onclick="deleteSnap({{ $book->id }})"
                                            style="display: none;position: absolute;top:0;right:55px"><i
                                                class="fa fa-times"></i></a>
                                    </div>
                                    {{-- @dd($book->guestImage); --}}
                                    <div class="old-image{{ $book->id }}">
                                        @foreach ($book->guestImage as $Bookimage)
                                        <img height="150" width="200" src="{{ asset($Bookimage->image) }}" alt="{{ optional($book->guest_info)->name }}">
                                        @endforeach
                                    </div>
                                </div>

                            </form>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-sm btn-outline-danger" data-dismiss="modal"><i
                        class="ace-icon fa fa-times"></i>Cancel</button>
                <button class="btn btn-sm btn-outline-success" form="updateGuestImage{{ $book->id }}" type="submit">
                    <i class="ace-icon fa fa-pencil-square"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>
