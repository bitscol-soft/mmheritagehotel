<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Item Units</title>

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
        </style>
    </head>
    <body>
        <a class="only-print btn btn-sm btn-primary"  href="{{ url()->previous() }}"> Back </a>
        <table class="table table-striped table-bordered table-hover" >
            <caption class="text-center"><h4><b>Item Unit List</b></h4></caption>
            <thead class="bg-secondary">
                <tr>
                    <th>SL</th>
                    <th>Unit Name</th>
                    <th>Conversion</th>
                    <th>Satatus</th>
                </tr>
            </thead>

            <tbody>
                @foreach($item_units as $key => $item_unit)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $item_unit->name }}</td>
                        <td>{{ $item_unit->conversion }}</td>
                        <td class="text-{{ $item_unit->status ? 'success':'danger' }}">{{ $item_unit->status ? 'Active':'Deactive' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <script type="text/javascript">
            print();
        </script>
    </body>
</html>


