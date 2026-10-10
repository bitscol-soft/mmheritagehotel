@extends('layouts.master')

@section('title', 'User Activity Logs')

@section('css')
<style>
    table td {
        word-wrap: break-word;
    }
</style>
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-index" title="User Activity Logs">
        <x-mm.panel>
    @include('partials._alert_message')


    <!-- Searching -->
    <div class="row">
        <div class="col-md-12">
            <form>
                <div class="col-sm-10 col-sm-offset-1">
                    <table class="table table-bordered">
                        <tr>
                            <td width="30%">
                                <select name="user_id" class="form-control input-sm chosen-select-100-percent" title="All User" data-placeholder="- All User -">
                                    <option disabled selected>-User Search-</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td width="20%">
                                <select name="log_name" class="form-control input-sm chosen-select-100-percent" title="All Log Name" data-placeholder="- All Log Name -">
                                    <option disabled selected>-Log Name-</option>
                                    @foreach ($log_names as $log_name)
                                    <option value="{{ $log_name->log_name }}" {{ request('log_name') == $log_name->log_name ? 'selected' : '' }}>{{ $log_name->log_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td width="30%">
                                <div class="input-daterange input-group">
                                    <input type="text" class="input-sm form-control" name="from"
                                        value="{{ request('from') }}" autocomplete="off" placeholder="From" />
                                    <span class="input-group-addon">
                                        <i class="fa fa-exchange"></i>
                                    </span>

                                    <input type="text" class="input-sm form-control" name="to"
                                        value="{{ request('to') }}" autocomplete="off" placeholder="To" />
                                </div>
                            </td>
                            <td width="20%">
                                <div class="btn-group">
                                    <button class="btn btn-info btn-xs">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                    <a href="{{ request()->url() }}" class="btn btn-default btn-xs">
                                        <i class="fa fa-refresh"></i> Refresh
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12" style="margin-left:auto !important; margin-right:auto !important">

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table  class="table table-striped table-bordered table-hover" style="table-layout: fixed; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="10%">User Name</th>
                            <th width="20%">Log Name</th>
                            <th width="10%" class="text-center">Date</th>
                            <th width="45%">Description</th>
                            <th width="5%">Properties</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($logs as $key => $log)
                            <tr>
                                <td width="5%">{{ $key + 1 }}</td>
                                <td width="15%">{{ optional($log->causer)->name }}</td>
                                <td width="20%">{{ $log->log_name }}</td>
                                <td width="10%" class="text-center">{{ date('Y-m-d h:i:s a', strtotime($log->created_at)) }}</td>
                                <td width="45%">{{ $log->description }}</td>
                                <td width="5%" class="text-center">
                                    <a class="btn btn-primary btn-sm" data-toggle="collapse" href="#collapseExample{{ $log->id }}" role="button" aria-expanded="false" aria-controls="collapseExample{{ $log->id }}">
                                        <i class="fa fa-info"></i>
                                      </a>
                                 </td>

                            </tr>
                            <tr>
                                <td colspan="6" style="padding: 0px">
                                    <div class="collapse" id="collapseExample{{ $log->id }}">
                                        <div class="card">
                                            <div class="card-body" style="padding: 7px">
                                                <span>
                                                    @php
                                                        // echo gettype(json_decode($log->properties));
                                                        if(gettype(json_decode($log->properties)) == 'array'){
                                                            foreach(json_decode($log->properties) as $property){
                                                                echo json_encode($property);
                                                            }
                                                        }else{
                                                            echo $log->properties;
                                                        }
                                                    @endphp
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @include('partials._paginate',['data'=>$logs])
            </div>

        </div>
    </div>
        </x-mm.panel>
    </x-mm.page>


@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>


    <script src="{{ asset('assets/custom_js/custom-datatable.js') }}"></script>


        <!--date range picker-->
        <script type="text/javascript">
            jQuery(function($) {


                $('.input-daterange').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight: true
                });


                //to translate the daterange picker, please copy the "examples/daterange-fr.js" contents here before initialization
                $('input[name=date-range-picker]').daterangepicker({
                        'applyClass': 'btn-sm btn-success',
                        'cancelClass': 'btn-sm btn-default',
                        locale: {
                            applyLabel: 'Apply',
                            cancelLabel: 'Cancel'
                        }
                    })
                    .prev().on(ace.click_event, function() {
                        $(this).next().focus();
                    });

            })
        </script>


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
