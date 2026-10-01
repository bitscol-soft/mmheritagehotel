@extends('layouts.master')

@section('title', 'Night Audit')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Night Audit
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
    <style>
        .widget-header {
            background-color: #EAF4FA !important;
            background-image: none !important;
        }


        table thead th {
            background-color: #4d8cb3;
            color: #fff;
        }

        .border-none {
            border: none !important;
        }

        .header-input {
            background: white !important;
            border: none !important;
            font-size: 18px !important;
            font-weight: bold !important;
            padding: 0 !important;
            color: black !important;
            width: 100% !important;
        }

        .footer-input {
            background: white !important;
            border: none !important;
            text-align: right !important;
            font-size: 18px !important;
            font-weight: bold !important;
            padding: 0 !important;
            color: black !important;
            width: 100% !important;
        }
    </style>
@endpush


@section('content')

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('night-audits.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> List
                        </a>
                    </span>
                </div>



                <div class="widget-body">

                    <x-alert-message />

                    <div class="widget-main">

                        <div class="row mb-2 hidden-print">
                            <form action="" method="GET">
                                <div class="col-sm-8 col-sm-offset-2">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                                    <input type="text" class="date-picker-v2 form-control text-center"
                                                        autocomplete="off" name="from_date"
                                                        value="{{ request('from_date', today_from_system()) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                                    <input type="text" class="date-picker-v2 form-control text-center"
                                                        autocomplete="off" name="to_date"
                                                        value="{{ request('to_date', today_from_system()) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                    <a href="{{ request()->url() }}" class="btn btn-sm btn-default">
                                                        <i class="fa fa-refresh"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </form>
                        </div>

                        @if (request('from_date'))
                            {{-- @if (count($transactions ?? []) == 0)
                                <div class="text-center">
                                    <strong style="font-size: 18px" class="text-danger">
                                        No data found under Booking or Services
                                    </strong>
                                </div>
                            @else --}}
                            <form action="{{ route('night-audits.store') }}" method="post" id="formSubmit">
                                @csrf


                                <div class="row">
                                    <h3 class="text-center">
                                        <strong>Generate Date</strong> :
                                        <span>
                                            <input type="text" name="date" class="input-sm date-picker bs-tooltip"
                                                value="{{ request('date', fdate($from_date, 'Y-m-d')) }}"
                                                title="Generate Date"
                                                style="border: none; font-size:20px;font-weight:bold;color:rgb(216, 64, 18)">
                                        </span>
                                    </h3>
                                    <hr>

                                    <div class="col-sm-9 col-sm-offset-2">
                                        <table class="table" style="border: none">

                                            <tr style="border-bottom:none !important">
                                                <th class="text-right border-none"
                                                    style="padding: 0 7px 0 0 !important; width: 20%">Total Reservation
                                                    : </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_reservation"
                                                        value="{{ $total_reservation }}">
                                                </th>


                                                <th style="border: none"></th>
                                                <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                    Total Booked :
                                                </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_booked_room"
                                                        value="{{ $total_booked_room }}">
                                                </th>
                                            </tr>
                                            <tr style="border-bottom:none !important">
                                                <th class="text-right border-none"
                                                    style="padding: 0 7px 0 0 !important; width: 20%">Total Check In :
                                                </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_check_in"
                                                        value="{{ $total_check_in }}">
                                                </th>
                                                <th style="border: none"></th>
                                                <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                    Total
                                                    Check Out : </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_check_out"
                                                        value="{{ $total_check_out }}">
                                                </th>

                                            </tr>
                                            <tr style="border-bottom:none !important">
                                                <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                    Total Room :
                                                </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_room"
                                                        value="{{ $total_room }}">
                                                </th>

                                                <th style="border: none"></th>
                                                <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                    Total
                                                    Cancelled : </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_cancelled"
                                                        value="{{ $total_cancel }}">
                                                </th>
                                            </tr>
                                            <tr style="border-bottom:none !important">
                                                <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                    Total Dirty Room :
                                                </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_dirty_room"
                                                        value="{{ $total_dirty_room }}">
                                                </th>

                                                <th style="border: none"></th>
                                                <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                    Total Maintainance Room :
                                                </th>
                                                <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                                    <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_room_maintenance"
                                                        value="{{ $total_maintenance_room }}">
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    @php
                                        $grand_total_amount = $grand_total_collection = $grand_total_due = 0;

                                        foreach ($accountTypes as $id => $name) {
                                            $$name = 0;
                                        }
                                    @endphp
                                    @foreach ($transactions as $key => $collections)
                                        <div class="col-md-12">
                                            <h6 style="width: 100%;text-align: center" class="mb-2">
                                                <b
                                                    style="padding: 10px 20px; border-radius: 10px; color: #000; border:1px solid #ddd;">
                                                    {{ $key }}
                                                </b>
                                            </h6>
                                            <table class="table table-bordered table-striped table-hover"
                                                style="border: none">

                                                <thead>
                                                    <tr>
                                                        <th class="text-center">SL</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Invoice No</th>
                                                        <th class="text-center">Payment Type</th>
                                                        @if ($key == 'Booking')
                                                            <th class="text-center" style="width: 30%">Room No</th>
                                                            <th class="text-right">Extra Charge <span
                                                                    class="currency-sign"></span></th>
                                                        @endif
                                                        <th class="text-right">Total Amount <span
                                                                class="currency-sign"></span></th>
                                                        <th class="text-right">Paid Amount <span
                                                                class="currency-sign"></span></th>
                                                        <th class="text-right" style="width: 14% !important">Due
                                                            Amount<span class="currency-sign"></span></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php
                                                        $total_collection = $total_due_amount = 0;
                                                        $sub_total_amount = $total_paid_amount = $total_due_amount = 0;
                                                        $total_discount = 0;

                                                    @endphp

                                                    @foreach ($collections as $transaction)
                                                        @php
                                                            $sub_total_amount += $total_amount = $transaction->total_amount;
                                                            $total_collection += $paid_amount = $transaction->ledger_paid;
                                                            $total_discount += $transaction->discount;

                                                            $total_due_amount += $due_amount = $total_amount - $paid_amount;
                                                            // $total_due_amount += $due_amount = $transaction->due_amount;
                                                        @endphp
                                                        @foreach ($transaction->transaction_ledgers as $ledger)
                                                            <input type="hidden"
                                                                name="transaction_ledger_ids[{{ $transaction->id }}]"
                                                                value="{{ $ledger->id }}">
                                                        @endforeach
                                                        <tr>
                                                            <td class="text-center">
                                                                {{ $loop->iteration }}

                                                                <input type="hidden"
                                                                    name="transaction_ids[{{ $transaction->id }}]"
                                                                    value="{{ $transaction->id }}">
                                                            </td>
                                                            <td class="text-center">{{ $transaction->date }}</td>
                                                            <td class="text-center">{{ $transaction->source_type }}</td>
                                                            <td class="text-center">
                                                                INV-{{ $transaction->invoice_no }}
                                                            </td>

                                                            <td class="text-center">
                                                                @foreach ($transaction->transaction_ledgers->unique('payment_type') as $ledger)
                                                                    @php
                                                                        foreach ($accountTypes as $id => $name) {
                                                                            if (optional($ledger->account)->name === $name) {
                                                                                $$name += $ledger->in;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    {{ optional($ledger->account)->name ?? 'N/A' }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach

                                                            </td>

                                                            @if ($key == 'Booking')
                                                                <td class="text-center">
                                                                    @php
                                                                        $details = optional($transaction->source)->details;
                                                                    @endphp
                                                                    @foreach ($details ?? [] as $room)
                                                                        <label
                                                                            class="label label-success">{{ optional($room->roomNumber)->room_number }}</label>
                                                                    @endforeach
                                                                </td>
                                                                <td style="text-align: right;">
                                                                    {{ calculateCurrencyAmount($transaction->extra_charge) }}
                                                                </td>
                                                            @endif
                                                            <td style="text-align: right;">
                                                                <input type="hidden"
                                                                    name="total_amounts[{{ $transaction->id }}]"
                                                                    value="{{ calculateCurrencyAmount($total_amount - $transaction->discount, 1) }}">
                                                                {{ calculateCurrencyAmount($total_amount - $transaction->discount) }}
                                                            </td>
                                                            <td style="text-align: right;">
                                                                <input type="hidden"
                                                                    name="collections[{{ $transaction->id }}]"
                                                                    value="{{ calculateCurrencyAmount($paid_amount, 1) }}">
                                                                {{ calculateCurrencyAmount($paid_amount) }}
                                                            </td>
                                                            <td class="text-right">
                                                                <input type="hidden"
                                                                    name="due_amounts[{{ $transaction->id }}]"
                                                                    value="{{ $due_amount - $transaction->discount > 0 ? calculateCurrencyAmount($due_amount - $transaction->discount, 1) : 0 }}">

                                                                {{ $due_amount - $transaction->discount > 0 ? calculateCurrencyAmount($due_amount - $transaction->discount) : 0 }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>


                                                <tfoot>
                                                    <tr>
                                                        <th class="text-right" colspan="{{ $key == 'Booking' ? 7 : 5 }}">
                                                            <strong style="font-size: 18px">Total:</strong>
                                                        </th>
                                                        <th class="text-right"><strong
                                                                style="font-size: 18px">{{ calculateCurrencyAmount($sub_total_amount - $total_discount, 1) }}</strong>
                                                        </th>
                                                        <th class="text-right"><strong
                                                                style="font-size: 18px">{{ calculateCurrencyAmount($total_collection, 1) }}</strong>
                                                        </th>
                                                        {{-- <th class="text-right"><strong
                                                                style="font-size: 18px">{{ calculateCurrencyAmount($due_amount - $transaction->discount, 1) }}</strong>
                                                        </th> --}}

                                                        <th class="text-right"><strong
                                                                style="font-size: 18px">{{ calculateCurrencyAmount($total_due_amount - $total_discount, 1) }}</strong>
                                                        </th>
                                                    </tr>


                                                </tfoot>
                                            </table>
                                        </div>
                                        @php
                                            $grand_total_amount += $sub_total_amount;
                                            $grand_total_collection += $total_collection;
                                            $grand_total_due += $total_due_amount - $total_discount;

                                        @endphp
                                    @endforeach

                                </div>

                                <div class="row mt-3">
                                    <table class="table table-borderedless">
                                        <thead>
                                            <tr>
                                                <h6 style="width: 100%;text-align: center" class="mb-2">
                                                    <b
                                                        style="padding: 10px 20px; border-radius: 10px; color: #000; border:1px solid #ddd;">
                                                        SUMMARY
                                                    </b>
                                                </h6>
                                            </tr>
                                        </thead>
                                        <tr style="border-bottom:none !important">
                                            <th class="text-right border-none">
                                                Total Amount:
                                            </th>
                                            <th class="text-right border-none"
                                                style="padding: 3px 7px 0px 0px !important;">
                                                <input type="text" readonly class="footer-input"
                                                    style="background: white !important; padding: 0 !important"
                                                    name="total_amount" value="{{ $grand_total_amount }}">
                                            </th>
                                        </tr>
                                        <tr style="border-bottom:none !important">
                                            <th class="text-right border-none">
                                                Total Collection:
                                            </th>
                                            <th class="text-right border-none"
                                                style="padding: 3px 7px 0px 0px !important;">
                                                <input type="text" readonly class="footer-input"
                                                    style="background: white !important; padding: 0 !important"
                                                    name="collection" value="{{ $grand_total_collection }}">
                                            </th>
                                        </tr>

                                        @foreach ($accountTypes as $id => $name)
                                            <tr style="border-bottom:none !important">
                                                <th class="text-right border-none">
                                                    {{ $name }}:
                                                </th>
                                                <th class="text-right border-none"
                                                    style="padding: 3px 7px 0px 0px !important;">
                                                    <input type="text" readonly class="footer-input"
                                                        style="background: white !important; padding: 0 !important"
                                                        name="payment_way[{{ $name }}]"
                                                        value="{{ $$name }}">
                                                </th>
                                            </tr>
                                        @endforeach

                                        <tr style="border-bottom:none !important">
                                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                Total Due:</th>
                                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                                <input type="text" readonly class="footer-input"
                                                    style="background: white !important" name="due_amount"
                                                    value="{{ $grand_total_due }}">
                                            </th>
                                        </tr>
                                    </table>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4 pull-right text-right">
                                        <div class="btn-group btn-corner">
                                            <button class="btn-outline-danger btn-sm">
                                                <i class="fa fa-refresh"></i> Close
                                            </button>
                                            <button type="button" class="btn-sm btn-outline-success save-btn">
                                                <i class="fa fa-check-circle"></i>
                                                Generate
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            {{-- @endif --}}

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        $('.save-btn').on('click', () => {
            $.LoadingOverlay("show", {
                image: '<svg></svg>',
                imageClass: 'loader',
                imageAnimation: false,
                progress: false,
                rogressAutoResize: true,
                progressResizeFactor: 0.25,
                progressColor: "#8a256c",
                progressClass: "",
                progressOrder: 2,
                progressFixedPosition: "center",
                progressSpeed: 200,
                progressMin: 20,
                progressMax: 100,
                size: 50,
                maxSize: 120,
                minSize: 20,
                direction: "column",
                fade: [400, 200],
                resizeInterval: 50,
                zIndex: 2147483647

            })

            // setInterval(() => {
            //     $.LoadingOverlay("hide")
            //     $('#formSubmit').submit();
            // }, 1000);

            setTimeout(() => {
                $.LoadingOverlay("hide")
                $('#formSubmit').submit();
            }, 10000);

        })


        $('.date-picker-v2').datetimepicker({
            //  format: 'YYYY-MM-DD H:mm:ss',//use this option to display seconds
            format: 'YYYY-MM-DD h:mm:ss A', //use this option to display seconds
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-arrows ',
                clear: 'fa fa-trash-o',
                close: 'fa fa-times'
            }
        }).next().on(ace.click_event, function() {
            $(this).prev().focus();
        });
    </script>
@stop
