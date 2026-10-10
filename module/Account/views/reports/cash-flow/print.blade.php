@extends('layouts.master')
@section('title', 'Cash Flow')

@push('style')
    <style type="text/css">
        @page { size: landscape; margin: 0.5in; }
    </style>
@endpush

@section('content')
    <x-mm.styles />

    <div class="mm-no-print" style="text-align: right; margin-bottom: 12px">
        <a href="{{ route('report.transaction-ledger') }}" class="mm-button mm-button-secondary">
            <i class="fa fa-backward"></i> Back
        </a>
    </div>

    @php
        $company = optional($accountGroups->first())->company;
    @endphp

    <x-mm.print-sheet sheet="a4-landscape" title="Cash Flow">
        <x-mm.panel class="tw-p-4">
            <h4 style="line-height: 0;text-align: center">{{ optional($company)->name }}</h4>

            <h4 style="line-height: 0; text-align: center !important; font-weight: bolder; margin-top: 20px !important">
                <center> Cash Flow</center>
            </h4>

            <h5 style="line-height: 0; text-align: center !important; margin-top: 10px !important; font-style: italic">
                <center><strong>From:</strong> {{ fdate(request('from')) }} <strong></center>
            </h5>

            @php
                $totalOwnerEquity = 0;
                $asset = 0;
            @endphp

            @foreach($accountGroups as $key => $accountGroup)
                <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
                <div style="margin-left: 10%; width: 85%">
                <x-mm.data-table :columns="[
                    ['label' => 'Account'],
                    ['label' => 'Balance', 'align' => 'right', 'width' => '150px'],
                ]" table-class="table table-bordered table-striped" label="{{ $accountGroup->name }}">

                    @foreach($accountGroup->accounts->where('balance', '<>', 0)->sortBy('name') as $account)
                        <tr>
                            <td>{{ $account->name }}</td>
                            <td class="text-right pr-1">{{ number_format($account->balance ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right">
                            <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                Total {{ $accountGroup->name }}
                            </strong>
                        </td>
                        <td class="text-right pr-1">{{ number_format($totalBalance = $accountGroup->accounts->sum('balance'), 2) }}</td>
                    </tr>

                    @if ($loop->iteration > 1)
                        @php $totalOwnerEquity += $totalBalance; @endphp
                    @else
                        @php $asset = $totalBalance; @endphp
                    @endif

                    @if($loop->last)
                        <tr>
                            <td class="text-right">
                                <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                    Liabilities and Owners Equity
                                </strong>
                            </td>
                            <td class="text-right pr-1">{{ number_format($totalOwnerEquity, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                    Current Equity
                                </strong>
                            </td>
                            <td class="text-right pr-1">{{ number_format($currentEquity = ($asset - $totalOwnerEquity), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                    Total Liabilities and Owners Equity
                                </strong>
                            </td>
                            <td class="text-right pr-1">{{ number_format($totalOwnerEquity + $currentEquity, 2) }}</td>
                        </tr>
                    @endif
                </x-mm.data-table>
                </div>
            @endforeach
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection
