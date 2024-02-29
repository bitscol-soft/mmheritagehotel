@extends('layout.app')
@section('title', 'Medical Service Due Receive List')
@section('content')
    <div id="content" class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-heading-btn pull-right">
                            <a class="btn btn-success btn-sm"  href="{{action('ServiceSalesDueReceiveController@index')}}">Receive Due</a>
                        </div>
                        <h4 class="panel-title">Due Receipt</h4>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">
                            <h1>Due Payment Receipt</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@stop
