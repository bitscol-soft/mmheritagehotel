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

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Product Stock In Hand" description="Stock on hand with average rate.">
    <x-mm.panel class="mm-report-filter">
        <form class="form-horizontal mm-setup-filter mm-report-form" action="" method="get">
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

            <div class="btn-group">
                <button class="mm-button" type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </form>
    </x-mm.panel>

    <x-mm.panel class="tw-p-4">
        <p class="text-muted">(<b>{{ $itemStocks->total() }} </b>Records Found, page
                <b>{{ request('page') ?? 1 }}</b> of <b>{{ $itemStocks->lastPage() }}</b>, Data Show per page
                <b>{{ $itemStocks->perPage() }}</b> ) </p>
        <x-mm.table-scroll label="Product Stock In Hand">
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
        </x-mm.table-scroll>

        <span class="only-print" id="print_btn" style="margin-right: 5px; margin-top:5px; cursor: pointer;">
                        <img src="{{ asset('assets/images/export-icons/printer-icon.png') }}">
                    </span>


                    @include('partials._paginate', ['data' => $itemStocks])
    </x-mm.panel>
</x-mm.page>

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
