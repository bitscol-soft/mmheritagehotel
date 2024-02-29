@extends('layouts.master')
@section('title','Fund Transfers')
@section('page-header')
    <i class="fa fa-list"></i> Fund Transfers
@stop
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
    <div class="row">
        <div class="col-sm-12">

            @include('partials._alert_message')

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{request()->url()}}" class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>

                                @if(hasPermission("fund.transfers.create", $slugs))
                                    <a href="{{route('fund-transfers.create')}}" class="dt-button btn btn-white btn-info btn-bold" title="Create New" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                                        <span>
                                            <i class="fa fa-plus bigger-110"></i>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12">
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
                                                        <a href="#" onclick="delete_item('{{ route('fund-transfers.destroy', $item->id) }}')" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('partials._paginate', ['data' => $transfers])
                    </div>
                </div>

            </div>
        </div>
    </div>

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


