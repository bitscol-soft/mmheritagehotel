@extends('layouts.master')

@section('title', 'Purchase Return List')

@section('page-header')
<i class="fa fa-info-circle"></i> Purchase Return List
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

            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">

                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar">
                        <a href="{{ route('acc-purchase-returns.index') }}" ><i class="fa fa-refresh"></i> Refresh</a>
                    </div>

                    <div class="widget-toolbar">
                        <a href="{{ route('acc-purchase-returns.create') }}" ><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>



                <div class="space"></div>


                <div class="row">
                    <div class="col-sm-12 px-4">
                        <table id="data-table" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Invoice No</th>
                                    <th>Supplier Name</th>
                                    <th class="text-right">Total Amount</th>
                                    <th class="text-right">Paid Amount</th>
                                    <th class="text-right">Due Amount</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($purchaseReturns as $purchaseReturn)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $purchaseReturn->date }}</td>
                                        <td>{{ $purchaseReturn->invoice_no }}</td>
                                        <td>{{ optional($purchaseReturn->supplier)->name }}</td>
                                        <td class="text-right">{{ $purchaseReturn->total_payable }}</td>
                                        <td class="text-right">{{ $purchaseReturn->total_paid_amount }}</td>
                                        <td class="text-right">{{ $purchaseReturn->total_due_amount }}</td>

                                        <td class="text-center">
                                            <div class="btn-group btn-corner">

                                                @include('partials._user-log', ['data' => $purchaseReturn])

                                                <a href="{{route('acc-purchase-returns.show', $purchaseReturn->id)}}" target="_blank" class="btn btn-success btn-xs" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                @if(hasPermission("account-purchases.delete", $slugs))
                                                    <button type="button" onclick="delete_item(`{{ route('acc-purchase-returns.destroy', $purchaseReturn->id) }}`)" class="btn btn-xs btn-danger" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>




                    @if(count($purchaseReturns) <= 0)
                        <div class="text-center">
                            <span class="text-warning">No Records Founds Yet!</span>
                        </div>
                        <br>
                    @else
                        @include('partials._paginate', ['data' => $purchaseReturns])
                    @endif

                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
@endsection
