@extends('layouts.master')
@section('title', 'Journal Vouchers')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .table {
            margin-bottom: 0 !important;
        }

    </style>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Journal Vouchers" description="Journal vouchers with approval status.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        @if (hasPermission('voucher-journals.create', $slugs))
            <a class="mm-button" href="{{ route('voucher-journals.create') }}"><i class="fa fa-plus"></i> Create New</a>
        @endif
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">
            <div class="input-group">
                <label class="input-group-addon" for="">
                    Invoice No
                </label>
                <input type="text" name="invoice_no" value="{{ request('invoice_no') }}" class="form-control" placeholder="Invoice">
            </div>

            <div class="input-group">
                <label class="input-group-addon" for="">
                    Reference
                </label>
                <input type="text" name="reference" value="{{ request('reference') }}" class="form-control" placeholder="Reference">
            </div>

            <div class="input-group">
                <input type="text" class="form-control input-sm date-picker" name="from_date"
                    value="{{ request('from_date') }}" autocomplete="off">
                <span class="input-group-addon">From|To</span>
                <input type="text" class="form-control input-sm date-picker" name="to_date"
                    value="{{ request('to_date') }}" autocomplete="off">
            </div>

            <div class="btn-group">
                <button type="submit" class="mm-button"><i class="fa fa-search"></i> Search</button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary"><i class="fa fa-refresh"></i> Refresh</a>
            </div>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
         <!-- Filtering -->
         <!-- LIST -->
         <div class="row" style="width: 100%; margin: 0 !important; margin-bottom: 20px !important">
             <div class="col-sm-12">
                 <x-mm.table-scroll label="Journal Vouchers">
                     <table class="table table-bordered table-striped">
                         <thead>
                             <tr class="table-header-bg">
                                 <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                 <th class="pl-3" style="color: white !important;" width="20%">Invoice No</th>
                                 <th class="pl-3" style="color: white !important;">Date</th>
                                 <th class="pl-3" style="color: white !important;" width="15%">Reference</th>
                                 <th class="pr-3 text-right" style="color: white !important;">Amount</th>
                                 <th class="text-center" style="color: white !important;">Status</th>
                                 <th class="text-center" style="color: white !important;">Actions</th>
                             </tr>
                         </thead>

                         <tbody>
                             @forelse ($vouchers as $item)
                                 <tr>
                                     <td class="text-center">{{ $loop->iteration }}</td>
                                     <td class="pl-3">{{ $item->invoice_no }}</td>
                                     <td class="pl-3">{{ $item->date }}</td>
                                     <td class="pl-3">{{ $item->reference }}</td>
                                     <td class="pr-3 text-right">{{ number_format($item->amount, 2) }}</td>
                                     <td class="text-center">
                                         {!! $item->is_approved == 1 ? '<span class="label label-info">Approved</span>' : '<span class="label label-warning">Unapproved</span>' !!}
                                     </td>
                                     <td class="text-center">
                                         <div class="btn-group btn-corner">
                                             @include('partials._user-log', ['data' => $item])

                                             <a href="{{ route('voucher-journals.show', $item->id) }}" target="_blank"
                                                 class="btn btn-success btn-xs" title="View Details"><i
                                                     class="fa fa-eye"></i></a>

                                             @if (!$item->is_approved)
                                                 @if (hasPermission('voucher-journals.edit', $slugs))
                                                     <a href="{{ route('voucher-journals.edit', $item->id) }}"
                                                         class="btn btn-primary btn-xs" title="Edit"><i
                                                             class="fa fa-pencil"></i></a>
                                                 @endif
                                             @endif
                                             @if (hasPermission('voucher-journals.delete', $slugs))
                                                 <a href="#"
                                                     onclick="delete_item('{{ route('voucher-journals.destroy', $item->id) }}')"
                                                     class="btn btn-danger btn-xs" title="Delete"><i
                                                         class="fa fa-trash-o"></i></a>
                                             @endif
                                         </div>
                                     </td>
                                 </tr>
                             @empty
                                 <tr>
                                     <th colspan="30" class="text-center">
                                         <br>
                                         <strong class="text-danger" style="font-size: 18px">No records found!</strong>
                                         <br>
                                         <br>
                                     </th>
                                 </tr>
                             @endforelse 
                         </tbody>
                     </table>
                 </x-mm.table-scroll>

                 @include('partials._paginate', ['data' => $vouchers])

             </div>
         </div>
    </x-mm.panel>
</x-mm.page>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.date-picker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });
        });
    </script>

@endsection
