@extends('layouts.master')
@section('title', 'Send SMS')
@section('page-header')
    <i class="fas fa-comments"></i> Send SMS
@stop


@section('css')
    @include('guests.include.css')
@stop



@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">

                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        <a href="{{ route('guests.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Guest List
                        </a>
                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('guests.submit-sms') }}"
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row" style="margin: 0 15px 25px 15px">
                                <div class="col-sm-5">
                                    <div class="widget-box mobile-widget-box widget-color-dark ui-sortable-handle"
                                        style="opacity: 1; background: #f4f4f4 !important;">
                                        <div class="widget-header widget-header-small"
                                            style="background: transparent !important;">
                                            <h6 class="widget-title smaller" style="color: #28282B; font-weight: bold;">
                                                MOBILE</h6>
                                        </div>

                                        <div class="widget-body" style="background: #f4f4f4 !important;">
                                            <div class="widget-main">
                                                <div class="inline">
                                                    @if ($phones != '')
                                                        <input type="hidden" name="isFromGuestList" value="1">
                                                        <input class="multiple-phone-input" type="text" name="phone_no"
                                                            id="form-field-tags" value="{{ $phones }}"
                                                            placeholder="Enter Mobile Numbers..." />
                                                    @else
                                                        <input class="multiple-phone-input" type="text" name="phone_no"
                                                            id="form-field-tags" value=""
                                                            placeholder="Enter Mobile Numbers..." />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="widget-box message-widget-box widget-color-dark ui-sortable-handle"
                                        style="opacity: 1;">
                                        <div class="widget-header widget-header-small"
                                            style="background: #f4f4f4 !important;">
                                            <h6 class="widget-title smaller" style="color: #28282B; font-weight: bold;">
                                                MESSAGE</h6>
                                        </div>

                                        <div class="widget-body">
                                            <div class="widget-main" style="background-color: #f4f4f4">
                                                <textarea name="message" class="textarea message-area" placeholder="Write Messages..." rows="10" cols="40"
                                                    autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="widget-box sms-widget-box widget-color-dark ui-sortable-handle"
                                        style="border-radius: 4px; opacity: 1; background: #f4f4f4 !important;">
                                        <div class="widget-header widget-header-small"
                                            style="background: #eaf4fa !important; color:#28282B;">
                                            <h6 class="widget-title smaller"
                                                style="font-size: 13px; font-weight: bold !important;">SMS CONFIGURATION
                                            </h6>
                                        </div>

                                        <div class="widget-body" style="background: #f4f4f4 !important;">
                                            <div class="widget-main">
                                                <div class="row">
                                                    <div class="col-lg-5">Balance</div>
                                                    <div class="col-lg-7">: {{ $smsbal }}</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-7">Character</div>
                                                    <div class="col-lg-5">: <span class="total-character-count">0</span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-7">SMS</div>
                                                    <div class="col-lg-5">: <span class="">0</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="form-actions center" style="text-align: right !important; margin-bottom: 0;">
                                <a href="{{ route('guests.index') }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-backward"></i> Back List
                                </a>
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fas fa-paper-plane icon-on-right bigger-110"></i> Send SMS
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection



@section('js')
    @include('guests.include.script')
@endsection
