@extends('layout.app')
@push('style')
    <style>
        input.form-control.small-box {
            height: 27px;
        }

        form .form-group .control-label {
            padding-top: 7px !important;
        }

    </style>
@endpush
@section('content')
    <div id="content" class="content min-form" style="min-height: 700px;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-heading-btn pull-right">
                            <a class="btn btn-success btn-sm"
                                href="/inventory-product-purchases/{!! $invoice->id !!}">View Invoice</a>
                            <a class="btn btn-success btn-sm"
                                href="/inventory-product-purchases/{!! $invoice->id !!}/payments/">Payment List</a>
                        </div>
                        <h4 class="panel-title">Sales Payment</h4>
                    </div>

                    <div class="panel-body" id="tabArea">

                        @if ($invoice->due_amount == 0.0)
                            <form method="get" action="{{ url('inventory-product-purchases') }}">
                            @else
                                <form method="post"
                                    action="{{ action('Restaurant\InventoryPurchasePaymentController@store', $invoice) }}">
                                    @csrf
                        @endif
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Paid to : </label>
                                <input type="text" class="form-control"
                                    value="{{ optional($invoice->manufacturer)->name ?? '' }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="ref_id"> Invoice ID :</label>
                                <input type="text" class="form-control" placeholder="Reference #ID" readonly
                                    value="{{ $invoice->invoice_id }}">
                                <br>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Date">Payment Date :</label>
                                <input class="form-control" type="text" value="{{ $invoice->date->format('d/m/Y') }}"
                                    readonly />
                            </div>
                        </div>


                        <div class='row'>
                            <div class='col-md-7'>
                                <table class="table table-bordered table-hover" id="table_auto">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="2%">SL</th>
                                            <th class="text-center" width="50%">Product Name</th>
                                            <th class="text-center" class="text-center">Price</th>
                                            <th class="text-center" class="text-center">Quantity</th>
                                            <th class="text-center" width="25%" style="text-align:right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="account-row">
                                        <?php
                                        $total = 0;
                                        ?>
                                        @foreach ($invoice->items as $key => $item)
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td class="text-center">{{ $item->product->name }}</td>
                                                <td class="text-center">{{ number_format($item->sales_price, 2) }}</td>
                                                <td class="text-center">{{ $item->retail_quantity }}</td>
                                                <td align="right">{{ number_format($item->price, 2) }} &#x09F3;</td>
                                            </tr>
                                            <?php
                                            $total += $item->price;
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" align="right">Total :</td>
                                            <td align="right">{{ number_format($total, 2) }} &#x09F3;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-5">

                                {{-- @dd($invoice); --}}
                                <div class="form-group aside_system">
                                    <input type="hidden" name="total_amount" value="">
                                    <label class="control-label col-md-4 text-right">Paid Amount :</label>
                                    <div class="input-group col-md-8">
                                        <div class="input-group-addon currency">৳</div>
                                        <input class="form-control" style="text-align:right"
                                            value="{{ $invoice->paid_amount }}" name="totalPayable" id="totalPayable"
                                            placeholder="Total
                                                 Payable" readonly>
                                    </div>
                                </div>
                                <div class="form-group aside_system">
                                    <input type="hidden" name="total_amount" value="">
                                    <label class="control-label col-md-4 text-right">Due Amount :</label>
                                    <div class="input-group col-md-8">
                                        <div class="input-group-addon currency">৳</div>
                                        <input class="form-control" style="text-align:right"
                                            value="{{ $invoice->due_amount }}" name="totalPayable" id="totalPayable"
                                            placeholder="Total Payable" readonly>
                                    </div>
                                </div>

                                @if ($invoice->due_amount != 0.0)
                                    <div class="form-group aside_system">
                                        <label class="control-label col-md-4 text-right">Pay Amount :</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input @if (request('amount')) value="{{ request('amount') }}" readonly @endif style="text-align:right" type="number" min="0"
                                                step="any" class="form-control" name="totalPaid" id="totalPaid"
                                                placeholder="Paid Amount" onpaste="return false;">
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group aside_system">
                                    <div class="input-group col-md-12">
                                        <button type="submit" class="btn btn-primary col-md-8 pull-right"
                                            style="font-weight: bold !important;">
                                            @if ($invoice->due_amount != 0.0) Submit Payment @else Go To List Page @endif
                                        </button>
                                    </div>
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
        $('#account_id').change(function(e) {
            $.getJSON('{{ url('getPaymentMethod') }}', {
                    id: $('#account_id').val()
                },
                function(methods) {
                    $("#method_id").removeAttr("disabled");
                    $('select#method_id').html(
                        $.map(methods, function(method) {
                            return `<option value="${method.id}">${method.method_name}</option>`;
                        }).join('')
                    ).trigger('change')
                }

            )
        })
    </script>
@endsection
