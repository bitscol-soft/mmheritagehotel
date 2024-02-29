@extends('layout.app')
@section('title', 'Medical Service Due Receive List')
@section('content')
    <div id="content" class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-heading-btn pull-right">
                            <a class="btn btn-success btn-sm" href="{{ route('hospital-services-sales.create') }}"><i
                                    class="fa fa-bars"></i>Service Sales</a>
                        </div>
                        <h4 class="panel-title">Due Receive List</h4>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="20%">Patient Name</th>
                                            <th width="8%">Patient ID</th>
                                            <th width="5%">Age</th>
                                            <th>Referred Doctor</th>
                                            <th>Payment Date</th>
                                            <th>Previous Due</th>
                                            <th>Payment Amount</th>
                                            <th>Current Due</th>
                                            <th colspan="3" width="2%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paymentList as $payment)
                                            <tr>
                                                <td>{{ $payment->patient->name }}</td>
                                                <td>{{ $payment->patient->customer_id }}</td>
                                                <td>{{ $payment->patient->age }}</td>
                                                <td>{{ $payment->serviceSales->referredDoctor->name }}</td>
                                                <td>{{ $payment->date }}</td>
                                                <td>{{ $payment->previous_due }}</td>
                                                <td>{{ $payment->payment_amount }}</td>
                                                <td>{{ $payment->current_due }}</td>
                                                <td><a class="btn btn-info"
                                                        href="{{ action('ServiceSalesDueReceiveController@dueReceiveVoucher', $payment) }}"><i
                                                            class="fa fa-eye"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.bookingdate').datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true,
            changeMonth: true,
            changeYear: true,
        });
        // $(".bookingdate").datepicker("setDate", new Date());
    </script>

    <script>
        $(function() {
            $("select#dept").change(function(e) {
                $.getJSON(
                    "{{ url('/loadDoctor') }}", {
                        id: $(this).val()
                    },
                    function(doctors) {
                        $("select#loadDoctor").html(
                            $.map(doctors, function(doctor) {
                                return '<option value="' + doctor.empId + '">' + doctor.fName +
                                    '</option>';
                            }).join('')
                        );

                    })
            })


        });

        function payment(serviceSaleId) {
            $('#payment-form').attr('action', '{{ url('medical-service-due-receive/') }}/' + serviceSaleId);

            $.getJSON('/medical-service-due/' + serviceSaleId, function(response) {
                console.log(response);
                $('#previous-due').val(parseFloat(response.due_amount));
                $('#exampleModal').modal().show();
            });
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
@stop
