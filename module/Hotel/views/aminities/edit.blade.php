@extends('layouts.master')
@section('title',' Edit Aminities')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Edit amenity" description="Name, icon (38 x 40 px) and status.">
    <x-slot name="actions"><a class="mm-button mm-button-secondary" href="{{ route('aminities.index') }}"><i class="fa fa-list-alt"></i> Amenities list</a></x-slot>
    @include('partials._alert_message')

    <x-mm.panel>
        <form class="form-horizontal" id="companyForm" action="{{ route('aminities.update', $aminities->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- W3.4: replaced Bootstrap-3 form-group markup with
                 <x-mm.field> and <x-mm.select>. The file uploader
                 keeps its dropzone behavior (category_photos class,
                 ace_file_input plugin) — endpoint unchanged. --}}
            <div class="tw-grid tw-gap-4 sm:tw-grid-cols-2">
                <x-mm.field label="Amenity name" id="aminities-name" name="name" value="{{ $aminities->name }}" placeholder="Amenity name" :error="$errors->first('name')" />
                <div>
                    <label for="aminities-icon" class="tw-block tw-mb-2 tw-text-sm tw-font-semibold tw-text-ink">Amenity icon</label>
                    <input id="aminities-icon" name="aminiti_icon" type="file" class="category_photos mm-input" multiple>
                    <p class="tw-mt-2 tw-text-sm tw-text-muted">Use flaticon icon/png size (38 * 40) px.</p>
                    @error('aminiti_icon') <p class="tw-mt-2 tw-text-sm tw-text-danger">{{ $message }}</p> @enderror
                </div>
                <x-mm.select name="status" label="Status"
                    :options="['1' => 'Active', '0' => 'In Active']"
                    selected="{{ $aminities->status }}"
                    placeholder="Select option"
                    :error="$errors->first('status')" />
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

@section('js')
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<script type="text/javascript">
    jQuery(function($) {
        $('.category_photos').ace_file_input({
            style: 'well',
            btn_choose: 'Upload Amenity icon',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small'
        }).on('change', function() {
        });
    });
</script>
@stop
