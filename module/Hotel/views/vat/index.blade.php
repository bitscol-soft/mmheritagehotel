@extends('layouts.master')

@section('title','Vat & Services')

@section('page-header')
    <i class="fa fa-info-circle"></i> Vat & Services
@endsection

@section('content')


    <x-alert-message />

<div class="row">
    <div class="col-xs-6" style="margin-left: 25%">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>

                <div class="widget-body">
                    <div class="no-padding">
                        <div style="margin: 20px;"></div>
                        <form class="form-horizontal" id="companyForm" action="{{ route('vat.update',$vat->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Hotel VAT (%)</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <div class="input-group">
                                                <input type="number" class="form-control input-sm" name="hotel_vat" value="{{ $vat->hotel_vat }}" placeholder="Enter Vat Number (%)">
                                                <span class="input-group-addon">Persent (%)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Resturent VAT (%)</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <div class="input-group">
                                                <input type="number" class="form-control input-sm" name="resturent_vat" value="{{ $vat->resturent_vat }}" placeholder="Enter Vat Number (%)">
                                                <span class="input-group-addon">Persent (%)</span>
                                            </div>
                                            @error('resturent_vat')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Bar VAT (%)</label>
                                        <div class="col-xs-12 col-sm-8 @error('vat_number') has-error @enderror">
                                            <div class="input-group">
                                                <input type="number" class="form-control input-sm" name="bar_vat" value="{{ $vat->bar_vat }}" placeholder="Enter Vat Number (%)">
                                                <span class="input-group-addon">Persent (%)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">VAT Number</label>

                                        <div class="col-xs-12 col-sm-8 @error('vat_number') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="vat_number" value="{{ $vat->vat_number ?? '' }}" placeholder="Enter Vat Number">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Room Rate </label>
                                        <div class="col-xs-12 col-sm-8 @error('room_rate') has-error @enderror"">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm" name="room_rate" value="{{ $vat->room_rate ?? '' }}" placeholder="Enter Room Rate">
                                                <span class="input-group-addon">Amount 126.50 For 10(%) </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Room Service (%)</label>
                                        <div class="col-xs-12 col-sm-8 @error('room_service') has-error @enderror"">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm" name="room_service" value="{{ intval($vat->room_service_charge) ?? '' }}" placeholder="Enter room Service">
                                                <span class="input-group-addon">Persent (%)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Rst Service (%)</label>
                                        <div class="col-xs-12 col-sm-8 @error('rst_service_charge') has-error @enderror"">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm" name="rst_service_charge" value="{{ intval($vat->rst_service_charge) ?? '' }}" placeholder="Restaurant Service Charge">
                                                <span class="input-group-addon">Persent (%)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Included VAT Calc</label>
                                        <div class="col-xs-12 col-sm-8" style="background: #EDEDED; border-radius: 10px;">
                                            <label>
                                            <input type="radio" name="key[use_vat_included]"{{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                                Yes
                                            </label>
                                            <label>
                                            <input type="radio" name="key[use_vat_included]"{{ $systemSetting->value == '0' ? 'checked' : '' }} value="0">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-actions center mb-0" style="text-align: right !important;">
                                <button type="submit" class="btn-sm btn-outline-primary">
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
