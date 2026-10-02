@extends('layout.app')
@push('style')
    <style>
        input.form-control.small-box {
            height: 27px;
        }

    </style>
@endpush
@section('content')
    <div id="content" class="content min-form" style="min-height: 700px;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @can('sample-collection-list')
                            <div class="panel-heading-btn pull-right">
                                <a class="btn btn-success btn-sm" href="/inventory-barcodes">All Barcode</a>
                            </div>
                        @endcan
                        <h4 class="panel-title"> Make Barcode For Products</h4>
                    </div>
                    <div class="panel-body" id="tabArea">
                        <form action="{{ route('inventory-barcodes.store') }}" method="post" class="form-horizontal">
                            @csrf
                            {{-- <div class="row"> --}}
                            {{-- <div class="form-group col-md-4"> --}}
                            {{-- <label for="Date">Invoice ID  :</label> --}}
                            {{-- <input class="form-control" readonly type="text" value="{{$serviceSaleItem->serviceSale->invoice_id}}" name="invoice_id" placeholder="Invoice ID" autocomplete="off"/> --}}
                            {{-- <input type="hidden" value="{{$serviceSaleItem->id}}" name="item_id"/> --}}
                            {{-- </div> --}}
                            {{-- <div class="form-group col-md-4"> --}}
                            {{-- <label for="Date">Patient ID  :</label> --}}
                            {{-- <input class="form-control" type="text" name="customer_id" value="{{$serviceSaleItem->serviceSale->patient->name}}" placeholder="Patient ID" required autocomplete="off" readonly/> --}}
                            {{-- </div> --}}
                            {{-- <div class="form-group col-md-4"> --}}
                            {{-- <label for="Date">Test Name  :</label> --}}
                            {{-- <input class="form-control" type="text" name="date" value="{{$serviceSaleItem->service->name}}" placeholder="Test Name" readonly autocomplete="off"/> --}}
                            {{-- </div> --}}
                            {{-- </div> --}}
                            <div class='row'>
                                <br>
                                <div class='col-md-12'>
                                    <table class="table table-bordered table-hover" id="table_auto">
                                        <thead>
                                            <tr>
                                                <th width="30%">Product Name</th>
                                                <th width="10%" class="text-center" colspan="2">Available Quantity</th>
                                                <th width="10%" class="text-center">Barcode</th>
                                                <th width="5%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="account-row">
                                            <tr class="record-row">
                                                <td>
                                                    <input type="text" name="product_name[]" placeholder="Product Name"
                                                        autocomplete="off" class="form-control small-box product-names"
                                                        required>
                                                    <input type="hidden" name="product_id[]" autocomplete="off"
                                                        class="form-control small-box product-ids">
                                                </td>
                                                <td>
                                                    <input type="text" tabindex="-1" name="available_quantity[]" value="0"
                                                        autocomplete="off"
                                                        class="form-control small-box
                                                       available-quantities"
                                                        readonly>

                                                </td>
                                                <td>
                                                    <span class="unit-name">PCS</span>
                                                </td>
                                                <td>
                                                    <input type="number" name="number[]" autocomplete="off"
                                                        class="form-control small-box numbers" required>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-xs addBtn"
                                                        tabindex="-1"> <i class="fa fa-plus"></i> </button>
                                                    <button type="button" class="btn btn-danger btn-xs deleteBtn"
                                                        tabindex="-1"> <i class="fa fa-trash-o"></i> </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6 col-lg-offset-3">
                                    <div class="form-group">
                                        <label class="col-md-12">&nbsp;</label>
                                        <button type="submit" class="btn btn-success col-md-12">Generate
                                            Barcode</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        //Add More Row
        $(document).on('click', '.addBtn', function() {
            $('.account-row').append(`
            <tr class="record-row">
                <td>
                    <input type="text"  name="product_name[]" placeholder="Product Name" autocomplete="off"
                    class="form-control small-box product-names " required>
                    <input type="hidden"  name="product_id[]" autocomplete="off" class="form-control small-box
                    product-ids">
                </td>
                <td>
                    <input type="text" tabindex="-1"  name="available_quantity[]" value="0"  autocomplete="off"
                    class="form-control small-box available-quantities" readonly>

                </td>
                <td>
                    <span class="unit-name">PCS</span>
                </td>
                <td>
                    <input type="number"  name="number[]" autocomplete="off" class="form-control small-box numbers"
                    required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-success btn-xs addBtn" tabindex="-1"> <i class="fa
                    fa-plus"></i> </button>
                    <button type="button" class="btn btn-danger btn-xs deleteBtn" tabindex="-1"> <i class="fa
                    fa-trash-o"></i> </button>
                </td>
            </tr>
        `)
        })




        //User Friendly Configs
        $(document).on('keydown', '.numbers', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                $($('.addBtn')[0]).trigger('click');
                $('.product-names').last().focus();
            }
        });
        $(document).on('keydown', '.numbers, .product-names', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
            }
        });
        //Delete Action on row
        $(document).on('click', '.deleteBtn', function(e) {
            if ($('.record-row').length == 1) {
                alert(`You can't delete the existed last row!`)
                $(this).parents('tr').find('input').val('')
            } else {
                $(this).parents('tr').remove();
            }
        });
    </script>

    <script src="{{ asset('custom_js/loadDetails.js') }}"></script>

    <script>
        loadDetails({
            selector: '.product-names',
            url: '{{ url('/load-barcodeable-products') }}',
            select: function(event, ui) {
                $(event.target).parents('.record-row').find('.product-ids').val(ui.item.data.id)
                $(event.target).parents('.record-row').find('.available-quantities').val(ui.item.data
                    .retail_quantity)
                $(event.target).parents('.record-row').find('.unit-name').text(ui.item.data.unit.name)


                generateBarcodeNumber(ui.item.data.id, event)

            }
        })


        function generateBarcodeNumber(productId, event) {
            $.get(`{{ url('get-barcode') }}/${productId}`,
                function(number) {
                    $(event.target).parents('.record-row').find('.numbers').val(number)
                }
            )
        }
    </script>

@endsection
