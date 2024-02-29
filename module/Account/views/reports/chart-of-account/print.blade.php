@extends('layouts.master')
@section('title','Chart Of Account')
@section('page-header')
    <i class="fa fa-list"></i> Chart Of Account
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>

    <style type="text/css">
        .rate-entry-table td, tr {
            border: none !important;
        }

        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        .chosen-container > .chosen-single, [class*=chosen-container] > .chosen-single {
            height: 30px !important;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="row pt-2 pb-2 text-right no-print" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12 px-4">
                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="print()"><i
                                class="fa fa-print"></i>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- heading -->

            <!-- LIST -->
            <div class="row" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12 px-4">
                    <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                        <thead>
                        <tr class="table-header-bg">
                            <th class="text-center">Sl</th>
                            <th class="text-center">Opening Date</th>
                            <th class="pl-1">Name</th>
                            <th class="text-center">Balance Type</th>
                            <th class="text-right pr-1">Balance</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($accounts as $account)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ fdate($account->created_at) }}</td>
                                <td class="pl-1">{{ $account->name }}</td>
                                <td class="text-center">{{ $account->balance_type }}</td>
                                <td class="text-right pr-1">{{ number_format($account->balance, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    
    
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
@endsection


