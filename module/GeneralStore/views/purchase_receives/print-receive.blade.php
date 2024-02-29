<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Print Purchase Receive</title>
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
    @include('partials.print-header', ['company' => $purchaseReceive->company])


    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 margin-top" style="margin-top: 60px">
            <dl id="dt-list-1" class="dl-horizontal">
                <p class="text-center" style="margin-top: -20px !important; font-size: 12px">Date : {{ $purchaseReceive->purchase_receive_date }}</p>
                <p class="text-center" style="margin-top: -10px !important; font-size: 12px">Purchase Number : {{ $purchaseReceive->purchase->form_number }}</p>
                <p class="text-center" style="margin-top: -10px !important; font-size: 12px">GRN Number : {{ $purchaseReceive->form_number }}</p>
                <p class="text-center" style="margin-top: -10px !important; font-size: 12px">Challan Number : {{ $purchaseReceive->purchase_challan_number }}</p>

                <table class="table">
                    <tr>
                        <th class="border">Items</th>
                        <th class="border">Item Unit</th>
                        <th class="border">Vendor</th>
                        <th class="border">Remarks</th>
                        <th class="border">Required Qty</th>
                        <th class="border">Received Qty</th>
                        <th class="border">Rate</th>
                        <th class="border">Total</th>
                    </tr>
                    @php
                        $total_received_amount = 0;
                        $total_required_quantity = 0;
                        $total_received_quantity = 0;
                    @endphp
                    @foreach($purchaseReceive->purchase->purchase_details as $key => $purchase)
                        @php
                            $total_received_amount += ($purchaseReceive->purchase_receive_details[$key]->rate * $purchaseReceive->purchase_receive_details[$key]->quantity);
                            $total_required_quantity += $purchase->quantity;
                            $total_received_quantity += $purchaseReceive->purchase_receive_details[$key]->quantity;
                        @endphp
                        <tr>
                            <td class="border">{{ $purchase->item->name  }}</td>
                            <td class="border">{{ $purchase->item->item_unit->name   }}</td>
                            <td class="border">{{ $purchaseReceive->purchase_receive_details[$key]->supplier->name }}</td>
                            <td class="border">{{ $purchaseReceive->remarks }}</td>
                            <td class="border">{{ $purchase->quantity }}</td>
                            <td class="border">{{ number_format($purchaseReceive->purchase_receive_details[$key]->quantity, 2) }}</td>
                            <td class="border">{{ $purchaseReceive->purchase_receive_details[$key]->rate }}</td>
                            <td class="text-right border">{{ $purchaseReceive->purchase_receive_details[$key]->rate * $purchaseReceive->purchase_receive_details[$key]->quantity }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border" colspan="4">Total</td>
                        <td class="border">{{ $total_required_quantity }}</td>
                        <td class="border" colspan="2">{{ number_format($total_received_quantity, 2) }}</td>
                        <td class="text-right border">{{ $total_received_amount }}</td>
                    </tr>

                    <tr>
                        <td class="border-none" colspan="4" style="height: 70px !important;"></td>
{{--                        <td class="border-none" colspan="4"></td>--}}
                    </tr>
                    <tr>
                        <td colspan="4" style="border: none !important;">
                            <span style="border-top: 1px solid gray; margin-top: 150px !important;">Received By</span>
                            <br>
                            {{ $purchaseReceive->updated_user->name }}
                            <br>
                            {{ optional(optional($purchaseReceive->updated_user->employee)->department)->name }}
                            <br>
                            {{ optional(optional($purchaseReceive->updated_user->employee)->designation)->name }}
                        </td>
                        <td colspan="3" class="text-right border-none"><span style="border-top: 1px solid gray; margin-top: 150px !important;">Approved By</span></td>
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
