<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Item Ledger</title>

        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <style>
            @media print { 
               .only-print { 
                  visibility: hidden; 
               } 
            } 
            
            body {
                width:90%;
                margin-left:auto;
                margin-right:auto;
            }

            .only-print {
                margin-top:20px;
            }
            caption {
                color:black;
            }
            .page-header {
                position:fixed;
            }
        </style>
    </head>
    <body>
        <a class="only-print btn btn-sm btn-primary" href="{{ url()->previous() }}"> Back </a>
       
        <table class="table table-striped table-bordered table-hover" >
            <img src="{{ $selected_item->company->image == "default.png" ? $selected_item->company->image : asset('uploads/default.png') }}" height="70px" style="" alt="">

            <caption class="text-center" style="margin-top:-90px"><h3><b>{{ $selected_item->company->name }}</b></h3></caption>
            <caption class="text-center" style="margin-top:-30px !important"><h4><strong>@if(isset($selected_item)) {{ "'".$selected_item->name."' -" }} @endif Stock Details </strong></h4></caption>
             <thead>
                    <tr>
                        <th class="text-center" rowspan="2">Date</th>
                        <th class="text-center" rowspan="2">Unit</th>
                        <th class="text-center" colspan="3">Opening Balance</th>
                        <th class="text-center" colspan="4">Receive</th>
                        <th class="text-center" colspan="4">Issue</th>
                        <th class="text-center" colspan="3">Closing Balance</th>
                    </tr>
                    <tr>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>GRN</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>GIN</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>

                <tbody> 
                    @if (isset($item_stock_details))
                        @forelse ($item_stock_details as $key => $details)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($details->created_at)->format('Y-m-d') }}</td>
                            <td>{{ $selected_item->item_unit->name }}</td>
                            <td>{{ $opening_stock }}</td>
                            <td>{{ $opening_rate }}</td>
                            <td>{{ $opening_stock * $opening_rate }}</td>
                            @if ($details->type == "Purchase Receive")
                                <td>{{ $details->source_number }}</td>
                                <td>{{ $details->credit_qty }}</td>
                                <td>{{ $details->credit_rate }}</td>
                                <td>{{ $details->credit_qty * $details->credit_rate }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @else 
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $details->source_number }}</td>
                                <td>{{ $details->debit_qty }}</td>
                                <td>{{ $details->debit_rate }}</td>
                                <td>{{ $details->debit_qty * $details->debit_rate }}</td>
                            @endif
                            
                            @php
                                $opening_stock = $opening_stock + $details->credit_qty - $details->debit_qty;
                                if($details->credit_rate == 0) {
                                    $opening_rate  = ($opening_rate + $details->debit_rate) / 2;
                                } else {
                                    $opening_rate  = ($opening_rate + $details->credit_rate) / 2;
                                }
                                
                            @endphp
                            <td>{{ $opening_stock }}</td>
                            <td>{{ $opening_rate }}</td>
                            <td>{{ $opening_stock * $opening_rate }}</td>
                            
                        </tr>
                        @empty
                        <tr>
                            <td>{{ $selected_item ? $selected_item->created_at : ''}}</td>
                            <td>{{ $selected_item ? $selected_item->item_unit->name : '' }}</td>
                            <td>{{ $opening_stock }}</td>
                            <td>{{ $opening_rate }}</td>
                            <td>{{ $opening_stock * $opening_rate }}</td>
                           
                            
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            
                        
                            <td>{{ $opening_stock }}</td>
                            <td>{{ $opening_rate }}</td>
                            <td>{{ $opening_stock * $opening_rate }}</td>
                            
                        </tr>
                        @endforelse
                    @endif

                </tbody>
        </table>
        <script type="text/javascript">
            print();
        </script>
    </body>
</html>


