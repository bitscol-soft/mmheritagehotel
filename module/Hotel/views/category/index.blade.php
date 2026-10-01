@extends('layouts.master')
@section('title', 'Room Categories List')
@section('page-header')
    <i class="fa fa-gears"></i> Room Categories List
@stop

@section('content')
    <div class="page-header">
        <a class="btn btn-xs btn-info" href="{{ route('hotel-categories.create') }}" style="float: right; margin: 0 2px;"> <i
                class="fa fa-plus"></i> Add New Category </a>
        <h1>
            <i class="fa fa-info-circle green"></i> Room Categories List
        </h1>
    </div>

    <x-alert-message />

    <div class="row">
        <div class="col-xs-12">
            <div class="clearfix">
                <div class="pull-right tableTools-container"></div>
            </div>
            <div>
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="center">SL</th>
                            <th>Name</th>
                            <th class="center">Guest Capacity</th>
                            <th class="hidden-480 center">Price</th>
                            <th class="hidden-480 center">Image</th>
                            <th class="center">Status</th>

                            <th class="center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="center">{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="center">
                                    <p>{{ $item->guest_capacity }} Person</p>
                                    <p style="display:flex; align-items:center; justify-content: center;">
                                        @foreach ($item->roomPrices ?? [] as $roomPrice)
                                            <span class="label label-info" style="display:flex; align-items:center; line-height: 18px; margin:0 3px;"><span>{{ $roomPrice->capacity }}: {{ calculateCurrencyAmount($roomPrice->price) }}</span> <span class="currency-sign"></span></span>
                                        @endforeach
                                    </p>
                                </td>
                                <td class="hidden-480 text-right"><span class="currency-sign"></span> {{ calculateCurrencyAmount($item->price) }}</td>
                                <td class="center">
                                    <img height="60" class="img-fluid"
                                        src="{{ asset('/') }}{{ $item->roomSingleImg->relative_path ?? '' }}{{ $item->roomSingleImg->name ?? 'no-image.jpg' }}"
                                        alt="">
                                </td>
                                <td class="center">
                                    @if ($item->status == 1)
                                        <span class="label label-sm label-success">Active</span>
                                    @else
                                        <span class="label label-sm label-danger">In Active</span>
                                    @endif
                                </td>

                                <td class="center">
                                    <div class="btn-group">
                                        <a class="btn btn-xs btn-success"
                                            href="{{ route('hotel-categories.edit', $item->id) }}">
                                            <i class="ace-icon fa fa-edit"></i>
                                        </a>

                                        <button type="button"
                                            onclick="delete_item(`{{ route('hotel-categories.destroy', $item->id) }}`)"
                                            class="btn btn-xs btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>

    <script>
        $(document).ready( function(){
            $( ".currency-sign" ).each(function() {
                $(this).text(`{!! currencySign() !!}`);
            });
        });
    </script>

    <script type="text/javascript">
        jQuery(function($) {
            var myTable =
                $('#dynamic-table')
                .DataTable({
                    bAutoWidth: false,
                    "aoColumns": [{
                            "bSortable": false
                        },
                        null, null, null, null, null,
                        {
                            "bSortable": false
                        }
                    ],
                    "aaSorting": [],

                    select: {
                        style: 'multi'
                    }
                });
        })
    </script>
@endsection
