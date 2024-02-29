<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

    //---------------------------------------------------------//
    //                        VIEW MODAL METHOD                //
    //---------------------------------------------------------//
    function showCheckInModal(booking_id){
        $('#check-in').toggle("modal");

        let html  = ``;
        let id    = booking_id;
        let route = `{{ route('check.in.update', ':id') }}`;
        route = route.replace(':id', id);
        console.log(id, route);

        $('#check_in_data').html('');

        html+= `<form action="${route}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <button onclick="closeCheckInModal()" type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="blue bigger"><i class="fa fa-check-square"></i> Check IN Information</h4>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">


                                    <!-- Check In Time -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Check In Time</label>
                                        <div class="col-xs-12 col-sm-8 @error('check_in_time') has-error @enderror">
                                            <div class="input-group">
                                                <input class="form-control date-picker"
                                                    value="{{ old('check_in_time', date('Y-m-d')) }}" name="check_in_time"
                                                    type="text">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>

                                                @error('check_in_time')
                                                    <span class="text-danger">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">

                                    <!-- Check In Note -->
                                    <div class="form-group mt-2">

                                        <label class="col-sm-3 control-label">
                                            Check In Note
                                        </label>
                                        <div class="col-xs-12 col-sm-8">
                                            <textarea name="check_in_note" class="form-control" cols="10" rows="5">{{ old('check_in_note') }}</textarea>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
                                </button>
                                <button onclick="closeCheckInModal()" class="btn btn-sm" data-dismiss="modal">
                                    <i class="ace-icon fa fa-times"></i>
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </form>`;

        $('#check_in_data').append(html);

    }

    //---------------------------------------------------------//
    //                        CLOSE MODAL METHOD                //
    //---------------------------------------------------------//
    function closeCheckInModal(){
        $('#check-in').toggle("modal");

    }










</script>

