@extends('layouts.master')
@section('title', 'Hotel Service List')

@section('page-header')
    <i class="fa fa-bars"></i> Hotel Service List
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style>
        .table-bg-color thead th {
            background-color: #4d8cb3 !important;
            color: #fff;
        }
    </style>
@stop


@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    @if (hasPermission('service.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="#modal-dialog" data-toggle="modal">
                                <i class="fa fa-plus-circle"></i> Add New Service
                            </a>
                        </span>
                    @endif

                </div>

                @include('services.category.add-modal')

                <div class="widget-body">
                    <div class="widget-main">
                        @include('partials._alert_message')

                        <div class="row">
                            <table id="data-table" class="table table-striped table-bordered nowrap table-bg-color" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Sl</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Created At</th>
                                        <th class="text-center">Updated At</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php($sl = $services->firstItem())

                                    @forelse ($services as $item)
                                        <tr class="odd gradeX">
                                            <td>{{ $sl++ }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ number_format($item->price) }}</td>
                                            <td class="text-center">
                                                {{ $item->created_at }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->updated_at }}
                                            </td>
                                            <td class="text-center">

                                                <div class="btn-group btn-corner">
                                                    @if (hasPermission('service.edit', $slugs))
                                                        <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                                            class="btn btn-sm btn-success" title="Edit">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                        </a>
                                                    @endif
                                                    @if (hasPermission('service.delete', $slugs))

                                                        <button type="button"
                                                            onclick="delete_item(`{{ route('hotelservice.services.destroy', $item->id) }}`)"
                                                            class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                <!-- #modal-dialog -->
                                                @include('services.category.edit-modal')
                                                <!-- end edit section -->
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-danger text-center" style="font-size: 18px" colspan="30">No Data
                                                Found !</td>
                                        </tr>
                                    @endforelse
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
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
@endsection
