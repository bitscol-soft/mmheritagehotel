@extends('layouts.master')

@section('title', 'Hotel Service Sales List')

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-service" title="Hotel service sales" description="Service invoices raised to guests, with payment status.">
    @if (hasPermission('service.view', $slugs))
        <x-slot name="actions">
            <a href="{{ route('hotelservice.service-sales.create') }}" class="mm-button">
                <i class="fa fa-plus" aria-hidden="true"></i> New Hotel Service
            </a>
        </x-slot>
    @endif

    <x-mm.panel class="mm-hs-filter">
        <form class="mm-hs-filter-form">
            <div class="mm-hs-filter-field">
                <label for="hs-invoice-no">Invoice ID</label>
                <input type="number" name="invoice_no" id="hs-invoice-no" value="{{ request('invoice_no') }}"
                    class="form-control" placeholder="Invoice ID" autocomplete="off">
            </div>
            <div class="mm-hs-filter-field">
                <label for="hs-customer-id">Guest ID</label>
                <input type="number" name="customer_id" id="hs-customer-id" value="{{ request('customer_id') }}"
                    class="form-control" placeholder="Guest ID" autocomplete="off">
            </div>
            <div class="mm-hs-filter-actions">
                <div class="btn-group btn-corner">
                    @if (hasPermission('service.view', $slugs))
                        <button type="submit" class="btn btn-success btn-sm" aria-label="Search">
                            <i class="fa fa-search"></i>
                        </button>
                        <a href="{{ request()->url() }}" class="btn btn-danger btn-sm" aria-label="Reset">
                            <i class="fa fa-undo"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </x-mm.panel>

    <x-mm.panel>
        <x-mm.table-scroll label="Hotel service sales">
            <table class="table table-striped table-bordered table-bg-color">
                <thead>
                    <tr>
                        <th width="1%">SL</th>
                        <th>Invoice ID</th>
                        <th width="8%">Guest ID</th>
                        <th width="10%">Guest Name</th>
                        <th>Mobile Number</th>
                        <th width="3%">Total</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Due</th>
                        <th width="8%">Payment</th>
                        <th width="8%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @php
                        $grand_total_amount     = 0;
                        $grand_total_discount   = 0;
                        $grand_total_paid       = 0;
                        $grand_total_due        = 0;
                    @endphp

                    @forelse($services as $key=> $service)
                        @php
                            $grand_total_amount     += $total_amount        = $service->subtotal;
                            $grand_total_discount   += $total_discount      = $service->discount;
                            $grand_total_paid       += $total_paid          = $service->paid_amount;
                            $grand_total_due        += $due_amount          = $service->payable_amount - $service->paid_amount;
                        @endphp
                        <tr>
                            <td>
                                {{ $key + $services->firstItem() }}
                            </td>
                            <td>
                                {{ $service->invoice_no }}
                            </td>
                            <td>
                                {{ optional($service->hotel_guest)->id ?? '' }}
                            </td>
                            <td>
                                {{ optional($service->hotel_guest)->name ?? ($service->guest_name ?? '') }}
                            </td>
                            <td>
                                {{ optional($service->hotel_guest)->phone_no ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ number_format($total_amount, 2) }}
                            </td>
                            <td class="text-right">
                                {{ number_format($total_discount, 2) }}
                            </td>
                            <td class="text-right">
                                {{ number_format($total_paid, 2) }}
                            </td>
                            <td class="text-right">
                                {{ number_format($due_amount, 2) }}
                            </td>
                            <td class="text-center">

                                @if ($due_amount != 0.0)
                                    @if (hasPermission('service.edit', $slugs))
                                        <a class="btn btn-minier btn-warning"
                                            onclick="payment(`{{ route('hotelservice.service-due-receive', $service->id) }}`, {{ $due_amount }})">
                                            <i class="fa fa-money"></i> Payment
                                        </a>
                                    @endif
                                @else
                                    <span class="btn-xs btn-success">PAID</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-corner btn-group">
                                    @if (hasPermission('service.view', $slugs))
                                        <a class="btn btn-xs btn-success" target="__blank"
                                            href="{{ route('hotelservice.service-sales.show', $service) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endif
                                    @if (hasPermission('service.delete', $slugs))
                                        <button type="button"
                                            onclick="delete_item(`{{ route('hotelservice.service-sales.destroy', $service) }}`)"
                                            class="btn btn-xs btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="30" class="text-center">
                                <strong class="text-danger">No Record Found !</strong>
                            </td>
                        </tr>
                    @endforelse
                    <tr>
                        <th colspan="5" class="text-right">
                            Total Amount
                            <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                        </th>
                        <th colspan="1" class="text-right" style="color: blue">
                            {{ number_format($grand_total_amount, 2) }}
                        </th>
                        <th class="text-right" style="color: gray">
                            {{ number_format($grand_total_discount, 2) }}
                        </th>
                        <th class="text-right" style="color:green">
                            {{ number_format($grand_total_paid, 2) }}
                        </th>
                        <th class="text-right" style="color:red">
                            {{ number_format($grand_total_due, 2) }}
                        </th>
                        <th colspan="4" class="text-left">
                            <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                        </th>

                    </tr>

                </tbody>
            </table>
        </x-mm.table-scroll>
        {{ $services->appends(['invoice_no' => request('invoice_no'), 'customer_id' => request('customer_id')])->render() }}
    </x-mm.panel>

    @include('services.sales.due-payment-modal')
</x-mm.page>
@endsection


@section('js')
    <script>
        function payment(route, dueAmount) {
            $('#payment-form').attr('action', route);
            $('#previous-due').val(dueAmount);
            $('#exampleModal').modal().show();
        }

        $('#payable-amount').on('keyup', function() {
            var payable = $(this).val(),
                previousDue = $('#previous-due').val(),
                currentDue = parseFloat(previousDue) - parseFloat(payable);

            if (currentDue < 0) {
                $(this).val(0);
                $('#current-due').val(previousDue);
            } else {
                $('#current-due').val(currentDue);
            }
        });

        function giveDelivery($investigationId) {

            swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to deliver this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                })
                .then((isConfirm) => {
                    if (isConfirm.value) {

                        $.ajax({
                            url: '/hospitals/service-sale-delivery/' + $investigationId,
                            type: 'get',
                            success: function(response) {
                                console.log(response);
                                if (response.status) {
                                    location.reload();
                                }
                            },
                            error: function(error) {
                                // console.log(error)
                            }
                        });
                    } else {
                        // If not confirm.
                    }
                });
        }
    </script>
@endsection
