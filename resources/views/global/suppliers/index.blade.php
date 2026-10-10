
@extends('layouts.master')
@section('title',' Supplier')
@section('css')

@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-index" title="Suppliers">
        <x-slot name="actions">
            @if (hasPermission("suppliers.view", $slugs))
                <a class="btn btn-sm btn-primary" href="{{ route('suppliers.create') }}">
                    <i class="fa fa-plus"></i> Add @yield('title')
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="data-table" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($suppliers as $key => $supplier)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->supplier_type->name }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        @if (hasPermission("suppliers.view", $slugs))
                                            <a href="#view-details{{ $supplier->id }}" role="button" data-toggle="modal" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif

                                        @if (hasPermission("suppliers.edit", $slugs))
                                            <a href="{{ route('suppliers.edit',$supplier->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif

                                        @if (hasPermission("suppliers.delete", $slugs))
                                            <button type="button" onclick="delete_check({{ $supplier->id }})" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <form action="{{ route('suppliers.destroy',$supplier->id)}}" id="deleteCheck_{{ $supplier->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            {{-- export/print/save --}}
            {{-- <div class="pull-right" style="margin-top:-20px">
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/excel-icon.png') }}" alt="excel"></a>
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" alt="pdf"></a>
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/word-icon.png') }}" alt="word"></a>
                <a class="btnPrint" href="{{ route('print.suppliers') }}" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/printer-icon.png') }}" alt="print"></a>
            </div> --}}

        </div>
    </div>
        </x-mm.panel>
    </x-mm.page>

    
    @foreach($suppliers as $supplier)

        <div id="view-details{{ $supplier->id }}" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="blue bigger"><i class="fa fa-eye"></i> View @yield('title') Details</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-12">

                                <dl id="dt-list-1" class="dl-horizontal">

                                    <dt>Group</dt>
                                    <dd>{{ $supplier->group->name }}</dd>

                                    <dt>Name</dt>
                                    <dd>{{ $supplier->name }}</dd>

                                    <dt>Phone</dt>
                                    <dd>{{ $supplier->phone }}</dd>

                                    <dt>Email</dt>
                                    <dd>{{ $supplier->email }}</dd>

                                    <dt>Address</dt>
                                    <dd>{{ $supplier->address }}</dd>

                                    <dt>Attention</dt>
                                    <dd>{{ $supplier->attention }}</dd>

                                    <dt>Created At</dt>
                                    <dd>{{ \Carbon\Carbon::parse($supplier->created_at)->format('F d, Y h:i s A') }}</dd>

                                    <dt>Updated At</dt>
                                    <dd>{{ Carbon\Carbon::parse($supplier->updated_at)->format('F d, Y h:i s A') }}</dd>

                              </dl>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm" data-dismiss="modal">
                            <i class="ace-icon fa fa-times"></i>
                            Cancel
                        </button>

                    </div>
                </div>
            </div>

        </div>

    @endforeach

@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    

    <script src="{{ asset('assets/custom_js/custom-datatable.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">

        function delete_check(id)
        {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width:400,
            }).then((result) =>{
                if(result.value){
                    $('#deleteCheck_'+id).submit();
                }
            })

        }

    </script>

@stop
