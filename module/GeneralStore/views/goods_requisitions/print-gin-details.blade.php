<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Print GIN Details</title>
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

        <style>
            @media print {
                .d-print-none {
                    display: none !important;
                }

                .margin-top {
                    margin-top: 20px !important;
                }
                .d-none {
                    display: block !important;
                }
            }
            table {
                border: none !important;
            }
            tr {
                border: none !important;
            }
            .border {
                border: 1px solid gray !important;
            }
            .border-none {
                border: none !important;
            }
        </style>
    </head>
    <body>


    <a role="button" class="btn btn-sm btn-danger d-print-none pull-right" style="margin-right: 20px !important; margin-top: 20px !important;" title="Close" onclick="close_window()">
        <i class="fa fa-times"></i>
    </a>
    @include('partials.print-header', ['company' => $goodsRequisition->company])


    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 margin-top" style="margin-top: 60px">
            <dl id="dt-list-1" class="dl-horizontal">

                @if ($goodsRequisition->issue_number)
                    <h4 class="text-center" style="margin-top: -20px !important;">GIN Details</h4>
                @else
                    <h4 class="text-center" style="margin-top: -20px !important;">Goods Requisition Details</h4>
                @endif
                <p class="text-center" style="margin-top: -10px !important; font-size: 12px">Date : {{ $goodsRequisition->goods_requisition_date }}</p>
                <p class="text-center" style="margin-top: -10px !important; font-size: 12px">Goods Requisition No : {{ $goodsRequisition->form_number }}</p>
                @if ($goodsRequisition->issue_number)
                    <p class="text-center" style="margin-top: -10px !important; font-size: 12px">Issue No{{ $goodsRequisition->issue_number }}</p>
                @endif


                <table class="table">
                    <tr>
                        <th class="border">SL</th>
                        <th class="border">Items</th>
                        <th class="border">Item Unit</th>
                        <th class="border">Remarks</th>
                        <th class="border">Stock In Hand</th>
                        <th class="border">Issue Quantity</th>

                    </tr>
                    @php
                        $total_received_amount = 0;
                        $total_required_quantity = 0;
                        $total_received_quantity = 0;
                    @endphp
                    @foreach ($goodsRequisition->goods_requisition_details as $key => $requisition)
                        <tr>
                            <td class="border">{{ $key + 1 }}</td>
                            <td class="border">{{ $requisition->item->name  }}</td>
                            <td class="border">{{ $requisition->item->item_unit->name   }}</td>
                            <td class="border">{{ $requisition->remarks  }}</td>
                            <td class="border text-center">{{ $requisition->item->current_stock  }}</td>
                            <td class="border text-center">{{ $requisition->quantity  }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border" colspan="5">Total</td>
                        <td class="border text-center">{{ $goodsRequisition->goods_requisition_details->sum('quantity') }}</td>
                    </tr>

                    <tr>
                        <td class="border-none" colspan="4" style="height: 70px !important;"></td>
{{--                        <td class="border-none" colspan="4"></td>--}}
                    </tr>
                    <tr>
                        <td colspan="4" style="border: none !important;">
                            <span style="border-top: 1px solid gray; margin-top: 150px !important;">Received By</span>
                            <br>
                            {{ $goodsRequisition->updated_user->name }}
                            <br>
                            {{ optional(optional($goodsRequisition->created_user->employee)->department)->name }}
                            <br>
                            {{ optional(optional($goodsRequisition->created_user->employee)->designation)->name }}
                        </td>
                        <td colspan="3" class="text-right border-none">
                            <span style="border-top: 1px solid gray; margin-top: 150px !important;">Approved By</span>
                            <br>
                            {{ $goodsRequisition->updated_user->name }}
                            <br>
                            {{ optional(optional($goodsRequisition->updated_user->employee)->department)->name }}
                            <br>
                            {{ optional(optional($goodsRequisition->updated_user->employee)->designation)->name }}
                        </td>
                        <td class="border-none"></td>
                    </tr>
                </table>

            </dl>
        </div>
    </div>

    <script type="text/javascript">

        window.print();

        function close_window() {
            close();
        }
    </script>
    </body>
</html>
