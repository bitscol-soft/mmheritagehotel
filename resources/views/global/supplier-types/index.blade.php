@extends('layouts.master')
@section('title','Supplier Type')
@section('css')

    <style>
        .bg-dark{
            background-color: #ededed;
        }
    </style>

@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-index" title="Supplier Type">
        <x-mm.panel>
                        <div class="row">
                            @include('partials._alert_message')

                            @if(hasPermission('supplier.types.create', $slugs))
                                <div class="col-sm-6 col-sm-offset-3">
                                <form action="{{ route('supplier-types.store') }}" method="post">
                                    @csrf
                                    <table id="myTable" class="table table-bordered edu1">

                                        <tr>
                                            <th width="5%">SL</th>
                                            <th>Supplier Type Name</th>
                                            <th width="5%"></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                1
                                            </td>
                                            <td>
                                                <input class="form-control input-sm" name="name[]" required type="text" id="">
                                            </td>
                                            <td><button type="button" disabled class="ibtnDel btn btn-minier btn-danger"><i class="fa fa-times-circle"></i></button></td>
                                        </tr>
                                        <tfoot>
                                        <tr>
                                            <td class="text-center" colspan="2">
                                                <button type="submit" class="btn btn-mini btn-primary">
                                                    <i class="fa fa-save"></i> Save
                                                </button>
                                            </td>
                                            <td style="text-align: right;">
                                                <button type="button" class="btn btn-minier btn-inverse" id="add" >
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <form action="">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Supplier Type Name</th>
                                            <td>
                                                <input type="text" class="form-control input-sm" name="name" value="{{ request('name') }}" placeholder="Sample Type Name">
                                            </td>
                                            <td>
                                                <div class="btn-group btn-corner">
                                                    <button class="btn btn-primary btn-minier" title="Search"><i class="fa fa-search"></i></button>
                                                    <a href="{{ route('supplier-types.index') }}" class="btn btn-info btn-minier" title="Refresh"><i class="fa fa-refresh"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td>Total : {{ $supplierTypes->total() }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-dark">SL</th>
                                        <th class="bg-dark">Sample Type Name</th>
                                        <th class="bg-dark">Action</th>
                                    </tr>
                                    @foreach($supplierTypes as $key => $supplierType)
                                        <tr>
                                            <td>{{ $key+$supplierTypes->firstItem() }}</td>
                                            <td>{{ $supplierType->name }}</td>
                                            <td>
                                                @if(hasPermission('supplier.types.edit', $slugs))
                                                    <div class="btn-group btn-corner">
                                                        <a href="#edit{{ $supplierType->id }}" role="button" data-toggle="modal" class="btn btn-primary btn-minier"><i class="fa fa-pencil-square-o"></i></a>
                                                        <button class="btn btn-minier btn-danger" onclick="delete_check({{ $supplierType->id }})" type="button"><i class="fa fa-trash-o"></i></button>
                                                    </div>
                                                @endif

                                                @if(hasPermission('supplier.types.delete', $slugs))
                                                    <form action="{{ route('supplier-types.destroy',$supplierType->id) }}" id="deleteCheck_{{ $supplierType->id }}" method="POST">
                                                        @csrf
                                                        @method("DELETE")
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>


                                        @if(hasPermission('supplier.types.edit', $slugs))
                                            <div id="edit{{ $supplierType->id }}" class="modal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">×</button>
                                                        <h4 class="blue bigger"><i class="fa fa-pencil-square-o"></i> Edit Sample Type </h4>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="row">

                                                            <div class="col-sm-12">

                                                                <form action="{{ route('supplier-types.update', $supplierType->id) }}" method="post" class="form-horizontal">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Name </label>

                                                                        <div class="col-xs-12 col-sm-8 ">
                                                                            <input type="text" class="form-control" name="name" value="{{ $supplierType->name }}" placeholder="Sample Type Name">

                                                                        </div>

                                                                    </div>

                                                                    <br>
                                                                    <br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label"></label>

                                                                        <div class="col-xs-12 col-sm-8">
                                                                            <button class="btn btn-sm btn-primary"><i class="fa fa-pencil-square-o"></i> Update</button>
                                                                        </div>

                                                                    </div>


                                                                </form>

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
                                        @endif

                                    @endforeach
                                </table>

                                @include('partials._paginate', ['data' => $supplierTypes])
                            </div>
                        </div>
        </x-mm.panel>
    </x-mm.page>


@endsection

@section('js')


    

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

    <script type="text/javascript">

        $(document).ready(function () {
            var i = 2;

            $("#add").on("click", function () {
                var newRow = $("<tr id='gg_name'>");
                var cols = "";

                cols += '<td>'+ i +'</td>';
                cols += '<td><input class="form-control input-sm" name="name[]" required type="text" id=""></td>';
                cols += '<td><button type="button" class="ibtnDel btn btn-minier btn-danger"><i class="fa fa-times-circle"></i></button></td>';
                newRow.append(cols);
                $("table.edu1").append(newRow);
                i++;
            });



            $("table.edu1").on("click", ".ibtnDel", function (event) {
                $(this).closest("tr").remove();
                i -= 1
            });


        });

    </script>

    <script type="text/javascript">
        $('[data-rel=popover]').popover({html:true});
    </script>
@endsection


