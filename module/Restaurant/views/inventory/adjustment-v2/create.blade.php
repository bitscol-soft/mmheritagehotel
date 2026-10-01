@extends('layouts.master')
@section('title', 'Stock Adjustment Create')

@section('page-header')
    Stock Adjustment Create
@stop


@push('style')
    <style>
        input.form-control.small-box {
            height: 28px;
        }

        input.form-control.small-label-box {
            height: 24px;
            padding: 1.5px;
            border: 1px solid rgba(204, 204, 204, 0.51) !important;
        }

        .table>thead {
            background: #ddd;
        }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-box">
                <div class="widget-header">

                    <h4 class="widget-title"> <i class="fa fa-plus-circle"></i> @yield('page-header')</h4>

                    @if (hasPermission('rst.stock-adjustment.index', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('rst.stock-adjustment.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> List
                            </a>
                        </span>
                    @endif
                </div>
                <div class="widget-body">

                    <div class="widget-main">


                        <x-alert-message />


                        <div class="row">
                            <form method="POST" action="{{ route('rst.stock-adjustment.store') }}" id="purchase-form">
                                @csrf

                                <div class="col-md-12">
                                    @include('inventory.adjustment-v2.inc.common')
                                    <div class="row">
                                        <div class="col-md-12">

                                            @include('inventory.adjustment-v2.create.left-side')

                                        </div>
                                        <div class="col-md-8">

                                        </div>
                                        <div class="col-md-4">

                                            @include('inventory.adjustment-v2.create.right-side')

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('inventory.adjustment-v2/inc/script')
@endsection
