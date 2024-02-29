<div class="row filter-booking">

    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="guest-info">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <td><b style="font-size: 14px">Name</b></td>
                        <td><b style="font-size: 14px">{{ request('reference') }}</b></td>
                    </tr>
                    <tr>
                        <td><b style="font-size: 14px">Total Booking</b></td>
                        <td><b style="font-size: 14px">{{ $booking->count() }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-2"></div>

</div>
