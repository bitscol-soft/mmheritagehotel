@extends('layouts.master')
@section('title','Edit Registration Terms')

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Edit registration terms" description="Update the terms text.">
    <x-slot name="actions"><a class="mm-button mm-button-secondary" href="{{ route('guest-registration-terms.index') }}"><i class="fa fa-list-alt"></i> Registration Terms List</a></x-slot>
    @include('partials._alert_message')

    <x-mm.panel>
        <form class="form-horizontal" id="companyForm" action="{{ route('guest-registration-terms.update',$bookingNote->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-sm-12">

                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Title<sup class="text-danger">*</sup></label>

                        <div class="col-xs-12 col-sm-8">
                            <textarea name="title" class="form-control" cols="30" rows="10" required>{{ $bookingNote->title }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-actions center" style="text-align: right !important;">
                <button type="submit" class="mm-button">
                    <i class="fa fa-save"></i>
                    Save
                </button>

                <a href="{{ route('guest-registration-terms.index') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-backward"></i> Back List
                </a>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
  <!--Drag and drop-->
  <script type="text/javascript">
    jQuery(function($) {
        $('.id-input-file-3').ace_file_input({
            style: 'well',
            btn_choose: 'Upload NID / Passport Photo',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>
<script>
    $('.photo-remove').click(function(){
        $(this).closest('.nid_photo').remove();
        $(this).closest('.ace-file-input').show();

    });
</script>
@endsection
