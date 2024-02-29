@extends('layouts.master')

@section('title', 'Product Stock In Hand')

@section('page-header')
    <i class="fa fa-info-circle"></i> Product Stock In Hand
@stop




@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
@stop


@section('content')


    <div class="page-header">
        <h1>
            @yield('page-header')&nbsp;
            <span style="font-size: 15px;">(<b>{{ $itemStocks->total() }} </b>Records Found, page
                <b>{{ request('page') ?? 1 }}</b> of <b>{{ $itemStocks->lastPage() }}</b>, Data Show per page
                <b>{{ $itemStocks->perPage() }}</b> ) </span>
        </h1>
    </div>


    <div class="row">
        <form class="form-horizontal" action="" method="get">

            <div class="col-sm-12">
                <table class="table table-bordered">

                    <tr>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon">Company</span>
                                <select name="company_id" id="company_id" class="form-control chosen-select-180">
                                    <option selected disabled>select</option>

                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ request()->company_id == $id ? 'selected' : '' }}>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </td>
                        {{-- <td>
                        <div class="input-group">
                            <span class="input-group-addon">Factories</span>
                            <select id="factory_id" name="factory_id" class="chosen-select-100-percent" data-placeholder="- Select Factory -">
                                <option></option>

                                @foreach ($factories as $id => $name)
                                    <option value="{{ $id }}" {{ request()->facroty_id == $id ? 'selected':'' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td> --}}
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon">Unit</span>
                                <select name="unit_id" class="form-control chosen-select-180">
                                    <option selected value="">select</option>
                                    @foreach ($units as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ request()->unit_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td width="200px">
                            <div class="input-group">
                                <span class="input-group-addon">Product</span>
                                <select name="product_id" class="form-control chosen-select" id="product_id">
                                    <option selected value="">select</option>

                                    @foreach ($products as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ request()->product_id == $id ? 'selected' : '' }}>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </td>

                        <td width="200px">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ request()->url() }}" class="btn btn-xs"><i
                                        class="fa fa-refresh"></i></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-center" style="font-size:20px"><strong>Product Stock In Hand</strong>
                        </td>
                    </tr>

                </table>
            </div>
        </form>
    </div>



    <div class="clearfix"></div>

    <div class="row">
        <div class="col-xs-12">
            <table id="dynamic-table" class="table table-striped table-bordered table-hover">



                <thead>
                    <tr style="background: #C9DAF8 !important; color:black !important">
                        <th>SL</th>
                        <th>Product</th>
                        <th>Unit</th>
                        <th class="text-right">Avg. Rate</th>
                        <th class="text-center">Stock</th>
                        <th class="text-right">Total Avg. Price</th>
                    </tr>
                </thead>




                <tbody>
                    @forelse($itemStocks as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($item->product)->name }}</td>
                            <td>{{ optional(optional($item->product)->unit)->name }}</td>
                            <td class="text-right">{{ $item->avg_rate }}</td>
                            <td class="text-center">{{ number_format($item->stock, 2) }}</td>
                            <td class="text-right">{{ number_format($item->stock * $item->avg_rate, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <b class="text-danger">No records found!</b>
                            </td>
                        </tr>
                    @endforelse
                </tbody>


                <tfoot>
                    {{-- <tr>
                    <td colspan="6" class="text-right">Total</td>
                    <td class="text-center">{{ number_format($totalstock, 2) }}</td>
                    <td class="text-right">{{ number_format($totalavgprice, 2) }}</td>
                </tr> --}}
                </tfoot>
            </table>



            <span class="only-print" id="print_btn" style="margin-right: 5px; margin-top:5px; cursor: pointer;">
                <img src="{{ asset('assets/images/export-icons/printer-icon.png') }}">
            </span>


            @include('partials._paginate', ['data' => $itemStocks])
        </div>
    </div>


@endsection

@section('js')



    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script type="text/javascript">
        function exportData(url) {
            $('.exportForm').attr('action', url).submit();
        }

        $('#print_btn').on("click", function() {
            print()
        });
    </script>

    <script type="text/javascript">
        const products = $('#product_id')
        let counter = 0;
        $(document).ready(function() {

            $('#company_id').change(function() {
                
                $.get(`/ajax/company-wise-product?company_id=${$(this).val()}`, function(res) {
                    products.empty().append('<option></option>')
                    res.forEach(function(item) {
                            products.append(`<option value="${item.id}">${item.name}</option>`)
                        })

                    products.trigger('chosen:updated');

                    counter++
                })
            })
        })
    </script>


@stop
