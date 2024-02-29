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
            <img src="{{ count($item_stocks) > 0 ? $item_stocks[0]->item->company->logo == 'default.png' ? asset('uploads/default.png') :  $item_stocks[0]->item->company->logo : asset('uploads/default.png') }}" height="60px" style="position:fixed; margin-top:-20px" alt="">
            <caption class="text-center" style="margin-top:-50px"><h3><b>{{ count($item_stocks) > 0 ? $item_stocks[0]->item->company->name : '' }}</b></h3></caption>
            <caption class="text-center" style="margin-top:-30px !important"><h4><b>Item Ledger</b></h4></caption>
            <thead class="bg-secondary">
                <tr class="page-header">
                    
                </tr>
                <tr>
                    <th>SL</th>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Stock In Hand</th>
                </tr>
            </thead>

            <tbody>
                @foreach($item_stocks as $key => $item_stock)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item_stock->created_at)->format('Y-m-d') }}</td>
                        <td>{{ $item_stock->item->name }}</td>
                        <td>{{ $item_stock->item->item_unit->name }}</td>
                        <td>{{ $item_stock->available_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script type="text/javascript">
            print();
        </script>
    </body>
</html>


