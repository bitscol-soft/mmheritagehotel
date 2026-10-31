@extends('layouts.master')
@section('title','CRM Customer')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-inv mm-rst-form" title="Customer" description="Company / contact master used by bookings, banquet and POS.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('crm.customers.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{  route('crm.customers.store')  }}" method="post">
            @csrf
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">

                    <h5 class="tw-font-semibold tw-mb-2">Company</h5>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="org_name"> <b>Organization <sup class="text-danger">*</sup></b> </label>

                        <div class="col-sm-9">
                            <input required id="org_name" name="org_name" type="text" class="form-control input-sm" placeholder="Company / organization name" value="{{  old('org_name')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="org_phone"> <b>Organization Phone</b> </label>

                        <div class="col-sm-9">
                            <input id="org_phone" name="org_phone" type="text" class="form-control input-sm" placeholder="Phone" value="{{  old('org_phone')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="org_email"> <b>Organization Email</b> </label>

                        <div class="col-sm-9">
                            <input id="org_email" name="org_email" type="email" class="form-control input-sm" placeholder="Email" value="{{  old('org_email')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="tax_id"> <b>Tax ID</b> </label>

                        <div class="col-sm-9">
                            <input id="tax_id" name="tax_id" type="number" class="form-control input-sm" placeholder="Tax / VAT registration" value="{{  old('tax_id')  }}">
                        </div>
                    </div>

                    <h5 class="tw-font-semibold tw-mb-2 tw-mt-3">Contact Person</h5>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="c_name"> <b>Name</b> </label>

                        <div class="col-sm-9">
                            <input id="c_name" name="c_name" type="text" class="form-control input-sm" placeholder="Contact person" value="{{  old('c_name')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="c_phone"> <b>Phone</b> </label>

                        <div class="col-sm-9">
                            <input id="c_phone" name="c_phone" type="text" class="form-control input-sm" placeholder="Contact phone" value="{{  old('c_phone')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="c_email"> <b>Email</b> </label>

                        <div class="col-sm-9">
                            <input id="c_email" name="c_email" type="email" class="form-control input-sm" placeholder="Contact email" value="{{  old('c_email')  }}">
                        </div>
                    </div>

                    <h5 class="tw-font-semibold tw-mb-2 tw-mt-3">Address</h5>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="street"> <b>Street</b> </label>

                        <div class="col-sm-9">
                            <input id="street" name="street" type="text" class="form-control input-sm" placeholder="Street" value="{{  old('street')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="city"> <b>City</b> </label>

                        <div class="col-sm-9">
                            <input id="city" name="city" type="text" class="form-control input-sm" placeholder="City" value="{{  old('city')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="zip_code"> <b>Zip Code</b> </label>

                        <div class="col-sm-9">
                            <input id="zip_code" name="zip_code" type="number" class="form-control input-sm" placeholder="Zip code" value="{{  old('zip_code')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="country_id"> <b>Country</b> </label>

                        <div class="col-sm-9">
                            <select id="country_id" name="country_id" class="chosen-select-100-percent" data-placeholder="- Select Country -">
                                <option></option>
                                @foreach($countries as $key => $name)
                                <option value="{{ $key }}" {{ old('country_id') == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="address"> <b>Address</b> </label>

                        <div class="col-sm-9">
                            <input id="address" name="address" type="text" class="form-control input-sm" placeholder="Full address" value="{{  old('address')  }}">
                        </div>
                    </div>

                    <h5 class="tw-font-semibold tw-mb-2 tw-mt-3">Business &amp; Lead</h5>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="currency"> <b>Currency</b> </label>

                        <div class="col-sm-9">
                            <select id="currency" name="currency" class="chosen-select-100-percent" data-placeholder="- Select Currency -">
                                <option></option>
                                @foreach($currencies as $key => $name)
                                <option value="{{ $name }}" {{  old('currency')  == $name ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="assigned"> <b>Assigned To</b> </label>

                        <div class="col-sm-9">
                            <select id="assigned" name="assigned" class="chosen-select-100-percent" data-placeholder="- Select User -">
                                <option></option>
                                @foreach($users as $key => $name)
                                <option value="{{ $name }}" {{  old('assigned')  == $name ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="contact_date"> <b>Contact Date</b> </label>

                        <div class="col-sm-9">
                            <input id="contact_date" name="contact_date" type="date" class="form-control input-sm" value="{{  old('contact_date')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="leads"> <b>Is Lead</b> </label>

                        <div class="col-sm-9">
                            <label class="tw-inline-flex tw-items-center tw-gap-2">
                                <input id="leads" name="leads" type="checkbox" value="1" {{  old('leads') ? 'checked' : ''  }}>
                                <span class="lbl">This company is still a lead</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="lead_status"> <b>Lead Status</b> </label>

                        <div class="col-sm-9">
                            <input id="lead_status" name="lead_status" type="text" class="form-control input-sm" placeholder="Lead status" value="{{  old('lead_status')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="lead_source"> <b>Lead Source</b> </label>

                        <div class="col-sm-9">
                            <input id="lead_source" name="lead_source" type="text" class="form-control input-sm" placeholder="Lead source" value="{{  old('lead_source')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Save</button>
                            <a href="{{ route('crm.customers.index') }}" class="btn btn-gray btn-sm"><i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
@endsection
