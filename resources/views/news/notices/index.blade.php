@extends('layouts.master')

@section('title','Notice List')

@section('page-header')
    <i class="fa fa-list"></i> Notice List
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style type="text/css">
        .rate-entry-table td, tr {
            border: none !important;
        }
    </style>
@stop


@section('content')

    <div class="row">
        <div class="col-sm-12">

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')

                        @if(hasPermission('notices.create', $slugs))
                            <span style="font-size: 14px; padding-right: 20px !important;" class="pull-right">|
                                <a href="{{ route('notices.create') }}"><i class="fa fa-plus"></i> Add New</a>
                            </span>
                        @endif
                    </h3>
                </div>


                <div class="space"></div>


                <!-- entry form -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <th>Sl</th>
                                    <th>Date</th>
                                    <th>Company</th>
                                    <th>Title</th>
                                    <th style="width: 130px" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($notices as $key => $notice)
                                    <tr class="text-{{ $notice->is_view == 1 ? ''  : 'primary' }}">
                                        <td>{{ $key + $notices->firstItem() }}  </td>
                                        <td>{{ fdate($notice->publish_at) }}</td>
                                        <td>{{ $notice->company->name }}</td>
                                        <td>{{ $notice->title }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                <span class="btn btn-info btn-xs popover-success"
                                                      data-rel="popover"
                                                      data-placement="top"
                                                      data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Log Information"
                                                      data-content="<p>Created By: {{ optional($notice->created_user)->name }}.</p> <p> Created At : {{ $notice->created_at }} </p>
                                                       <hr/>
                                                       <p>Approved By: {{ optional($notice->approved_user)->name }}.</p> <p> Updated At : {{ $notice->update_at }} </p>">
                                                    <i class="fa fa-info-circle"></i>
                                                </span>

                                                <a href="{{ route('notices.show', $notice->id) }}" target="__blank" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>

                                                @if(hasPermission('notices.edit', $slugs))
                                                    <a href="{{ route('notices.edit', $notice->id) }}" class="btn btn-xs btn-blue"><i class="fa fa-edit"></i></a>
                                                @endif

                                                @if(hasPermission('notices.delete', $slugs))
                                                    <button class="btn btn-xs btn-danger" onclick="delete_item('{{ route('notices.destroy', $notice->id) }}')" type="button">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="text-center text-danger">No records found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @include('partials._paginate', ['data' => $notices])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


    <!--  User log popover -->
    <script type="text/javascript">
        $('[data-rel=popover]').popover({ html:true, container:'body' });
    </script>

    <!-- delete confirm dialog -->
    <script type="text/javascript" src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

    <!--  Select Box Search-->
    <script type="text/javascript" src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <!--  date Picker-->
    <script type="text/javascript" src="{{ asset('assets/custom_js/date-picker.js') }}"></script>



@endsection


