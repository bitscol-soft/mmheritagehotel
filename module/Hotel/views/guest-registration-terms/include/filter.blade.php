<div class="row">
    <form action="">
        <table class="table table-striped table-bordered table-hover" style="text-align: center">
            <tr>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">Title</span>
                        <input type="text" name="title" class="form-control" value="{{ request('title') }}">
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
