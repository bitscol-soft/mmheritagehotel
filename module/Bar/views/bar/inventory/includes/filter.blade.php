<div class="col-sm-12 mb-2 ml-4">
    <form method="get">

        <div class="row">


            <!-- Product Name -->
            <div class="col-md-4">
                <x-widget.product-select name="id"/>
            </div>





            <!-- Category -->
            <div class="col-md-3">
                <div class="input-group">
                    <div class="input-group-addon">
                        <label class="input-group-text">Category </label>
                    </div>
{{--
                    <input class="form-control" type="text" value="{{ request('category_name') }}" id="name"
                        name="category_name" placeholder="Category Name" autocomplete="off" /> --}}

                        <select name="category_id" class="form-control chosen-select-100-percent" data-selected="{{ request('category_id') }}">
                            @foreach ($categorys as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                    </select>
                </div>
            </div>


            <!-- Stock Type -->
            {{-- <div class="col-md-2">
                <div class="input-group">
                    <div class="input-group-addon">
                        <label class="input-group-text">Type</label>
                    </div>

                    <select name="is_bar" class="form-control chosen-select-100-percent" data-selected="{{ request('is_bar') }}">
                        <option value="1">Bar</option>
                        <option value="0">Restaurant</option>
                    </select>
                </div>
            </div> --}}


            @if (request()->routeIs('bar.report.inventory') || request()->routeIs('rst.report.inventory') || request()->routeIs('rst.report.inventory-ledger'))
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="from_date" class="form-control date-picker" placeholder="From Date" value="{{ request('from_date') }}" autocomplete="off">
                        <span class="input-group-addon"><i class="fas fa-exchange-alt"></i></span>
                        <input type="text" name="to_date" class="form-control date-picker" placeholder="To Date" value="{{ request('to_date') }}" autocomplete="off">
                    </div>
                    {{-- <x-widget.date-filter /> --}}
                </div>
            @endif



            <!-- Action -->
            <div class="col-md-2">
                <div class="btn-group">
                    <a class="btn btn-sm" href="{{ request()->url() }}"><i
                            class="fa fa-refresh"></i></a>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Search</button>
                </div>
            </div>
        </div>
    </form>
</div>
