<x-mm.panel class="mm-hotel-setup-fragment">
    <h2 class="mm-setup-title"><i class="fa fa-plus-circle"></i> Add New</h2>
    <form class="form-horizontal createCurrencyConversionForm" action="{{ route('currency-conversions.store') }}" method="post"
        enctype="multipart/form-data">
        @csrf
                
        <div class="row">
            <div class="col-sm-12 mt-2">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="form-field-1-1"> Currency <span style="color: deeppink">*</span> </label>

                    <div class="col-xs-12 col-sm-8 @error('currency_id') has-error @enderror">
                        {!! Form::select('currency_id', $currencies, null, ['class' => 'form-control chosen-select', 'id' => 'currencyId', 'placeholder' => 'Select Currency', 'required']) !!}

                        @error('currency_id')
                            <span class="text-danger"> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="form-field-1-1"> Rate <span style="color: deeppink">*</span></label>
                    <div class="col-xs-12 col-sm-8 @error('rate') has-error @enderror">
                        <input type="text" class="form-control input-sm" name="rate" id="rate"
                            value="{{ old('rate') }}" placeholder="Enter Rate" required>
                        @error('rate')
                            <span class="text-danger"> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="form-field-1-1"> Date <span style="color: deeppink">*</span></label>
                    <div class="col-xs-12 col-sm-8 @error('effected_date') has-error @enderror">
                        <input type="text" class="form-control input-sm date-picker" name="effected_date" id="effectedDate"
                            value="{{ old('effected_date') }}" placeholder="Enter Effected Date">

                        @error('effected_date')
                            <span class="text-danger"> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>


        </div>


        <div class="form-actions center" style="text-align: right !important;">
            <button type="button" class="mm-button" onclick="submitRoomStoreForm(this)">
                <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                Save
            </button>
        </div>
    </form>
</x-mm.panel>

