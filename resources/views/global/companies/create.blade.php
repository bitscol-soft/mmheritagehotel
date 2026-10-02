
@extends('layouts.master')
@section('title','Add New Company')
@section('page-header')
    <i class="fa fa-gears"></i> Add New Company
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

                        <form class="form-horizontal" id="companyForm" action="{{ route('company.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-sm-6">

                                    <h4 class="text-center">Company Info</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Company Name </label>

                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="name"
                                                   value="{{ old('name') }}" placeholder="Company Name">

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
                                            <input type="text" class="form-control input-sm" name="business_type" value="{{ old('business_type') }}" placeholder="Business Type">
                                        </div>

{{--                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">--}}
{{--                                            <select name="business_type_id" class="chosen-select input-sm" data-placeholder="Choose a Business Type...">--}}
{{--                                                <option value=""></option>--}}
{{--                                                @foreach($business_types as $business_type)--}}
{{--                                                    <option {{ old('business_type_id') == $business_type->id ? 'selected' : '' }} value="{{ $business_type->id }}">{{ $business_type->name }}</option>--}}
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
                                            <input type="number" class="form-control input-sm" id="code" name="code"
                                                   value="{{ old('code') }}" placeholder="Company Code">

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
                                            <input type="text" class="form-control input-sm" name="short_name"
                                                   value="{{ old('short_name') }}" placeholder="Company Short Name">

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
                                        <textarea class="form-control input-sm" name="head_office"
                                                  placeholder="Head Office">{{ old('head_office') }}</textarea>

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
                                        <textarea class="form-control input-sm" name="factory"
                                                  placeholder="Factory">{{ old('factory') }}</textarea>

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
                                            <input type="text" class="form-control input-sm" name="contact_name"
                                                   value="{{ old('contact_name') }}" placeholder="Company Contact Name">

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
                                            <input type="text" class="form-control input-sm" name="position"
                                                   value="{{ old('position') }}" placeholder="Position">

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
                                            <input type="number" class="form-control input-sm" name="phone_number"
                                                   value="{{ old('phone_number') }}" placeholder="Phone Number">

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
                                            <input type="text" class="form-control input-sm" name="fax"
                                                   value="{{ old('fax') }}" placeholder="Fax">

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
                                            <input type="email" class="form-control input-sm" name="email"
                                                   value="{{ old('email') }}" placeholder="Email">

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

                                            <select name="day_off" class="chosen-select input-sm"  data-placeholder="Choose a Day...">
                                                <option value=""></option>

                                                @for($i = 4; $i <= 10; $i++)
                                                    <option value="{{ \Carbon\Carbon::now()->subDays($i)->format('l') }}">
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
                                            <input type="text" class="form-control input-sm" name="country"
                                                   value="{{ old('country') }}" placeholder="Country">

                                            @error('country')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Latitude </label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control input-sm" name="latitude"
                                                   value="{{ old('latitude') }}" placeholder="Latitude Value">
                                        </div>
                                    </div>

                                    <!-- Start header [nabil] -->
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="header"> Header </label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="file" name="header" id="header" />
                                        </div>
                                    </div>
                                    <!-- End header -->

                                </div>

                                <div class="col-sm-6">
                                    <h4 class="text-center">Company Details Info</h4>
                                    <hr>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Company Logo </label>

                                        <div class="col-xs-12 col-sm-7">
                                            <input type="file" name="logo" id="id-input-file-3" />
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Top Text </label>

                                        <div class="col-xs-12 col-sm-7 @error('top_text') has-error @enderror">
                                        <textarea class="form-control input-sm" name="top_text"
                                                  rows="4" placeholder="Top text">{{ old('top_text') }}</textarea>

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
                                            <input type="text" class="form-control input-sm" name="vat_no"
                                                   value="{{ old('vat_no') }}" placeholder="BIN No.">

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
                                            <input type="text" class="form-control input-sm" name="facsimile_number"
                                                   value="{{ old('facsimile_number') }}" placeholder="Facsimile Number .">

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
                                            <input type="text" class="form-control input-sm" name="bonded_license"
                                                   value="{{ old('bonded_license') }}" placeholder="Bonded/Trade License .">

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
                                            <input type="text" class="form-control input-sm" name="membership_number"
                                                   value="{{ old('membership_number') }}" placeholder="Membership Number .">

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
                                            <input type="text" class="form-control input-sm" name="bkmea_reg_no"
                                                   value="{{ old('bkmea_reg_no') }}" placeholder="BKMEA Reg No .">

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
                                            <input type="text" class="form-control input-sm" name="import_reg_certi"
                                                   value="{{ old('import_reg_certi') }}" placeholder="Import Reg Certi .">

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
                                            <input type="text" class="form-control input-sm" name="export_reg_certi"
                                                   value="{{ old('export_reg_certi') }}" placeholder="Export Reg Certi .">

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
                                            <input type="text" class="form-control input-sm" name="epb_reg_no"
                                                   value="{{ old('epb_reg_no') }}" placeholder="EPB Reg No .">

                                            @error('epb_reg_no')
                                            <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="form-field-1-1"> Longitude </label>

                                        <div class="col-xs-12 col-sm-7">
                                            <input type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57" class="form-control input-sm" name="longitude"
                                                   value="{{ old('longitude') }}" placeholder="Longitude Value">
                                        </div>
                                    </div>

                                    <!-- Start footer [nabil] -->
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="footer"> Footer </label>
                                        <div class="col-xs-12 col-sm-7">
                                            <input type="file" name="footer" id="footer" />
                                        </div>
                                    </div>

                                    <!-- Start organogram -->
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="organogram"> Organogram </label>
                                        <div class="col-xs-12 col-sm-7">
                                            <input type="file" name="organogram" id="organogram" />
                                        </div>
                                    </div>

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
                                                        <input type="text" value="{{ old('account_name')[$key] }}" name="account_name[]" class="form-control input-sm" />
                                                    </td>
                                                    <td>
                                                        <input type="number" value="{{ old('account_number')[$key] }}" name="account_number[]"  class="form-control input-sm"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ old('bank_name')[$key] }}" name="bank_name[]"  class="form-control input-sm"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ old('branch')[$key] }}" name="branch[]"  class="form-control input-sm"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ old('swift_code')[$key] }}" name="swift_code[]"  class="form-control input-sm"/>
                                                    </td>
                                                    <td><button type="button" class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button></td>
                                                </tr>
                                            @endforeach

                                            @else
                                            <tr>
                                                <td>
                                                    <input type="text" name="account_name[]" class="form-control input-sm" />
                                                </td>
                                                <td>
                                                    <input type="number" name="account_number[]"  class="form-control input-sm"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="bank_name[]"  class="form-control input-sm"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="branch[]"  class="form-control input-sm"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="swift_code[]"  class="form-control input-sm"/>
                                                </td>
                                                <td><a class="btn btn-sm btn-danger" disabled="disabled" ><i class="fa fa-trash-o"></i></a></td>
                                            </tr>
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
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
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

                cols += '<td><input type="text" class="form-control input-sm" name="account_name[]' + i + '"/></td>';
                cols += '<td><input type="number" class="form-control input-sm" name="account_number[]' + i + '"/></td>';
                cols += '<td><input type="text" class="form-control input-sm" name="bank_name[]' + i + '"/></td>';
                cols += '<td><input type="text" class="form-control input-sm" name="branch[]' + i + '"/></td>';
                cols += '<td><input type="text" class="form-control input-sm" name="swift_code[]' + i + '"/></td>';

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
