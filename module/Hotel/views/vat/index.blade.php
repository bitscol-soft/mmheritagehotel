@extends('layouts.master')
@section('title','Vat & Services')

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="VAT and services" description="VAT percentages, service charges and the room rate used on invoices.">
    <x-alert-message />

    <x-mm.panel class="mm-setup-narrow">
        <form class="form-horizontal" id="companyForm" action="{{ route('vat.update',$vat->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- W3.4: replaced Bootstrap-3 form-group markup with
                 <x-mm.field>. The field name, value, type="number"
                 with input-group, placeholder, and validation error
                 hooks are preserved byte-identically. --}}
            <div class="tw-grid tw-gap-4 sm:tw-grid-cols-2">
                <x-mm.field label="Hotel VAT (%)" id="hotel-vat" name="hotel_vat" type="number" value="{{ $vat->hotel_vat }}" placeholder="Enter VAT number (%)" help="Percent (%)" />
                <x-mm.field label="Restaurant VAT (%)" id="resturent-vat" name="resturent_vat" type="number" value="{{ $vat->resturent_vat }}" placeholder="Enter VAT number (%)" help="Percent (%)" :error="$errors->first('resturent_vat')" />
                <x-mm.field label="Bar VAT (%)" id="bar-vat" name="bar_vat" type="number" value="{{ $vat->bar_vat }}" placeholder="Enter VAT number (%)" help="Percent (%)" />
                <x-mm.field label="VAT number" id="vat-number" name="vat_number" value="{{ $vat->vat_number ?? '' }}" placeholder="Enter VAT number" />
                <x-mm.field label="Room rate" id="room-rate" name="room_rate" value="{{ $vat->room_rate ?? '' }}" placeholder="Enter room rate" help="Amount 126.50 for 10 (%)" />
                <x-mm.field label="Room service (%)" id="room-service" name="room_service" value="{{ intval($vat->room_service_charge) ?? '' }}" placeholder="Enter room service" help="Percent (%)" />
                <x-mm.field label="Rst service (%)" id="rst-service-charge" name="rst_service_charge" value="{{ intval($vat->rst_service_charge) ?? '' }}" placeholder="Restaurant service charge" help="Percent (%)" />

                {{-- Radio group: kept raw (x-mm.field doesn't support
                     radios). The field id/name pattern is byte-identical. --}}
                <div>
                    <span class="tw-block tw-mb-2 tw-text-sm tw-font-semibold tw-text-ink">Included VAT calc</span>
                    <div class="tw-rounded-lg tw-bg-soft tw-p-3 tw-flex tw-gap-4">
                        <label class="tw-flex tw-items-center tw-gap-2">
                            <input type="radio" name="key[use_vat_included]"{{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                            Yes
                        </label>
                        <label class="tw-flex tw-items-center tw-gap-2">
                            <input type="radio" name="key[use_vat_included]"{{ $systemSetting->value == '0' ? 'checked' : '' }} value="0">
                            No
                        </label>
                    </div>
                </div>
            </div>

            <div class="tw-mt-6 tw-flex tw-justify-end tw-gap-2">
                <button type="submit" class="mm-button">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    Save
                </button>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
@endsection
