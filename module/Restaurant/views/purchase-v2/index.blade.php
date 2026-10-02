@extends('layouts.master')
@section('title', 'Purchase')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <style>
        .bg-dark {
            background-color: #C9DAF8;
        }
    </style>
@stop


@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-purchase" title="Purchase list" description="Restaurant purchases with required and received quantities.">
    @if (hasPermission('rst.purchase.create', $slugs))
        <x-slot name="actions">
            <a class="mm-button" href="{{ route('rst.purchases.create') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> Add Purchase
            </a>
        </x-slot>
    @endif

    @include('partials._alert_message')

    <x-mm.panel class="mm-report-filter">
        <form class="form-horizontal mm-setup-filter mm-report-form" action="{{ route('rst.purchases.index') }}" method="get">
            @csrf
            <div class="mm-report-field">
                <select name="company_id" class="form-control chosen-select" aria-label="Company">
                    <option selected value="">- Select Company -</option>
                    @foreach ($companies as $id => $name)
                        <option value="{{ $id }}"
                            {{ request()->company_id == $id ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group">
                <input type="text" class="form-control input-sm date-picker" name="from_date"
                    value="{{ request('from_date') }}" autocomplete="off" aria-label="From date">
                <span class="input-group-addon">From|To</span>
                <input type="text" class="form-control input-sm date-picker" name="to_date"
                    value="{{ request('to_date') }}" autocomplete="off" aria-label="To date">
            </div>

            <div class="mm-report-field">
                <input name="purchase_number" class="form-control input-sm"
                    placeholder="Search by purchase number" value="{{ request('purchase_number') }}">
            </div>
            {{-- @dd($purchases); --}}

            <div class="btn-group" style="display: flex">
                <button class="mm-button"><i class="fa fa-search"></i> Search</button>
                <a href="{{ route('purchases.index') }}" class="mm-button mm-button-secondary"><i
                        class="fa fa-refresh"></i> Refresh</a>
            </div>
        </form>
    </x-mm.panel>

    <x-mm.panel>
        <x-mm.table-scroll label="Purchases">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr style="background: #C9DAF8 !important; color:black !important">
                        <th>SL</th>
                        <th>Date</th>
                        <th>Purchase Number</th>
                        {{-- <th>{{ $systemSetting->value != null ? $systemSetting->value : 'Reference' }}</th> --}}
                        <th>Company</th>
                        <th>Required Qty</th>
                        <th>Received Qty</th>
                        <th style="width: 150px"></th>
                    </tr>
                </thead>

                {{-- @dd($purchases); --}}
                <tbody>
                    @foreach ($purchases as $key => $purchase)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $purchase->date }}</td>
                            <td class="text-success">
                                {{ $purchase->challan_id }}
                            </td>
                            {{-- <td>{{ $purchase->purchase_reference }}</td> --}}
                            <td>{{ $purchase->company->name }}</td>
                            <td>{{ $purchase->purchase_details->sum('quantity') }}</td>
                            <td>{{ $purchase->purchase_details->sum('quantity') }}</td>

                            <td style="text-align: center">
                                <div class="btn-group btn-corner">
                                    @include('partials.user-logs', ['data' => $purchase])

                                    <a href="{{ route('rst.purchases.show', $purchase->id) }}" class="btn btn-xs btn-purple"
                                        title="View Purchase Details"><i class="fa fa-eye"></i></a>


                                    @if ($purchase->is_approved == 0)
                                        {{-- @if (hasPermission('rst.purchase.edit', $slugs))
                                            <a href="{{ route('rst.purchase.edit', $purchase->id) }}"
                                                class="btn btn-xs btn-success" title="Edit Purchase">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif --}}
                                        {{-- @if (hasPermission('rst.purchase.approve', $slugs))
                                            <a href="{{ route('rst.approve.show', $purchase->id) }}"
                                                class="btn btn-xs btn-success" title="Approve Purchase"
                                                id="approveBtn{{ $purchase->id }}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @endif --}}
                                    @endif


                                    {{-- @if (hasPermission('rst.purchase.create', $slugs))
                                        @if ($purchase->is_approved == 1)
                                            <a href="{{ route('purchase.receives.create', $purchase->id) }}"
                                                onclick="{{ $purchase->is_approved == 0 ? 'return false' : '' }}"
                                                class="btn btn-xs btn-primary" title="Create Purchase Receive">
                                                <i class="fa fa-arrow-down"></i>
                                            </a>
                                        @endif
                                    @endif --}}

                                    {{-- @if ($purchase->is_approved != 0 &&
                                            hasPermission('rst.purchase.approve', $slugs))
                                        <a href="{{ route('rst.unapprove.purchase', $purchase->id) }}"
                                            class="btn btn-xs btn-success" title="Unapprove Purchase"
                                            id="approveBtn{{ $purchase->id }}">
                                            <i class="fa fa-thumbs-down"></i>
                                        </a>
                                    @endif --}}

                                    {{-- @if (hasPermission('purchase.receives.edit', $slugs))
                                        @if (count($purchase->purchase_receives) > 0)
                                            <a href="{{ route('purchase.receive.list', $purchase->id) }}"  class="btn btn-xs btn-primary" title="Show Purchase Receive">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                    @endif --}}

                                    @if (hasPermission('rst.purchase.delete', $slugs) && $purchase->is_approved == 0)
                                        <button type="button" onclick="delete_check({{ $purchase->id }})"
                                            class="btn btn-xs btn-danger" title="Delete Purchase">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    @endif

                                </div>

                                <form action="{{ route('rst.purchases.destroy', $purchase->id) }}"
                                    id="deleteCheck_{{ $purchase->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if (count($purchases) == 0)
                        <tr>
                            <td colspan="10" class="text-center">
                                <b class="text-danger">No data found!</b>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </x-mm.table-scroll>

        @if (count($purchases) > 0)
            @include('partials._paginate', ['data' => $purchases])

            {{-- <div class="pull-left" style="margin-top:10px; margin-left:10px">
                <span onclick="exportData('{{ url('export-gs-as-excel') }}')"
                    style="margin-right: 5px; cursor: pointer;">
                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                </span>
                <span onclick="exportData('{{ url('export-gs-as-pdf') }}')"
                    style="margin-right: 5px; cursor: pointer;">
                    <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}">
                </span>
            </div> --}}

            <form class="exportForm" method="POST">
                @csrf

                <input type="hidden" name="model" value="Purchase List">
                <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                <input type="hidden" name="purchase_number" value="{{ request('purchase_number') }}">
                <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                <input type="hidden" name="to_date" value="{{ request('to_date') }}">
            </form>
        @endif
    </x-mm.panel>

    <input type="hidden" id="csrf" value="{{ csrf_token() }}">
</x-mm.page>

@endsection

@section('js')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>




    <script type="text/javascript">
        $('[data-rel=popover]').popover({
            html: true
        });
    </script>

    <script type="text/javascript">
        // export excel/pdf
        function exportData(url) {
            $('.exportForm').attr('action', url).submit();
        }


        // delete confirm dialog
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
        // chosen select
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
                                'width': '260px'
                            });
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': '260px'
                        });
                    })
                });
            }
        });



        // data picker
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd',
        });
    </script>
@stop
