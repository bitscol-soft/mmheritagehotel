@extends('layouts.master')

@section('title', 'Ledger Journal')


@section('page-header')
    <i class="fa fa-info-circle"></i> Ledger Journal
@stop

@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style type="text/css">
        table,
        th,
        td,
        tr {
            border: none !important;
        }


        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .d-print {
                display: block !important;
            }

            tr {
                page-break-after: avoid !important;
            }

            thead {
                page-break-before: avoid !important;
            }

            .widget-box {
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .px-4 {
                padding: 0 !important;
            }
        }

        @page {
            margin: 0.5in;
            /*size: landscape;*/
        }

        .d-print {
            display: none;
        }


        .header-bg {
            background: #bce4e5 !important;
            padding: 10px !important;
        }

        .odd-bg {
            background: #cecece42 !important;
        }

        .even-bg {
            background: #dadada !important;
        }

    </style>
@endpush





@section('content')



    <div class="row">
        <div class="col-sm-12">


            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7"
                style="width: 92%; margin: auto">


                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>



                    <div class="widget-toolbar">
                        <a href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>

                    <div class="widget-toolbar">
                        <a href="#" style="cursor: pointer" onclick="print()"><i class="fa fa-plus"></i> Print</a>
                    </div>
                </div>





                <!-- filter -->
                <div class="row px-3 pb-2 no-print mt-3">
                    <form action="" method="get">

                        <div class="col-sm-4 col-sm-offset-1">
                            <div class="input-group">
                                <label class="input-group-addon">Company</label>
                                <select class="form-control chosen-select-100-percent" name="company_id" data-placeholder="-Select Company-">
                                    <option></option>
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            @include('includes.input-groups.date-range', ['date1' => request('from', date('Y-m-d')), 'date2'
                            => request('to', date('Y-m-d')), 'is_read_only' => true])
                        </div>

                        <div class="col-sm-2">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i>
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>





                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important; padding: 0 !important;">
                    <div class="col-sm-12">


                        <table class="table" style="margin-bottom: 0; width: 100% !important;">


                            <!-- table header -->
                            <thead>
                                <tr style="color: black !important; font-weight: bolder; font-size: 15px">
                                    <th class="header-bg text-center" colspan="4">SL</th>
                                    <th class="header-bg text">Account</th>
                                    <th class="header-bg text">Description</th>
                                    <th class="header-bg text-right pr-1">Dr.</th>
                                    <th class="header-bg text-right pr-1">Cr.</th>
                                </tr>
                            </thead>



                            <!-- body -->
                            <tbody>

                                @php
                                    $totalDebit     = 0;
                                    $totalCredit    = 0;
                                    $sl             = 1;
                                @endphp


                                @forelse ($transactions->groupBy('invoice_no') as $items)

                                    @foreach ($items as $item)

                                        @php
                                            $totalDebit += $item->debit_amount;

                                            $totalCredit += $item->credit_amount;
                                        @endphp



                                        <tr class="{{ $sl % 2 == 0 ? 'even-bg' : 'odd-bg' }}">
                                            <td class="text-center">
                                                @if ($loop->first) {{ $sl }} @endif
                                            </td>
                                            <td colspan="4">{{ optional($item->account)->name }}</td>
                                            <td>{{ $item->getDescription() }}</td>
                                            <td class="text-right pr-1 font-weight-bold">
                                                <strong>{{ number_format($item->debit_amount ?? 0, 2) }}</strong>
                                            </td>
                                            <td class="text-right pr-1 font-weight-bold">
                                                <strong>{{ number_format($item->credit_amount ?? 0, 2) }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @php
                                        $sl++;
                                    @endphp
                                @empty
                                    <tr>
                                        <td colspan="30" style="font-size: 16px" class="text-center text-danger">
                                            NO RECORDS FOUND!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>



                            <!-- table footer -->
                            @if (count($transactions) > 0)
                                <tfoot>
                                    <tr class="{{ $sl % 2 == 0 ? 'even-bg' : 'odd-bg' }}">
                                        <th colspan="4"></th>
                                        <th class="text"></th>
                                        <th class="text-right h4"><strong style="font-size: 16px">Total</strong></th>
                                        <th class="text-right h4"><strong
                                                style="font-size: 16px">{{ number_format($totalDebit, 2) }}</strong></th>
                                        <th class="text-right h4"><strong
                                                style="font-size: 16px">{{ number_format($totalCredit, 2) }}</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            @endif

                        </table>


                        <br>

                        @include('partials._paginate', ['data' => $transactions])


                        <!-- EXCEL BUTTON -->
                        <a class="hidden-print" href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin: 18px 0 0 20px; display: inline-block;">
                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                        </a>
                        
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection


{{-- <td colspan="2">
    <table class="table" style="border: none;">
        <tbody>
        @foreach ($value as $item)
            <tr>
                <td>{{$item->account->name}}</td>
                <td>Description</td>
                <td class="text-right pr-1">{{number_format($item->amount < 0 ? abs($item->amount) : 0, 2)}}</td>
                <td class="text-right pr-1">{{number_format($item->amount >= 0 ? abs($item->amount) : 0, 2)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</td> --}}

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
@endsection
