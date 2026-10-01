
@extends('layouts.master')
@section('title','Edit Company')
@section('page-header')
    <i class="fa fa-pencil-square-o"></i> Edit Company
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop


@section('content')

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">
                        @if (hasPermission("company.infos.view", $slugs))
                            <a href="{{ route('company.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> Company List
                            </a>
                        @endif
                        </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('company.update',$company->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-sm-6">

                                    <h4 class="text-center">Company Info</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Company Name </label>

                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                            <input type="text" class="form-control" name="name"
                                                   value="{{ old('name', $company->name) }}" placeholder="Company Name">

                                            @error('name')
                                                <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Business Type </label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="business_type" value="{{ old('business_type', $company->business_type) }}" placeholder="Business Type">
                                        </div>
{{--                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">--}}
{{--                                            <select name="business_type_id" class="chosen-select" data-placeholder="Choose a Business Type...">--}}
{{--                                                <option value=""></option>--}}
{{--                                                @foreach($business_types as $business_type)--}}
{{--                                                    <option {{ $company->business_type_id == $business_type->id ? 'selected' : '' }} {{ old('business_type_id') == $business_type->id ? 'selected' : '' }} value="{{ $business_type->id }}">{{ $business_type->name }}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}

{{--                                            @error('name')--}}
{{--                                            <span class="text-danger">--}}
{{--                                                     {{ $message }}--}}
{{--                                                </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Company Code </label>

                                        <div class="col-xs-12 col-sm-8 @error('code') has-error @enderror">
                                            <input type="number" class="form-control" id="code" name="code"
                                                   value="{{ $company->code ?: old('code') }}" placeholder="Company Code">

                                            @error('code')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Short Name </label>

                                        <div class="col-xs-12 col-sm-8 @error('short_name') has-error @enderror">
                                            <input type="text" class="form-control" name="short_name"
                                                   value="{{ $company->short_name ?:  old('short_name') }}" placeholder="Company Short Name">

                                            @error('short_name')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Head Office </label>

                                        <div class="col-xs-12 col-sm-8 @error('head_office') has-error @enderror">
                                        <textarea class="form-control" name="head_office" rows="6"
                                                  placeholder="Head Office">{{ $company->head_office ?: old('head_office') }}</textarea>

                                            @error('head_office')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Factory </label>

                                        <div class="col-xs-12 col-sm-8 @error('factory') has-error @enderror">
                                        <textarea class="form-control" name="factory" rows="6"
                                                  placeholder="Factory">{{ $company->factory ?: old('factory') }}</textarea>

                                            @error('factory')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Contact Name </label>

                                        <div class="col-xs-12 col-sm-8 @error('contact_name') has-error @enderror">
                                            <input type="text" class="form-control" name="contact_name"
                                                   value="{{ $company->contact_name ?: old('contact_name') }}" placeholder="Company Contact Name">

                                            @error('contact_name')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Position </label>

                                        <div class="col-xs-12 col-sm-8 @error('position') has-error @enderror">
                                            <input type="text" class="form-control" name="position"
                                                   value="{{ $company->position ?: old('position') }}" placeholder="Position">

                                            @error('position')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Phone Number </label>

                                        <div class="col-xs-12 col-sm-8 @error('phone_number') has-error @enderror">
                                            <input type="number" class="form-control" name="phone_number"
                                                   value="{{ $company->phone_number ?: old('phone_number') }}" placeholder="Phone Number">

                                            @error('phone_number')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Fax </label>

                                        <div class="col-xs-12 col-sm-8 @error('fax') has-error @enderror">
                                            <input type="text" class="form-control" name="fax"
                                                   value="{{ $company->fax ?: old('fax') }}" placeholder="Fax">

                                            @error('fax')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Email </label>

                                        <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ $company->email ?: old('email') }}" placeholder="Email">

                                            @error('email')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Day Off </label>

                                        <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">

                                            <select name="day_off" class="chosen-select"  data-placeholder="Choose a Day...">
                                                <option value=""></option>

                                                @for($i = 4; $i <= 10; $i++)
                                                    <option {{ $company->day_off == \Carbon\Carbon::now()->subDays($i)->format('l') ? 'selected' : '' }} value="{{ \Carbon\Carbon::now()->subDays($i)->format('l') }}">
                                                        {{ \Carbon\Carbon::now()->subDays($i)->format('l') }}
                                                    </option>
                                                @endfor


                                            </select>

                                            @error('email')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Country </label>

                                        <div class="col-xs-12 col-sm-8 @error('country') has-error @enderror">
                                            <input type="text" class="form-control" name="country"
                                                   value="{{ $company->country ?: old('country') }}" placeholder="Country">

                                            @error('country')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Start header -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="header"> Header </label>

                                        <div class="col-sm-5">
                                            <input type="file" name="header" id="header" />
                                        </div>
                                        <div class="col-sm-2">
                                            @if($company->company_details && $company->company_details->header)
                                                <img src="{{ asset('uploads/company/extra/'.$company->company_details->header) }}" style="width: 100px" alt="{{ $company->name }}">
                                            @endif
                                        </div>
                                    </div>
                                    <!-- End header -->

                                </div>

                                <div class="col-sm-6">
                                    <h4 class="text-center">Company Details Info</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Company Logo </label>

                                        <div class="col-xs-12 col-sm-5">
                                            <input type="file" name="logo" id="id-input-file-3" />
                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                            @if ($company->logo == 'default.png')
                                                <img src="{{ asset('uploads/'.$company->logo) }}" style="width: 100px" alt="{{ $company->name }}">
                                            @else
                                                <img src="{{ asset('uploads/company/'.$company->logo) }}" style="width: 100px" alt="{{ $company->name }}">
                                            @endif
                                         </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Top Text </label>

                                        <div class="col-xs-12 col-sm-7 @error('top_text') has-error @enderror">
                                        <textarea class="form-control" name="top_text" rows="6"
                                                  placeholder="Top text">{{ $company->top_text ?: old('top_text') }}</textarea>

                                            @error('top_text')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> BIN No. </label>

                                        <div class="col-xs-12 col-sm-7 @error('vat_no') has-error @enderror">
                                            <input type="text" class="form-control" name="vat_no"
                                                   value="{{ ($company->company_details ? $company->company_details->vat_no : '' ) ?: old('vat_no') }}" placeholder="BIN No.">

                                            @error('vat_no')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Facsimile Number. </label>

                                        <div class="col-xs-12 col-sm-7 @error('facsimile_number') has-error @enderror">
                                            <input type="text" class="form-control" name="facsimile_number"
                                                   value="{{ ($company->company_details ? $company->company_details->facsimile_number : '' ) ?: old('facsimile_number') }}" placeholder="Facsimile Number .">

                                            @error('facsimile_number')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Bonded/Trade License. </label>

                                        <div class="col-xs-12 col-sm-7 @error('bonded_license') has-error @enderror">
                                            <input type="text" class="form-control" name="bonded_license"
                                                   value="{{ ($company->company_details ? $company->company_details->bonded_license : '' ) ?: old('bonded_license') }}" placeholder="Bonded License .">

                                            @error('bonded_license')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Membership Number. </label>

                                        <div class="col-xs-12 col-sm-7 @error('membership_number') has-error @enderror">
                                            <input type="text" class="form-control" name="membership_number"
                                                   value="{{ ($company->company_details ? $company->company_details->membership_number : '' ) ?: old('membership_number') }}" placeholder="Membership Number .">

                                            @error('membership_number')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> BKMEA Reg No. </label>

                                        <div class="col-xs-12 col-sm-7 @error('bkmea_reg_no') has-error @enderror">
                                            <input type="text" class="form-control" name="bkmea_reg_no"
                                                   value="{{ ($company->company_details ? $company->company_details->bkmea_reg_no : '' ) ?: old('bkmea_reg_no') }}" placeholder="BKMEA Reg No .">

                                            @error('bkmea_reg_no')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Import Reg Certi . </label>

                                        <div class="col-xs-12 col-sm-7 @error('import_reg_certi') has-error @enderror">
                                            <input type="text" class="form-control" name="import_reg_certi"
                                                   value="{{ ($company->company_details ? $company->company_details->import_reg_certi : '' ) ?: old('import_reg_certi') }}" placeholder="Import Reg Certi .">

                                            @error('import_reg_certi')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Export Reg Certi. </label>

                                        <div class="col-xs-12 col-sm-7 @error('export_reg_certi') has-error @enderror">
                                            <input type="text" class="form-control" name="export_reg_certi"
                                                   value="{{ ($company->company_details ? $company->company_details->export_reg_certi : '' ) ?: old('export_reg_certi') }}" placeholder="Export Reg Certi .">

                                            @error('export_reg_certi')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> EPB Reg No. </label>

                                        <div class="col-xs-12 col-sm-7 @error('epb_reg_no') has-error @enderror">
                                            <input type="text" class="form-control" name="epb_reg_no"
                                                   value="{{ ($company->company_details ? $company->company_details->epb_reg_no : '' ) ?: old('epb_reg_no') }}" placeholder="EPB Reg No .">

                                            @error('epb_reg_no')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Latitude </label>

                                        <div class="col-xs-12 col-sm-7">
                                            <input type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control input-sm" name="latitude"
                                                   value="{{ old('latitude', $company->latitude) }}" placeholder="Latitude Value">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Longitude </label>

                                        <div class="col-xs-12 col-sm-7">
                                            <input type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control input-sm" name="longitude"
                                                   value="{{ old('longitude', $company->longitude) }}" placeholder="Longitude Value">
                                        </div>
                                    </div>

                                    <!-- Start footer -->
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="footer"> Footer </label>

                                        <div class="col-sm-5">
                                            <input type="file" name="footer" id="footer" />
                                        </div>
                                        <div class="col-sm-2">
                                            @if($company->company_details && $company->company_details->footer)
                                                <img src="{{ asset('uploads/company/extra/'.$company->company_details->footer) }}" style="width: 100px" alt="{{ $company->name }}">
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Start organogram -->
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="organogram"> Organogram </label>
                                        <div class="col-xs-12 col-sm-7">
                                            <input type="file" name="organogram" id="organogram" />
                                        </div>
                                        <div class="col-sm-2">
                                            @if(optional($company->company_details)->footer)
                                                <img src="{{ asset('uploads/company/extra/' . optional($company->company_details)->organogram) }}" style="width: 100px" alt="{{ $company->name }}">
                                            @endif
                                        </div>
                                    </div>
                                    <!-- End footer -->

                                </div>


                            </div>
                            <br>
                            <div class="row" style="margin-bottom: 30px;">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <h4 class="text-center">Company Bank Account</h4>
                                    <hr>
                                    <table id="myTable" class="table table-bordered order-list">
                                        <thead>
                                        <tr>
                                            <td>Account Name</td>
                                            <td>Account Number</td>
                                            <td>Bank Name</td>
                                            <td>Branch</td>
                                            <td>Swift Code</td>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @if (old('account_name'))
                                            @foreach(old('account_name') as $key => $value)
                                                <tr>
                                                    <td>
                                                        <input type="text" value="{{ old('account_name')[$key] }}" name="account_name[]" class="form-control" />
                                                    </td>
                                                    <td>
                                                        <input type="number" value="{{ old('account_number')[$key] }}" name="account_number[]"  class="form-control"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ old('bank_name')[$key] }}" name="bank_name[]"  class="form-control"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ old('branch')[$key] }}" name="branch[]"  class="form-control"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ old('swift_code')[$key] }}" name="swift_code[]"  class="form-control"/>
                                                    </td>
                                                    <td><button type="button" class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button></td>
                                                </tr>
                                            @endforeach

                                        @else

                                            @foreach($company->company_bank_account as $company_bank_account)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="companyBankId[]" value="{{ $company_bank_account->id }}">
                                                    <input type="text" value="{{ $company_bank_account->account_name }}" name="account_name[]" class="form-control" />
                                                </td>
                                                <td>
                                                    <input type="number" value="{{ $company_bank_account->account_number }}" name="account_number[]"  class="form-control"/>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $company_bank_account->bank_name }}" name="bank_name[]"  class="form-control"/>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $company_bank_account->branch }}" name="branch[]"  class="form-control"/>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $company_bank_account->swift_code }}" name="swift_code[]"  class="form-control"/>
                                                </td>
                                                <td><button type="button" class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button></td>
                                            </tr>

                                            @endforeach
                                        @endif

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5" style="text-align: right;">
                                                <button type="button" class="btn btn-sm btn-primary" id="addrow" >
                                                    + Add New
                                                </button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>


                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-pencil-square-o icon-on-right bigger-110"></i>
                                    Update
                                </button>
                                @if (hasPermission("company.infos.view", $slugs))
                                    <a href="{{ route('company.index') }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-backward"></i> Back List
                                    </a>
                                @endif
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>




