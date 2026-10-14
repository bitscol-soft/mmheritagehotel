@extends('layouts.master')

@section('title', 'Kitchen Order List')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-rst mm-rst-kitchen" title="Kitchen orders" description="Orders sent to the kitchen, with their table, amount and status.">
    <x-slot name="actions">
        <a href="#" id="mm-focused-fullscreen-btn" data-mm-fullscreen-toggle aria-pressed="false" class="mm-button mm-button-secondary mm-focused-fs-btn" title="Toggle distraction-free Focused Fullscreen Mode (Alt+Z)">
            <i class="ace-icon fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen
        </a>
    </x-slot>
    <x-alert-message />

    <div id="mm-fullscreen-hud" class="mm-no-print" role="region" aria-label="Focused fullscreen controls">
        <div class="mm-fullscreen-hud-left">
            <span class="mm-fullscreen-hud-badge"><i class="fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen Mode</span>
            <span><strong>Kitchen Order Queue</strong> · Distraction-Free Pass Terminal (Press <code>Esc</code> or <code>Alt+Z</code> to exit)</span>
        </div>
        <a href="#" id="mm-fullscreen-exit-btn" data-mm-fullscreen-exit class="mm-fullscreen-exit-btn">
            <i class="fa fa-compress" aria-hidden="true"></i> Exit Fullscreen
        </a>
    </div>

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
