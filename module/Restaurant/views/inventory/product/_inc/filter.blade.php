<div class="col-sm-12">
    <form action="">
        <div class="row">
            <div class="col-md-4">
                {{-- <x-widget.text-input-group name="name" title="Product Name" :value="request('name')"/> --}}
                <x-widget.select-input-group name="id" title="Products" :collections="$products" :selected="request('id')"/>

            </div>

            <div class="col-md-3">
                <x-widget.text-input-group name="barcode" title="Barcode" :value="request('barcode')"/>

            </div>

            <div class="col-md-3">
                <x-widget.select-input-group name="category_id" title="Category" :collections="$categories" :selected="request('category_id')"/>

            </div>

            <div class="col-md-2">
                <div class="btn-group">
                    <a href="{{ request()->url() }}" class="btn btn-danger btn-sm" aria-label="Reset">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <button class="btn-outline-success btn-sm" aria-label="Search">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
