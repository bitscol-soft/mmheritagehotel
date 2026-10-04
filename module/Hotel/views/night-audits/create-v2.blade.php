@extends('layouts.master')

@section('title', 'Night Audit')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
@endpush

@section('content')

    <x-mm.styles />
    <x-mm.page class="mm-night-audit" title="Generate night audit" description="Pick the audit period, review the day's reservations, collections and dues, then generate the audit.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('night-audits.index') }}">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Audit List
            </a>
        </x-slot>
        <x-alert-message />

        <x-mm.panel class="mm-co-card hidden-print">
            <h2 class="mm-co-title">Audit period</h2>
            <form action="" method="GET">
                <div class="mm-pc-search">
                    <div class="mm-co-field" role="group" aria-labelledby="mm-na-from">
                        <span id="mm-na-from" class="mm-co-label">From</span>
                        <div class="input-group">
                            <label class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></label>
                            <input type="text" class="date-picker-v2 form-control text-center"
                                                        autocomplete="off" name="from_date"
                                                        value="{{ request('from_date', today_from_system()) }}">
                        </div>
                    </div>
                    <div class="mm-co-field" role="group" aria-labelledby="mm-na-to">
                        <span id="mm-na-to" class="mm-co-label">To</span>
                        <div class="input-group">
                            <label class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></label>
                            <input type="text" class="date-picker-v2 form-control text-center"
                                                        autocomplete="off" name="to_date"
                                                        value="{{ request('to_date', today_from_system()) }}">
                        </div>
                    </div>
                    <div class="mm-pc-search-actions">
                        <button type="submit" class="mm-button">
                            <i class="fa fa-search" aria-hidden="true"></i> Search
                        </button>
                        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                            <i class="fa fa-refresh" aria-hidden="true"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </x-mm.panel>

        @if (request('from_date'))
            <form action="{{ route('night-audits.store') }}" method="post" id="formSubmit">
                @csrf

                <section class="mm-panel mm-co-card" aria-labelledby="mm-na-day">
                    <h2 id="mm-na-day" class="mm-co-title">Generate date</h2>
                    <div class="mm-na-date">
                        <input type="text" name="date" class="input-sm date-picker bs-tooltip"
                                                value="{{ request('date', fdate($from_date, 'Y-m-d')) }}"
                                                title="Generate Date"
                                                style="border: none; font-size:20px;font-weight:bold;color:rgb(216, 64, 18)">
                    </div>
                    <div class="mm-na-stats">
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Reservation</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_reservation"
                                                        value="{{ $total_reservation }}">
                        </label>
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Booked</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_booked_room"
                                                        value="{{ $total_booked_room }}">
                        </label>
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Check In</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_check_in"
                                                        value="{{ $total_check_in }}">
                        </label>
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Check Out</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_check_out"
                                                        value="{{ $total_check_out }}">
                        </label>
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Room</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_room"
                                                        value="{{ $total_room }}">
                        </label>
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Cancelled</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_cancelled"
                                                        value="{{ $total_cancel }}">
                        </label>
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Dirty Room</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_dirty_room"
                                                        value="{{ $total_dirty_room }}">
                        </label>
                        <label class="mm-na-stat"><span class="mm-sum-label">Total Maintenance Room</span>
                            <input type="text" readonly class="header-input"
                                                        style="background: white !important" name="total_room_maintenance"
                                                        value="{{ $total_maintenance_room }}">
                        </label>
                    </div>
                </section>

                @php
                                        $grand_total_amount = $grand_total_collection = $grand_total_due = 0;

                                        foreach ($accountTypes as $id => $name) {
                                            $$name = 0;
                                        }
                                    @endphp
                @foreach ($transactions as $key => $collections)
                    <section class="mm-panel mm-co-charges">
                        <h2 class="mm-co-title">{{ $key }}</h2>
                        <x-mm.table-scroll :label="$key . ' transactions'">
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
                                                        <th class="text-right">Previous Paid <span
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
                                                        $total_previous_paid = 0;

                                                    @endphp

                                                    @foreach ($collections as $transaction)
                                                        @php
                                                            $sub_total_amount += $total_amount = $transaction->ledger_paid;
                                                            // $sub_total_amount += $total_amount = $transaction->total_amount;
                                                            $total_collection += $paid_amount = $transaction->ledger_paid;
                                                            $total_discount += $transaction->discount;
                                                            $total_previous_paid += $transaction->previous_paid;

                                                            $total_due_amount += $due_amount = $transaction->total_due_amount;
                                                            // $total_due_amount += $due_amount = $total_amount - $paid_amount;
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
                                                                    name="previous_paid"
                                                                    value="{{ calculateCurrencyAmount($transaction->previous_paid, 1) }}">
                                                                {{ calculateCurrencyAmount($transaction->previous_paid) }}
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
                                                                    value="{{ $transaction->total_due_amount > 0 ? calculateCurrencyAmount($transaction->total_due_amount, 1) : 0 }}">

                                                                {{ $transaction->total_due_amount > 0 ? calculateCurrencyAmount($transaction->total_due_amount) : 0 }}
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
                                                                style="font-size: 18px">{{ calculateCurrencyAmount($total_previous_paid, 1) }}</strong>
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
                        </x-mm.table-scroll>
                    </section>
                    @php
                                            $grand_total_amount += $sub_total_amount;
                                            $grand_total_collection += $total_collection;
                                            $grand_total_due += $total_due_amount - $total_discount;

                                        @endphp
                @endforeach

                <section class="mm-panel mm-co-summary" aria-labelledby="mm-na-summary">
                    <h2 id="mm-na-summary" class="mm-co-title">Summary</h2>
                    <label class="mm-sum-row is-strong"><span class="mm-sum-label">Total Amount</span>
                        <input type="text" readonly class="footer-input"
                                                    style="background: white !important; padding: 0 !important"
                                                    name="total_amount" value="{{ $grand_total_amount }}">
                    </label>
                    <label class="mm-sum-row is-strong"><span class="mm-sum-label">Total Collection</span>
                        <input type="text" readonly class="footer-input"
                                                    style="background: white !important; padding: 0 !important"
                                                    name="collection" value="{{ $grand_total_collection }}">
                    </label>
                    <label class="mm-sum-row is-due"><span class="mm-sum-label">Total Due</span>
                        <input type="text" readonly class="footer-input"
                                                    style="background: white !important" name="due_amount"
                                                    value="{{ $grand_total_due }}">
                    </label>
                    <div class="mm-form-actions">
                        <button class="mm-button mm-button-secondary">
                                                <i class="fa fa-refresh"></i> Close
                                            </button>
                        <button type="button" class="mm-button save-btn">
                                                <i class="fa fa-check-circle"></i>
                                                Generate
                                            </button>
                    </div>
                </section>
            </form>
        @endif
    </x-mm.page>

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
