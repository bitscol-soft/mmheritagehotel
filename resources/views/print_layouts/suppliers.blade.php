<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Supplier List</title>

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
        <div class="px-2">
            <table class="table table-striped table-bordered" >
            <caption class="text-center"><h4><b>Supplier List</b></h4></caption>
            <thead class="bg-secondary">
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Group</th>
                    <th>Type</th>
                    <th>Country</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Office</th>
                </tr>
            </thead>

            <tbody>
                @foreach($suppliers as $key => $supplier)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->group->name }}</td>
                        <td>{{ $supplier->supplier_type->name }}</td>
                        <td>{{ $supplier->country->name }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>{{ $supplier->head_office }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        <script type="text/javascript">
            print();
        </script>
    </body>
</html>


