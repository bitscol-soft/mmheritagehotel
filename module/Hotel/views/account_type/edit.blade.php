@extends('layouts.master')

@section('title',' Edit Account Type')
@section('page-header')
<i class="fa fa-gears"></i> Edit Account Type
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop


@section('content')

<div class="page-header">
    {{-- <h1>
        <i class="fa fa-info-circle green"></i> Projects Name List
    </h1> --}}
</div>

@include('partials._alert_message')

<div class="row">
    <div class="col-xs-6" style="margin-left: 25%">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>

                <div class="widget-body">
                    <div class="no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('account-type.update', $account->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Account Type</label>

                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                            {!! Form::text('name', $account->name, ['class' => 'form-control', 'placeholder' => 'Edit account']) !!}

                                            @error('name')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Status</label>

                                        <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                            <select name="status" class="select2" style="width: 100%">
                                                <option value="1" {{ $account->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $account->status == 0 ? 'selected' : '' }}>In Active</option>
                                            </select>
                                            @error('status')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection

@section('js')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>




<!-- inline scripts related to this page -->
<script type="text/javascript">
    function delete_check(id) {
        Swal.fire({
            title: 'Are you sure ?',
            html: "<b>You want to delete permanently !</b>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            width: 400,
        }).then((result) => {
            if (result.value) {
                $('#deleteCheck_' + id).submit();
            }
        })

    }
</script>
@stop
