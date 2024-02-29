@extends('layouts.master')

@section('title', 'Account Opening Balance')

@section('page-header')
    <i class="fa fa-info-circle"></i> Account Opening Balance
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12">

        @include('partials._alert_message')

            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                
                
                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller">
                        <a href="{{ request()->url() }}">
                            <i class="fa fa-refresh bigger-110"></i> Refresh
                        </a>
                    </div>

                    <div class="widget-toolbar border smaller">
                        @if(hasPermission('accounts.create', $slugs)))
                            <a href="{{ route('accounts.create') }}" >
                                <i class="fa fa-plus bigger-110"></i> Add New
                            </a>
                        @endif
                    </div>
                </div>



                <div class="space"></div>




                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">

                    <form action="" method="GET">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <label class="input-group-addon">Company <strong class="text-danger">*</strong></label>
                                <select class="form-control chosen-select-100-percent required" required name="company_id">
                                    <option></option>
                                    @foreach($companies as $id => $name)
                                        <option value="{{ $id }}" {{ $id == request('company_id') ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
    
                        <div class="col-sm-3">
                            <div class="input-group">
                                <label class="input-group-addon">Account Group</label>
                                <select class="form-control chosen-select-100-percent" id="account_group_id" name="account_group_id">
                                    <option></option>
                                    @foreach($accountGroups as $id => $name)
                                        <option value="{{ $id }}" {{ $id == request('account_group_id') ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
    
                        <div class="col-sm-3">
                            <div class="input-group">
                                <label class="input-group-addon">Account Control</label>
                                <select class="form-control chosen-select-100-percent" id="account_control_id" name="account_control_id">
                                    <option></option>
                                    @foreach($accountControls as $id => $name)
                                        <option value="{{ $id }}" {{ $id == request('account_control_id') ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
    
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-circle"></i> Get Data</button>
                        </div>
                    </form>


                    @if (request()->company_id != '')
                        
                        <form action="{{ route('account-opening-balances.store') }}" method="POST">
                            @csrf




                            <input type="hidden" name="company_id" value="{{ request('company_id') }}">




                            <div class="col-sm-10 col-sm-offset-1 mt-2">

                                <table id="data-table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr class="table-header-bg">
                                            <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                            <th class="pl-3" style="color: white !important;">Account Name</th>
                                            <th class="pl-3" style="color: white !important;">Opening</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($accounts as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="pl-3">{{ $item->name }}</td>
                                                <td class="pl-3">
                                                    <input type="hidden" name="account_ids[]" value="{{ $item->id }}">
                                                    <input type="number" class="form-control only-number text-center" autocomplete="off" name="amounts[]" value="{{ optional($item->opening_balances->first())->amount }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>



                                @include('partials._paginate', ['data' => $accounts])

                            </div>




                            <div class="col-sm-10 col-sm-offset-1 mb-2 text-right">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </form>
                    @endif

                </div>


                <div class="space"></div>
            </div>
        </div>
    </div>
@endsection





@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>


    <script>
        $(document).ready(function () {
            const accountControlId = $('#account_control_id');
            const accountSubsidiaryId = $('#account_subsidiary_id');

            $('#account_group_id').change(function () {
                $.get(`{{route('ajax.account-controls')}}?account_group_id=${$(this).val()}`, function (res) {
                    accountControlId.empty().append('<option></option>')

                    res.forEach(function (item) {
                        accountControlId.append(`<option value="${item.id}">${item.name}</option>`)
                    })

                    accountControlId.trigger('chosen:updated');
                    accountControlId.trigger('change')
                })
            })

            $('#account_control_id').change(function () {
                $.get(`{{route('ajax.account-subsidiaries')}}?account_control_id=${$(this).val()}`, function (res) {
                    accountSubsidiaryId.empty().append('<option></option>')

                    res.forEach(function (item) {
                        accountSubsidiaryId.append(`<option value="${item.id}">${item.name}</option>`)
                    })

                    accountSubsidiaryId.trigger('chosen:updated');
                })
            })
        });
    </script>
@endsection


