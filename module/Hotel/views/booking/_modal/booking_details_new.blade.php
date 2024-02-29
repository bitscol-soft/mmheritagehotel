<div id="booking-details" class="modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button onclick="closeDetailsModal()" type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-eye"></i> Booking Details</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <dl id="dt-list-1" class="dl-horizontal">
                            <table id="booking-details-table" class="table table-bordered">
                                <thead>
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
                                </thead>

                                <tbody>

                                </tbody>
                                <tfoot>

                                </tfoot>

                            </table>

                        </dl>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button onclick="closeDetailsModal()" class="btn btn-sm" data-dismiss="modal">
                    <i class="ace-icon fa fa-times"></i>
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
