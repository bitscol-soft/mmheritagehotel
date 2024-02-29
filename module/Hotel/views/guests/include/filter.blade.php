<div class="row">
    <form action="">
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">Name</span>
                        <input type="text" name="name" class="form-control" value="{{ request('name') }}">
                    </div>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">Mobile</span>
                        <input type="text" name="phone_no" class="form-control" value="{{ request('phone_no') }}">
                    </div>
                </td>
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">NID</span>
                        <input type="text" name="nid_no" class="form-control" value="{{ request('nid_no') }}">
                    </div>
                </td>
                <td>
                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <a href="{{ request()->url() }}" class="btn btn-sm btn-default">
                            <i class="fa fa-refresh"></i>
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>