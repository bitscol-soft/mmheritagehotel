<div id="guest-information-modal" class="guest-information modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: rgb(41, 4, 77)">
                <button type="button" class="close white" data-dismiss="modal">&times;</button>
                <h4 class="white bigger"><i class="glyphicon glyphicon-plus "></i> Add Guest Information</h4>
            </div>

            <div class="modal-body">
               
                    <div class="row">
                        <div class="col-sm-12">


                            
                            <div class="col-sm-9 col-sm-offset-1">

                                <!-- Guest Number Entry Table -->

                                <table class="table table-bordered">

                                    <tbody>
                                        <tr>
                                            <td width="60%">
                                                <div class="input-group">
                                                    <label class="input-group-addon">
                                                        Number Of Guest(<small class="text-danger">exclude you</small>)
                                                    </label>
                                                    <input type="text" name="number_of_guest" class="modal-number-of-guest text-center form-control input-sm">
                                                    
                                                </div>
                                            </td>
                                            

                                            <td style="width: 25%">
                                                <div class="btn-group btn-corner">

                                                    <button type="button" class="btn btn-success btn-sm add-guest-into-table" onclick="addGuest()">
                                                        <i class="fa fa-check"></i> Add Items
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-undo"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>

                            <div class="clearfix" style="margin-bottom: 10px"></div>
                            <div class="col-sm-12 guest-information-modal-body">
                                
                            </div>


                        </div>
                    </div>


                    <div class="form-actions center" style="text-align: right !important;">
                        <div class="btn-group btn-corner">
                            <button type="button" class="btn btn-sm btn-success save-guest-information">
                                <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                Save
                            </button>
                            <button class="btn btn-sm" data-dismiss="modal">
                                <i class="ace-icon fa fa-times"></i>
                                Cancel
                            </button>
                        </div>
                        
                    </div>
            </div>
        </div>
    </div>
</div>

