@extends('layouts.master')
@section('title', 'Room Categories List')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-room-inventory" title="Room categories" description="Review room types, guest capacity and configured rates.">
        <x-slot name="actions">
            <a class="mm-button" href="{{ route('hotel-categories.create') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Category
            </a>
        </x-slot>

    <x-alert-message />

    <div class="row">
        <div class="col-xs-12">
            <div class="clearfix">
                <div class="pull-right tableTools-container"></div>
            </div>
            <div class="mm-panel tw-p-4">
                <x-mm.table-scroll label="Room categories">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="center">SL</th>
                            <th scope="col">Name</th>
                            <th scope="col" class="center">Guest Capacity</th>
                            <th scope="col" class="hidden-480 center">Price</th>
                            <th scope="col" class="hidden-480 center">Image</th>
                            <th scope="col" class="center">Status</th>

                            <th scope="col" class="center">Action</th>
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
                                        <a class="btn btn-xs btn-success" aria-label="Edit category"
                                            href="{{ route('hotel-categories.edit', $item->id) }}">
                                            <i class="ace-icon fa fa-edit"></i>
                                        </a>

                                        <button type="button"
                                            onclick="delete_item(`{{ route('hotel-categories.destroy', $item->id) }}`)"
                                            class="btn btn-xs btn-sm btn-danger" title="Delete" aria-label="Delete category">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </x-mm.table-scroll>
            </div>
        </div>
    </div>
    </x-mm.page>
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
