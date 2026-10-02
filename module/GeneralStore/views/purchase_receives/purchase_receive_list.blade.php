@extends('layouts.master')
@section('title','Purchase Receive')
@section('css')

@stop


@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-gs mm-rst mm-rst-inv" title="Purchase receive" description="Receipts recorded against this purchase.">
    <x-slot name="actions">
        @if(hasPermission("purchases.create", $slugs))
        <a class="mm-button" href="{{ route('purchases.create') }}"><i class="fa fa-plus"></i> Add Purchase</a>
        @endif
        @if(hasPermission("purchases.create", $slugs))
        <a class="mm-button mm-button-secondary" href="{{ route('purchases.index') }}"><i class="fa fa-list"></i> Purchase List</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')

        <div class="row">
            <div class="col-xs-12">
                    <x-mm.table-scroll label="Purchase receives">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>GRN No.</th>
                                    <th>Date</th>
                                    <th>Purchase Number</th>
                                    <th>Company</th>
                                    <th>Required Qty</th>
                                    <th>Received Qty</th>
                                    <th>Challan Number</th>
                                    <th>Received By</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($purchase_receives as $key => $purchase_receive)
                                    @php
                                        $total_received_quantity = 0;
                                        $total_required_quantity = 0;
                                        foreach ($purchase_receive->purchase_receive_details as $key => $purchase) {
                                            $total_received_quantity += $purchase->quantity;
                                            $total_required_quantity += $purchase_receive->purchase->purchase_details[$key]->quantity;
                                        }
                                    @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td style="min-width: 90px">{{ $purchase_receive->purchase_receive_date }}</td>
                                    <td>{{ $purchase_receive->form_number }}</td>
                                    <td style="min-width: 90px">{{ $purchase_receive->purchase->purchase_date }}</td>
                                    <td>{{ $purchase_receive->purchase->form_number }}</td>
                                    <td>{{ $purchase_receive->company->name }}</td>
                                    <td>{{ $total_required_quantity }}</td>
                                    <td>{{ number_format($total_received_quantity, 2) }}</td>
                                    <td>{{ $purchase_receive->purchase_challan_number }}</td>
                                    <td>
                                        <p title="Update Time : {{ $purchase_receive->updated_at }}">{{ $purchase_receive->updated_user->name }}</p>
                                        <p style="margin-top:-10px !important; font-size: 10px !important;">{{ \Carbon\Carbon::parse($purchase_receive->updated_at)->format('Y-m-d') }}</p>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-corner" style="min-width: 50px">

                                            <a href="{{ route('print.purchase-receive', $purchase_receive->id) }}" role="button" target="__blank" class="btn btn-xs btn-info" title="Print">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            <a  href="#purchase-receive-details{{ $purchase_receive->id }}" role="button" data-toggle="modal" class="btn btn-xs btn-purple" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            @php
                                                $count = 0;
                                                foreach ($purchase_receive->purchase_receive_details as $details ){
                                                    $count += count($details->is_in_stock);
                                                }
                                            @endphp

                                            @if(hasPermission('purchase.receives.delete', $slugs) && $count == 0)
            {{--                                @if(hasPermission('purchase.receives.delete', $slugs) && $purchase_receive->totalQuantity->first()->totalReceived >= $purchase_receive->totalQuantity->first()->totalRemaining)--}}
                                            <button type="button" onclick="delete_check({{ $purchase_receive->id }})" class="btn btn-xs btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                            @endif
                                        </div>

                                        <form action="{{ route('purchase.receives.destroy',$purchase_receive->id)}}" id="deleteCheck_{{ $purchase_receive->id }}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                        </form>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-mm.table-scroll>

                    @include('partials._paginate', ['data' => $purchase_receives])
                </div>

            </div>
        </div>



        {{-- purchase receive detail modals --}}
        @foreach($purchase_receives as $purchase_receive)

            <div id="purchase-receive-details{{ $purchase_receive->id }}" class="modal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="blue bigger"><i class="fa fa-eye"></i> View Purchase Receive Details</h4>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <dl id="dt-list-1" class="dl-horizontal">
                                        <p class="text-center">Date : {{ $purchase_receive->purchase_receive_date }}</p>
                                        <p class="text-center">Purchase Number : {{ $purchase_receive->purchase->form_number }}</p>
                                        <p class="text-center">GRN Number : {{ $purchase_receive->form_number }}</p>
        {{--                                <p class="text-center">Company : {{ $purchase_receive->company->name }}</p>--}}

                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Items</th>
                                                <th>Item Unit</th>
                                                <th>Vendor</th>
                                                <th>Remarks</th>
                                                <th>Required Qty</th>
                                                <th>Received Qty</th>
                                                <th>Rate</th>
                                                <th>Total</th>
                                            </tr>
                                            @php
                                                $total_received_amount = 0;
                                                $total_required_quantity = 0;
                                                $total_received_quantity = 0;
                                            @endphp
                                            @foreach($purchase_receive->purchase->purchase_details as $key => $purchase)
                                                @php
                                                    $total_received_amount += ($purchase_receive->purchase_receive_details[$key]->rate * $purchase_receive->purchase_receive_details[$key]->quantity);
                                                    $total_required_quantity += $purchase->quantity;
                                                    $total_received_quantity += $purchase_receive->purchase_receive_details[$key]->quantity;
                                                @endphp
                                                <tr>
                                                    <td>{{ $purchase->item->name  }}</td>
                                                    <td>{{ $purchase->item->item_unit->name   }}</td>
                                                    <td>{{ $purchase_receive->purchase_receive_details[$key]->supplier->name }}</td>
                                                    <td>{{ $purchase_receive->remarks }}</td>
                                                    <td>{{ $purchase->quantity }}</td>
                                                    <td>{{ $purchase_receive->purchase_receive_details[$key]->quantity }}</td>
                                                    <td>{{ $purchase_receive->purchase_receive_details[$key]->rate }}</td>
                                                    <td class="text-right">{{ $purchase_receive->purchase_receive_details[$key]->rate * $purchase_receive->purchase_receive_details[$key]->quantity }}</td>
                                                </tr>
                                             @endforeach
                                            <tr>
                                                <td colspan="4">Total</td>
                                                <td>{{ $total_required_quantity }}</td>
                                                <td colspan="2">{{ $total_received_quantity }}</td>
                                                <td class="text-right">{{ $total_received_amount }}</td>
                                            </tr>
                                        </table>

                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-sm" data-dismiss="modal">
                                <i class="ace-icon fa fa-times"></i>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('assets/js/ace.min.js') }}"></script>

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
    jQuery(function($) {
        $('#dynamic-table').DataTable({
            "ordering": false,
            "bPaginate": false,
            "lengthChange": false,
            "info": false,
            'searching': false
        });

    })
</script>
@stop
