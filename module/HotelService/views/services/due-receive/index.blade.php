@extends('layout.app')
@section('title', 'Medical Service Due Receive')
@push('style')
    <?php
    $defaultAccountId = defaultAccount();
    ?>
    <style>
        .modal.in .modal-dialog {
            transform: translate(15%, {{ $defaultAccountId ? '1' : '10' }}0%);
        }

    </style>
@endpush
@section('content')
    @inject(request, Illuminate\Http\Request)
    <div id="content" class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-heading-btn pull-right">
                            {{-- <a class="btn btn-success btn-sm"  href="{{action('ServiceSalesDueReceiveController@dueReceiveList')}}"><i class="fa fa-bars"></i>Receive List</a> --}}
                        </div>
                        <h4 class="panel-title">Medical Service Due Collection</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            @include('hospital_partials.due_search', ['corporate_clients' => $corporate_clients])
                        </div>
                        <br>

                        <div class="row">
                            @if (isset($dueServiceSales) && count($dueServiceSales))
                                <div class="col-md-12">
                                    <table id="datatable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="20%">Patient Name</th>
                                                <th width="8%">Patient ID</th>
                                                <th width="5%">Age</th>
                                                <th>Delivery Date</th>
                                                <th>Total Price</th>
                                                <th>Discount</th>
                                                <th>Paid</th>
                                                <th>Due</th>
                                                <th colspan="3" width="2%" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dueServiceSales as $dueServiceSale)
                                                <tr>
                                                    <td>{{ $dueServiceSale->patient->name }}</td>
                                                    <td>{{ $dueServiceSale->patient->customer_id }}</td>
                                                    <td>{{ $dueServiceSale->patient->age }}</td>
                                                    <td>{{ $dueServiceSale->delivery_date->format('d/m/Y') }}</td>
                                                    <td>{{ $dueServiceSale->subtotal }}</td>
                                                    <td>{{ $dueServiceSale->discount }}</td>
                                                    <td>{{ number_format($dueServiceSale->advance_paid, 2) }}</td>
                                                    <td>{{ $dueServiceSale->due_amount }}</td>
                                                    @if ($dueServiceSale->due_amount != 0.0)
                                                        @if (hasPermission('service.edit', $slugs))
                                                            <td><a class="btn btn-xs btn-warning"
                                                                    onclick="payment({{ $dueServiceSale->id . ',' . $dueServiceSale->due }})">
                                                                    Payment</a>
                                                            </td>
                                                        @endif
                                                    @else
                                                        <td><a class="btn btn-xs btn-teal">PAID</a></td>
                                                    @endif
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    @if ($request->filled('customer_id'))
                                        {{ $dueServiceSales->appends(['customer_id' => $request->customer_id]) }}
                                    @elseif($request->filled('date'))
                                        {{ $dueServiceSales->appends(['date' => $request->date]) }}
                                    @else
                                        {{ $dueServiceSales->links() }}
                                    @endif
                                </div>

                                @include('hospital_partials.due-payment-popup')
                            @endif
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('custom_js/loadDetails.js') }}"></script>
    <script src="{{ asset('custom_js/patient_filter.js') }}"></script>
    <script>
        function patientFilter() {
            selfFilter();
            resetPatient();
            // switch ($('.patient_type:checked').val()) {
            //     case 'card':
            //         cardFilter("outdoor");
            //         resetPatient();

            //         break;
            //     case 'corporate':
            //         corporateFilter("outdoor");
            //         resetPatient();
            //         break;
            //     default:
            //         selfFilter();
            //         resetPatient();
            //         break;
            // }
        }

        function requestUrl(urlParts) {
            urlParts = $.extend({
                basePath: '{{ url('/') }}/',
                path: '',
                param: ''
            }, urlParts);
            return urlParts.basePath + urlParts.path + urlParts.param;
        }
    </script>

    <script>
        function payment(serviceSaleId, dueAmount) {
            $('#payment-form').attr('action', '{{ url('service-due-receive') }}/' + serviceSaleId);
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
    </script>
@endsection
