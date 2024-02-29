<div class="col-sm-10 col-sm-offset-1">
    <form action="">
        <table class="table table-bordered">
            <tr>
                <td>
                    {{-- <x-widget.text-input-group name="name" title="Product Name" :value="request('name')"/> --}}
                    <x-widget.select-input-group name="id" title="Products" :collections="$products" :selected="request('id')"/>
                </td>
                <td>
                    <x-widget.text-input-group name="barcode" title="Barcode" :value="request('barcode')"/>
                </td>
                <td>
                    <x-widget.select-input-group name="category_id" title="Category" :collections="$categories" :selected="request('category_id')"/>
                </td>
                <td>
                    <div class="btn-group">

                        <a href="{{ request()->url() }}" class="btn btn-danger btn-sm">
                            <i class="fa fa-refresh"></i>
                        </a>
                        <button class="btn-outline-success btn-sm">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
