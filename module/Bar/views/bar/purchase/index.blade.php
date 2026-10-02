@extends('layouts.master')
@section('title', 'Purchases')
@section('page-header')
    <i class="fa fa-info-circle"></i> Purchases
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

<x-mm.styles />
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv mm-rst-purchase" title="Purchase list" description="Bar purchases with required and received quantities.">
    <x-slot name="actions">
        @if (hasPermission('bar.purchases.create', $slugs))
                <a class="mm-button" href="{{ route('bar.purchases.create') }}"><i class="ace-icon fa fa-plus mr-1"></i>
                    Add Purchases
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
                <x-mm.table-scroll label="Purchases">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th width="11%">Chalan ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Discount</th>
                                <th>Total Paid</th>
                                <th>Due</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php($sl = $purchases->firstItem())
                            @foreach ($purchases as $purchase)
                                <tr class="odd gradeX">
                                    <td>{{ $sl++ }}</td>
                                    <td>
                                        {{ $purchase->challan_id }}
                                    </td>
                                    <td>{{ $purchase->date }}</td>
                                    <td>{{ number_format($purchase->subtotal, 2) }}</td>
                                    <td>{{ number_format($purchase->discount, 2) }}</td>
                                    <td>{{ $purchase->paid_amount ? number_format($purchase->paid_amount, 2) : 00 }}
                                    </td>
                                    <td>{{ number_format($purchase->due_amount, 2) }}</td>

                                    <td>
                                        <div class="btn-group">
                                            @if (hasPermission('bar.purchases.view', $slugs))
                                            <a href="{{ route('bar.purchases.show', $purchase->id) }}"
                                                class="btn btn-xs btn-warning">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @endif
                                            @if (hasPermission('bar.purchases.delete', $slugs))
                                                <a href="#" onclick="delete_item(`{{ route('bar.purchases.destroy', $purchase->id) }}`)"
                                                    class="btn btn-xs btn-danger deletable">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-mm.table-scroll>
                {{ $purchases->links() }}
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
