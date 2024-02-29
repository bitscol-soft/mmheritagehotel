<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Groups</title>

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
            <caption class="text-center"><h4><b>Group List</b></h4></caption>
            <thead class="bg-secondary">
                <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created By</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @foreach($groups as $key => $group)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->email }}</td>
                        <td>{{ $group->phone }}</td>
                        <td>{{ $group->user ? $group->user->name : ''}}</td>
                        <td>{{ $group->address }}</td>
                        <td>{{ \Carbon\Carbon::parse($group->created_at)->format('Y-d-m') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <script type="text/javascript">
            print();
        </script>
    </body>
</html>


