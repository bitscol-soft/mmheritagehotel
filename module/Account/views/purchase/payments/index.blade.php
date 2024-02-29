@extends('layouts.master')
@section('title', 'Supplier')
@section('page-header')
<i class="fa fa-plus"></i> Payment Lists
@stop
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />

@endpush

@section('content')
<div class="row">
    <div class="col-sm-12 col-sm-offset-0">

        @include('partials._alert_message')

        <!-- heading -->
        <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
            <div class="widget-header widget-header-small">
                <h3 class="widget-title smaller text-primary">
                    @yield('page-header')
                </h3>

                <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                    <div class="pull-right tableTools-container" style="margin: 0 !important">
                        <div class="dt-buttons btn-overlap btn-group">
                            <a href="{{ route('acc-suppliers.index') }}"
                                class="dt-button btn btn-white btn-info btn-bold" title="List" data-toggle="tooltip"
                                tabindex="0" aria-controls="dynamic-table">
                                <span>
                                    <i class="fa fa-list bigger-110"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space"></div>

            {{-- AAAAAAA --}}
            <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">
                <form action="" method="get">

                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="input-group">
                            <label class="input-group-addon" for="">
                                Invoice No
                            </label>

                            <select name="invoice_no" class="chosen-select-100-percent"
                                data-placeholder="- Select Invoice -" style="display: none;">
                                <option></option>
                                <option value="P-2021-10-000103">P-2021-10-000103</option>
                                <option value="P-2021-10-000104">P-2021-10-000104</option>
                                <option value="P-2021-10-000105">P-2021-10-000105</option>
                                <option value="P-2021-10-000106">P-2021-10-000106</option>
                                <option value="P-2021-10-000107">P-2021-10-000107</option>
                                <option value="P-2021-10-000108">P-2021-10-000108</option>
                                <option value="P-2021-10-000109">P-2021-10-000109</option>
                                <option value="P-2021-10-000110">P-2021-10-000110</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="input-group">
                            <label class="input-group-addon" for="">
                                Reference
                            </label>

                            <select name="reference" class="chosen-select-100-percent"
                                data-placeholder="- Select Reference -" style="display: none;">
                                <option></option>
                                <option value="" selected=""></option>
                            </select>

                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="btn-group btn-corner">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>
                                Search</button>
                        </div>
                    </div>

                </form>
            </div>
            {{-- AAAAAAA --}}


            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="data-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- @foreach($purchase as $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->date }}</td>
                            <td>{{ $value->invoice_no }}</td>
                            <td>{{ $value->supplier->name }}</td>
                            <td class="text-right">{{ $value->discount_amount }}</td>
                            <td class="text-right">{{ $value->payable_amount }}</td>
                            <td class="text-right">{{ $value->paid_amount }}</td>
                            <td class="text-right">{{ $value->due_amount }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-corner">
                                    @include('partials._user-log', ['data' => $value])
                                    <a href="{{route('acc_purchases.show', $value->id)}}" target="_blank"
                                        class="btn btn-success btn-xs" title="View Details"><i
                                            class="fa fa-eye"></i></a>
                                    @if(hasPermission("acc_purchases.edit", $slugs))
                                    <a href="{{route('acc_purchases.edit', $value->id)}}" class="btn btn-primary btn-xs"
                                        title="Edit"><i class="fa fa-pencil"></i></a>
                                    @endif
                                    @if(hasPermission("acc_purchases.delete", $slugs))
                                    <a href="#"
                                        onclick="delete_item('{{ route('acc_purchases.destroy', $value->id) }}')"
                                        class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    @endif
                                </div>

                                <!-- delete form -->
                                <form action="" id="deleteItemForm" method="POST">
                                    @csrf @method("DELETE")
                                </form>
                            </td>
                        </tr>
                        @endforeach --}}
                    </tbody>
                </table>
                {{-- @if(count($purchase) <= 0) --}} <div class="text-center">
                    <span class="text-warning">No Records Founds Yet!</span>
            </div>
            <br>
            {{-- @else --}}
            {{-- @include('partials._paginate', ['data' => $purchase]) --}}
            {{-- @endif --}}

        </div>
    </div>
</div>
</div>


@endsection

@section('js')


<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
@endsection
