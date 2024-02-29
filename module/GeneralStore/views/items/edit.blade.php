@extends('layouts.master')
@section('title','Edit Item')
@section('page-header')
    <i class="fa fa-gear"></i> Edit Item
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>

        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop


@section('content')

    <div class="row">

        <div class="col-sm-8 col-sm-offset-2">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if(hasPermission('items.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('items.index') }}"><i class="ace-icon fa fa-list-alt"></i> Item List</a>
                        </span>
                    @endif
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('items.update', $item->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                            @include('partials._alert_message')


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Company Name </label>
                                <div class="col-xs-12 col-sm-8 @error('item_unit') has-error @enderror">
                                    <select name="company_id" class="form-control chosen-select" onchange="load_units(this)">
                                        @foreach($companies as $id => $company)
                                            <option value="{{ $id }}" {{ $item->company_id == $id ? 'selected':'' }}>{{ $company }}</option>
                                        @endforeach
                                    </select>

                                    @error('company_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Item Description </label>
                                <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') ?: $item->name }}" placeholder="Item Name">

                                    @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Item Unit </label>
                                <div class="col-xs-12 col-sm-8 @error('item_unit') has-error @enderror">
                                    <select name="item_unit_id" class="form-control chosen-select" id="item_unit_id">
                                        @foreach($item_units as $id => $name)
                                            <option value="{{ $id }}" {{ old('item_unit_id') == $id || $item->item_unit_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>

                                    @error('item_unit')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>




                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Opening Balance </label>

                                <div class="col-xs-12 col-sm-8 @error('opening_balance') has-error @enderror">
                                    <input type="number" step="0.01" name="opening_balance" class="form-control opening_balance"  {{ $item->purchase_detail_count == 0 && $item->goods_requisition_count == 0 ? ''  : 'readonly="readlony"' }} value="{{ old('opening_balance') ?: $item->opening_balance }}" placeholder="Opening Balance">

                                    @error('opening_balance')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Assume Rate(Unit) </label>
                                <div class="col-xs-12 col-sm-8 @error('rate') has-error @enderror">
                                    <input type="number" step="0.01" class="form-control" {{ $item->purchase_detail_count == 0 && $item->goods_requisition_count == 0 ? ''  : 'readonly="readlony"' }} name="rate" value="{{ old('rate') ?: $item->rate }}" placeholder="Rate">

                                    @error('rate')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="pull-right" style="padding-right: 85px !important;">
                                    <button class="btn btn-success btn-sm"> <i class="fa fa-save"></i> Update</button>
                                    <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>
                                    @if(hasPermission('items.view', $slugs))
                                    <a href="{{ route('items.index') }}" class="btn btn-info btn-sm"> <i class="fa fa-list"></i> List</a>
                                    @endif
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>




@endsection

@section('js')

<script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('assets/js/ace.min.js') }}"></script>
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>

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
        }

    })
</script>


<script type="text/javascript">

    
    // validation numeric input
    $(document).ready(function() {
        $(".opening_balance").bind("keypress", function (e) {
            var keyCode = e.which ? e.which : e.keyCode
                
            if (!(keyCode >= 48 && keyCode <= 57)) {
                return false;
            } 
            if (this.value.length == 0 && e.which == 48 ){
                return false;
            }
        });
    });


    function load_units(element){
        var id = $(element).val();

        $.ajax({
            url: '{{ url("ajax/items/get_units") }}',
            type: 'GET',
            data: 'id='+id,
            success:function(res){
                    $('#item_unit_id').empty();
                $.each(res['item_units'], function(id, name){
                    $('#item_unit_id').val('').trigger('chosen:updated');

                    $('#item_unit_id').append('<option value="'+id+'">'+name+'</option>')
                });
            }
        });
    }

</script>
@stop
