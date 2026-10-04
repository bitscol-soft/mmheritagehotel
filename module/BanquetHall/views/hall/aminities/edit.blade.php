@extends('layouts.master')

@section('title', ' Edit Aminities')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-banquet mm-hotel-setup" title="Edit hall amenity" description="Update the amenity name, icon and status.">
        @include('partials._alert_message')

        <x-mm.panel class="tw-p-5">
            <form class="form-horizontal" id="companyForm"
                action="{{ route('banquet.aminities.update', $aminities->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-sm-12">
                        <x-mm.field label="Aminities Name" id="hall-amenity-name" name="name" value="{{ $aminities->name }}" placeholder="Aminities Name" />
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1">Aminities
                                icon</label>

                            <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                <label class="text-danger">use flaticon icon/png size (38 * 40)px</label>
                                {{-- W4.2: kept raw. The ace_file_input plugin binds to the
                                     `category_photos` class on this <input>. --}}
                                <input type="file" name="aminiti_icon" class="category_photos" multiple>

                                @error('name')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1"> Status</label>

                            <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                <select name="status">
                                    <option value="">Select Option</option>
                                    <option value="1" {{ $aminities->status == 1 ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ $aminities->status == 0 ? 'selected' : '' }}>
                                        In Active</option>
                                </select>
                                @error('status')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions center" style="text-align: right !important;">
                    <button type="submit" class="mm-button">
                        <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                        Save
                    </button>
                </div>
            </form>
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }
    </script>

    <!--Drag and drop-->
    <script type="text/javascript">
        jQuery(function($) {
            $('.category_photos').ace_file_input({
                style: 'well',
                btn_choose: 'Upload Aminities Icon',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {});
        });
    </script>
@stop
