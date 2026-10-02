@extends('layouts.master')
@section('title', 'Stock Adjustment List')
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

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-inv" title="Stock adjustments" description="Stock adjustments and their approval status.">
    <x-slot name="actions">
        @if (hasPermission('rst.stock-adjustment.create', $slugs))
                <a href="{{ route('rst.stock-adjustment.create') }}" class="mm-button"><i
                        class="ace-icon fa fa-plus mr-1"></i>Stock
                    Adjustment Create</a>
        @endif

    </x-slot>
    <x-mm.panel>
        @include('partials._alert_message')

        <x-mm.table-scroll label="Stock adjustments">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="5%">Sl</th>
                        <th width="11%">Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Total Qty</th>
                        <th>Stock Type</th>
                        <th>Reason</th>
                        {{-- <th>Status</th> --}}
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @php($sl = $stock_adjustment->firstItem())
                    @foreach ($stock_adjustment as $purchase)
                        @foreach ($purchase->adjustment_details as $details)
                            <tr class="odd gradeX">
                                <td>{{ $sl++ }}</td>
                                <td>
                                    {{ $purchase->invoice_no }}
                                </td>
                                <td>{{ $purchase->date }}</td>
                                <td>{{ number_format($purchase->total_amount, 2) }}</td>
                                <td>{{ $purchase->total_qty }}</td>
                                <td>
                                    {{ $details->stock_type }}
                                </td>
                                <td>
                                    {{ $details->adjustment_reason }}
                                </td>

                                <td class="text-center">
                                    {{-- {{ $purchase->current_status }} --}}
                                    {{-- {{ $purchase->current_status == 'Approved' }} --}}
                                    <div class="btn-group">
                                        <a href="{{ route('rst.stock-adjustment.show', $purchase->id) }}"
                                            class="btn btn-xs btn-warning">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('rst.stock-adjustment.delete', $purchase->id) }}"
                                            class="btn btn-xs btn-danger deletable">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </div>
                                    {{-- {{ route('rst.stock-adjustment.edit', $purchase->id) }} --}}
                                    @if ($purchase->current_status == 'Approved')
                                        <button type="button" class="btn btn-xs btn-info" title="Approved"
                                            disabled=""> <i class="fa fa-check-circle"></i></button>
                                        {{-- <a href="{{ route('rst.stock-adjustment.edit', $purchase->id) }}"
                                            class="btn btn-xs btn-warning" title="Pending"><i
                                                class="fa fa-clock-o"></i></a> --}}
                                    @elseif($purchase->current_status == 'Pending')
                                        <a href="{{ route('rst.stock-adjustment.edit', $purchase->id) }}"
                                            class="btn btn-xs btn-warning" title="Pending"><i
                                                class="fa fa-clock-o"></i></a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </x-mm.table-scroll>
        {{ $stock_adjustment->links() }}

    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" /> --}}

    <script type="text/javascript">
        function exportData(url) {
            $('.exportForm').attr('action', url).submit();
        }
    </script>

    <!--  Select Box Search-->
    <script type="text/javascript">
        jQuery(function($) {

            if (!ace.vars['touch']) {
                $('.chosen-select').chosen({
                    allow_single_deselect: true
                });
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({
                                'width': '220px'
                            });
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': '220px'
                        });
                    })
                });
            }
        })
    </script>

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

    <script type="text/javascript">
        // date picker
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd',
        });
    </script>
@stop