@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>





    <!--Drag and drop-->
    <script type="text/javascript">
        jQuery(function($) {
            $('#id-input-file-3').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'//large | fit

            }).on('change', function(){
                //console.log($(this).data('ace_input_files'));
                //console.log($(this).data('ace_input_method'));
            });
            // Start header, footer
            $('#header').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'//large | fit
            });
            $('#footer').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'//large | fit
            });
            $('#organogram').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'//large | fit
            });
            // End header, footer


        });

    </script>
    {{--    select Box Search--}}
    <script type="text/javascript">

        jQuery(function($){

            if(!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect:true});
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({'width': $this.parent().width()});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if(event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                });


                $('#chosen-multiple-style .btn').on('click', function(e){
                    var target = $(this).find('input[type=radio]');
                    var which = parseInt(target.val());
                    if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
                    else $('#form-field-select-4').removeClass('tag-input-style');
                });
            }

        })
    </script>


    <script type="text/javascript">

        $(document).ready(function () {
            var i = 0;

            $("#addrow").on("click", function () {
                var newRow = $("<tr>");
                var cols = "";

                cols += '<td><input type="text" class="form-control" name="new_account_name[]"/></td>';
                cols += '<td><input type="number" class="form-control" name="new_account_number[]"/></td>';
                cols += '<td><input type="text" class="form-control" name="new_bank_name[]"/></td>';
                cols += '<td><input type="text" class="form-control" name="new_branch[]"/></td>';
                cols += '<td><input type="text" class="form-control" name="new_swift_code[]"/></td>';

                cols += '<td><button type="button" class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button></td>';
                newRow.append(cols);
                $("table.order-list").append(newRow);
                i++;
            });



            $("table.order-list").on("click", ".ibtnDel", function (event) {
                $(this).closest("tr").remove();
                i -= 1
            });


        });


    </script>



@stop
