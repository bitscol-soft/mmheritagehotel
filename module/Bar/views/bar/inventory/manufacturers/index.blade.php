@extends('layouts.master')
@section('title', 'Manufacturer List')

@section('page-header')
    <i class="fa fa-bars"></i> Manufacturer List
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop


@section('content')
    <div class="row">

        @include('bar.inventory.manufacturers.create-modal')



        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('pharmacy.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="#modal-dialog" data-toggle="modal">
                                <i class="fa fa-plus-circle"></i> Add New Manufacturer
                            </a>
                        </span>
                    @endif

                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        @include('partials._alert_message')

                        <div class="row">
                            <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Name</th>
                                        <th width="10%">Status</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manufacturers as $item)
                                        <tr class="odd gradeX">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ status($item->status) }}</td>
                                            <td class="text-center">

                                                <div class="btn-group btn-corner">


                                                    @if (hasPermission('pharmacy.edit', $slugs))
                                                        <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                                            class="btn btn-sm btn-success" title="Edit">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                        </a>
                                                    @endif


                                                    @if (hasPermission('pharmacy.delete', $slugs))
                                                        <button type="button"
                                                            onclick="delete_item(`{{ route('bar.manufacturers.destroy', $item->id) }}`)"
                                                            class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    @endif


                                                </div>
                                                @include('inventory.manufacturers.edit-modal')
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')
@endsection
