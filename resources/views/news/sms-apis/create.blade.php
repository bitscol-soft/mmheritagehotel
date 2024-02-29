<div class="col-sm-12 create-panel">
    <form action="{{ route('sms-apis.store') }}" class="create-from" method="post">
        @csrf
        <table class="table table-bordered table-sm">
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Password</th>
                <th>Sender Id/Number</th>
                <th>Url</th>
            </tr>
            <tr>
                <td>
                    <input type="text" class="form-control input-sm form-control-sm" autocomplete="off" name="name" >
                </td>
                <td>
                    <input type="text" class="form-control input-sm form-control-sm" autocomplete="off" name="username">
                </td>
                <td>
                    <input type="password" class="form-control input-sm form-control-sm" autocomplete="off" name="password">
                </td>
                <td>
                    <input type="text" class="form-control input-sm form-control-sm" onkeypress="return event.charCode >= 48 && event.charCode <= 57" autocomplete="off" name="sender_number">
                </td>
                <td>
                    <textarea rows="2" class="form-control" name="url"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <button type="button" class="btn btn-primary btn-xs pull-right create-api"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-danger btn-xs pull-right close-create-panel" style="margin-right: 5px !important;" ><i class="fa fa-close"></i> Close</button>
                </td>
            </tr>
        </table>
    </form>
</div>
