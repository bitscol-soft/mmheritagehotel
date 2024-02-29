<div class="col-sm-12 mb-2 ml-4">
    <form method="get">

        <div class="row">






            <!-- Product Name -->
            <div class="col-md-5">
                <div class="input-group">
                    <div class="input-group-addon">
                        <label class="input-group-text">Product </label>
                    </div>

                    <input class="form-control" type="text" value="{{ request('name') }}" id="name" name="name"
                        placeholder="Product Name" autocomplete="off" />
                </div>
            </div>





            <!-- Category -->
            <div class="col-md-3">
                <div class="input-group">
                    <div class="input-group-addon">
                        <label class="input-group-text">Category </label>
                    </div>

                    <input class="form-control" type="text" value="{{ request('category_name') }}" id="name"
                        name="category_name" placeholder="Category Name" autocomplete="off" />
                </div>
            </div>








            <!-- Stock Type -->
            <div class="col-md-2">
                <div class="input-group">
                    <div class="input-group-addon">
                        <label class="input-group-text">Type </label>
                    </div>

                    <select name="is_bar" class="form-control chosen-select-100-percent" data-selected="{{ request('is_bar') }}">
                        <option value="1">Bar</option>
                        <option value="0">Restaurant</option>
                    </select>
                </div>
            </div>







            <!-- Action -->
            <div class="col-md-2">
                <div class="btn-group">
                    <a class="btn btn-sm" href="{{ route('rst.inventory-report.index') }}"><i
                            class="fa fa-refresh"></i></a>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
        </div>
    </form>
</div>
