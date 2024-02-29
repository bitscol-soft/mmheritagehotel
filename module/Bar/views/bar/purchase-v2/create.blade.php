@extends('layouts.master')
@section('title', 'Purchase Create')

@section('page-header')
    Purchase Create
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

      .table>thead{
        background: #ddd;
      }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="widget-box">
                <div class="widget-header">

                    <h4 class="widget-title"> <i class="fad fa-plus-circle"></i>  @yield('page-header')</h4>

                    @if (hasPermission('bar.purchases.index', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('bar.purchases.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> List
                            </a>
                        </span>
                    @endif
                </div>
                <div class="widget-body">

                    <div class="widget-main">


                        <x-alert-message />


                        <div class="row">
                            <form method="POST" action="{{ route('bar.purchases.store') }}" id="purchase-form">
                                @csrf

                                <div class="col-md-12">
                                    @include('bar.purchase-v2.inc.common')
                                    <div class="row">
                                        <div class="col-md-9">

                                            @include('bar.purchase-v2.create.left-side')

                                        </div>
                                        <div class="col-md-3">

                                            @include('bar.purchase-v2.create.right-side')

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
    @include('bar.purchase-v2/inc/script')
@endsection
