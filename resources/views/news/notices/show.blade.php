@extends('layouts.master')

@section('title','Notice Details')

@section('page-header')
    <i class="fa fa-list"></i> Notice Details
@stop

@section('css')

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
                        <table style="font-size: 13px">
                            <tr>
                                <td>Publish At</td>
                                <td style="width: 20px;" class="text-center">:</td>
                                <td>{{ fdate($notice->publish_at, 'Y-m-d') . ' at ' . fdate($notice->publish_at, 'h:i:s a') }}</td>
                            </tr>
                            <tr>
                                <td>Company</td>
                                <td style="width: 20px;" class="text-center">:</td>
                                <td>{{ $notice->company->name }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-12">
                        <h3 class="text-primary">Title: {{ $notice->title }}</h3>
                    </div>
                    <div class="col-sm-12">
                        {!! $notice->description !!}
                        <br><br>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    
@endsection


