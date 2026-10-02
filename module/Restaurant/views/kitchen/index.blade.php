@extends('layouts.master')

@section('title', 'Kitchen Order List')

@section('page-header')
    <i class="fa fa-gears"></i> Kitchen Order List
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-rst mm-rst-kitchen" title="Kitchen orders" description="Orders sent to the kitchen, with their table, amount and status.">
    <x-alert-message />

    <x-mm.panel>
        <x-mm.table-scroll label="Kitchen orders">
            <table id="data-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="1%">Sl</th>
                        <th width="10%" style="text-align: center">Invoice ID</th>
                        <th width="10%" style="text-align: center">Table No</th>
                        <th width="15%" class="text-center">Amount</th>
                        <th width="15%" class="text-center">Date</th>
                        <th class="text-center" width="1%">Status</th>
                        <th class="text-center" width="5%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>
                                {{ ++$loop->index }}
                            </td>
                            <td>
                                {{ $order->invoice_no }}
                            </td>
                            <td>
                                {{ $order->table_no }}
                            </td>
                            <td>
                                {{ $order->total_amount }} Tk
                            </td>
                            <td>
                                {{ $order->date }}
                            </td>
                            <td>
                                {{-- <span class="label label-sm label-danger">Dirty</span> --}}
                                {{ $order->order_status }}
                            </td>
                            <td>
                                <div class="btn-corner text-center">

                                    <a href="{{ route('kit.kitchen.show', $order->id) }}"
                                        target="_blank" class="btn btn-xs btn-outline-success">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    {{-- <a href="#" id="openModalBtn"
                                        class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash-o"></i>
                                    </a> --}}
                                    {{-- <a href="#" onclick="delete_item(``)"
                                        class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash-o"></i>
                                    </a> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>
        </x-mm.table-scroll>
    </x-mm.panel>
</x-mm.page>

<!-- Modal -->

<!-- Button to open the modal -->
{{-- <button id="openModalBtn">Open Modal</button> --}}

<!-- The Modal -->
<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modalContent">
            <!-- Dynamic content will be displayed here -->
        </div>
    </div>
</div>

<!-- Button to trigger the modal -->
{{-- <button id="openModalBtn">Open Modal</button> --}}
@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>


    @include('currency-conversions.inc.script')
    @include('kitchen.inc.script')
    {{-- @include('kitchen.inc.extend-date-modal') --}}


    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }
    </script>
@stop
