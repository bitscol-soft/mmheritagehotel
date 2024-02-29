
<div class="col-sm-12 edit-panel">
    <form action="" class="edit-form" data-base_route="{{ route('sms-apis.index') }}" method="post">
        @csrf @method('PUT')
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
                    <input type="text" class="form-control input-sm form-control-sm name" autocomplete="off" name="name" >
                </td>
                <td>
                    <input type="text" class="form-control input-sm form-control-sm username" autocomplete="off" name="username">
                </td>
                <td>
                    <input type="text" class="form-control input-sm form-control-sm password" autocomplete="off" name="password">
                </td>
                <td>
                    <input type="text" class="form-control input-sm form-control-sm sender_number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" autocomplete="off" name="sender_number">
                </td>
                <td>
                    <textarea rows="2" class="form-control url" name="url"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <button type="button" class="btn btn-primary btn-xs pull-right update-api"><i class="fa fa-edit"></i> Update</button>
                    <button type="button" class="btn btn-danger btn-xs pull-right close-create-panel" style="margin-right: 5px !important;" ><i class="fa fa-close"></i> Close</button>
                </td>
            </tr>
        </table>
    </form>
</div>