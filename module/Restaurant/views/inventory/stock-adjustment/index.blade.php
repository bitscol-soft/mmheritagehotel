@extends('layouts.master')
@section('css') @include('scroll-css') @endsection
@section('title', 'Stock Adjustment')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-index mm-rst" title="Stock Adjustment">
        <x-slot name="actions">
            @if (hasPermission("inv.stock-adjustments.create", $slugs))
                <a class="btn btn-sm btn-primary" href="{{ route('inv.stock-adjustments.create') }}">
                    <i class="fa fa-plus-circle"></i>
                    Add New
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
    <form action="{{ route('inv.stock-adjustments.index') }}" method="GET">
        <div class="col-sm-12 p-0">
            <table class="table table-bordered">
                <tr>
                    <!-- WAREHOUSE -->
                    <td width="25%">
                        <select name="from_warehouse_id" id="from_warehouse_id" data-placeholder="Warehouse" class="form-control select2" style="width: 100%" required>
                            {{-- <option value="" selected>- Select -</option> --}}
                            @foreach($warehouses ?? [] as $id => $name)
                                <option value="{{ $id }}" {{ request('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </td>


                    <!-- DATE RANGE -->
                    <td width="25%">
                        <div class="input-group">
                            <input type="text" class="form-control date-picker text-center" name="from" id="fromDate" value="{{ old('date') }}" autocomplete="off" placeholder="From Date" data-date-format="yyyy-mm-dd">
                            <span class="input-group-addon"><i class="fa fa-exchange"></i></span>
                            <input type="text" class="form-control date-picker text-center" name="to" id="toDate" value="{{ old('date') }}" autocomplete="off" placeholder="To Date" data-date-format="yyyy-mm-dd">
                        </div>
                    </td>



                    <!-- ACTION -->
                    <td width="20%" class="text-center">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm btn-primary" style="padding-top: 6px; padding-bottom: 6px;"><i class="fa fa-search"></i> SEARCH</button>
                            <a href="{{ request()->url() }}" class="btn btn-sm btn-light" style="width: 49%; padding-top: 6px; padding-bottom: 6px;"><i class="fa fa-refresh"></i> REFRESH</a>
                        </div>
                    </td>
                </tr>

            </table>
        </div>

    </form>


    @include('partials._alert_message')


    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered table-hover fixed-table-header" >
                    <thead>
                        <tr class="table-header-bg">
                            <th width="5%" class="text-center">SL</th>
                            <th width="10%">Invoice No</th>
                            <th width="15%">Warehouse</th>
                            <th width="15%">Date</th>
                            <th width="10%" class="text-center">Total Qty</th>
                            <th width="10%" class="text-center">Total Amount</th>
                            <th width="15%" class="text-center">Current Status</th>
                            <th width="15%" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($stockAdjustments as $stockAdjustment)
                            <tr>
                                <td width="5%" class="text-center">{{ $loop->iteration }}</td>
                                <td width="10%">{{ $stockAdjustment->invoice_no }}</td>
                                <td width="15%">{{ optional($stockAdjustment->warehouse)->name }}</td>
                                <td width="15%">{{ $stockAdjustment->date }}</td>
                                <td width="10%" class="text-center">{{ number_format($stockAdjustment->total_quantity, 2, '.', '') }}</td>
                                <td width="10%" class="text-center">{{ number_format($stockAdjustment->total_amount, 2, '.', '') }}</td>
                                <td width="15%" class="text-center">
                                    <div class="label
                                        @if($stockAdjustment->current_status == 'Pending')
                                            label-warning
                                        @elseif($stockAdjustment->current_status == 'Approved')
                                            label-primary
                                        @elseif($stockAdjustment->current_status == 'Cancelled')
                                            label-danger
                                        @endif
                                        ">{{ $stockAdjustment->current_status }}
                                    </div>
                                </td>
                                <td width="15%" class="text-center">
                                    <div class="btn-group btn-corner" style="display: flex">
                                        <span class="btn btn-info btn-xs popover-success"
                                            data-rel="popover"
                                            data-placement="top"
                                            data-trigger="hover"
                                            data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Log Information"
                                            data-content="<div style='width: 220px'>
                                                            <p><b>Created By:</b> {{ optional($stockAdjustment->created_user)->name }}.</p> <p> Created At : {{ $stockAdjustment->created_at }} </p>
                                                            <p><b>Updated By:</b> {{ optional($stockAdjustment->updated_user)->name }}.</p> <p> Updated At : {{ $stockAdjustment->updated_at }} </p>
                                                            <p><b>Approved By:</b> {{ optional($stockAdjustment->approvedBy)->name }}.</p> <p> Approved At : {{ $stockAdjustment->approved_at }} </p>
                                                        </div>"
                                            >
                                            <i class="fa fa-info-circle"></i>
                                        </span>

                                        @if (hasPermission("inv.stock-adjustments.view", $slugs))
                                            <a href="{{ route('inv.stock-adjustments.show', $stockAdjustment->id) }}" class="btn btn-xs btn-primary" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                        @if($stockAdjustment->current_status == 'Pending')
                                            {{-- @if (hasPermission("inv.stock-transfer.edit", $slugs))
                                                <a href="{{ route('inv.stock-transfer.edit', $stockAdjustment->id) }}" class="btn btn-xs btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif --}}
                                            @if (hasPermission("inv.stock-adjustments.approve", $slugs))
                                                <a href="{{ route('inv.stock-adjustments-approve', $stockAdjustment->id) }}" class="btn btn-xs btn-warning" title="Approve Now!">
                                                    <i class="fa fa-thumbs-up"></i>
                                                </a>
                                            @endif

                                        @elseif($stockAdjustment->current_status == 'Approved')
                                                @if (in_array($stockAdjustment->to_warehouse_id, warehouse_access()))
                                                    <a href="javascript:void(0)" class="btn btn-xs btn-success" title="Approved">
                                                        <i class="fa fa-thumbs-up"></i>
                                                    </a>
                                                @endif

                                        @endif

                                        @if($stockAdjustment->current_status == 'Pending')
                                            @if (hasPermission("inv.stock-adjustments.cancel", $slugs))
                                                @if($stockAdjustment->current_status != 'Cancelled')
                                                    <a href="{{ route('inv.stock-adjustments-cancel', $stockAdjustment->id)}}" class="btn btn-xs btn-warning" title="Cancel">
                                                        <i class="fa fa-undo"></i>
                                                    </a>
                                                @endif
                                            @endif
                                            @if (hasPermission("inv.stock-adjustments.delete", $slugs))
                                                <button type="button" class="btn btn-xs btn-danger" title="Delete" onclick="delete_item('{{ route('inv.stock-adjustments.destroy', $stockAdjustment->id) }}')">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="font-size: 16px" class="text-center text-danger">
                                    NO RECORDS FOUND!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @include('partials._paginate',['data'=> $stockAdjustments])
            </div>
        </div>
    </div>
        </x-mm.panel>
    </x-mm.page>
@endsection
