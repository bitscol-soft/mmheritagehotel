@extends('layouts.master')
@section('title','Fund Transfers')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <style>
        .table {
            margin-bottom: 0 !important;
        }
    </style>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv" title="Fund Transfers" description="Fund transfers between accounts.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{request()->url()}}"><i class="fa fa-refresh"></i> Refresh Data</a>
        @if(hasPermission("fund.transfers.create", $slugs))
            <a class="mm-button" href="{{route('fund-transfers.create')}}"><i class="fa fa-plus"></i> Create New</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12">
                <x-mm.table-scroll label="Fund Transfers">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="table-header-bg">
                                <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                <th class="pl-3" style="color: white !important;"  width="20%">Invoice No</th>
                                <th class="pl-3" style="color: white !important;" >Date</th>
                                <th class="pl-3" style="color: white !important;" width="15%">From Account</th>
                                <th class="pl-3" style="color: white !important;" width="15%">To Account</th>
                                <th class="pr-3 text-right" style="color: white !important;" >Amount</th>
                                <th class="pl-3" style="color: white !important;" >Description</th>
                                <th class="text-center" style="color: white !important;" >Status</th>
                                <th class="text-center" style="color: white !important;" width="12%">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($transfers as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="pl-3">{{ $item->invoice_no }}</td>
                                    <td class="pl-3">{{ $item->date }}</td>
                                    <td class="pl-3">{{ $item->fromAccount->name }}</td>
                                    <td class="pl-3">{{ $item->toAccount->name }}</td>
                                    <td class="pr-3 text-right">{{ $item->amount }}</td>
                                    <td class="pl-3">{{$item->description}}</td>
                                    <td class="text-center">
                                        {!! $item->is_approved == 1 ? '<span class="label label-info">Approved</span>' : '<span class="label label-warning">Unapproved</span>' !!}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            @include('partials._user-log', ['data' => $item])

                                            @if(!$item->is_approved)
                                                @if(hasPermission("fund.transfers.approve", $slugs))
                                                    <a href="#" onclick="approve_item('{{route('fund-transfers.approve.update', $item->id)}}')" class="btn btn-purple btn-xs" title="Approve Fund Transfer"><i class="fa fa-check"></i></a>
                                                @endif
                                                @if(hasPermission("fund.transfers.edit", $slugs))
                                                    <a href="{{route('fund-transfers.edit', $item->id)}}" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
                                                @endif
                                                @if(hasPermission("fund.transfers.delete", $slugs))
                                                    <a href="#" onclick="delete_item('{{ route('fund-transfers.destroy', $item->id) }}')" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash-o"></i></a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-mm.table-scroll>

                @include('partials._paginate', ['data' => $transfers])
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

    <!-- delete form -->
    <form action="" id="approveForm" method="POST">
        @csrf
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    <script>
        function approve_item(url) {
            $('#approveForm').attr('action', url).submit();
        }
    </script>

@endsection

