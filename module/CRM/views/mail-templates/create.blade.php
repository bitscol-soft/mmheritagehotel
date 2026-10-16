@extends('layouts.master')
@section('title','CRM Mail Template')

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-inv mm-rst-form" title="Mail Template" description="Template mailed by the scheduled CRM invoice command.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('crm.mail-templates.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{  route('crm.mail-templates.store')  }}" method="post">
            @csrf
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="subject"> <b>Subject <sup class="text-danger">*</sup></b> </label>

                        <div class="col-sm-9">
                            <input required id="subject" name="subject" type="text" class="form-control input-sm" placeholder="Mail subject" value="{{  old('subject')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="body"> <b>Body <sup class="text-danger">*</sup></b> </label>

                        <div class="col-sm-9">
                            <input required id="body" name="body" type="text" class="form-control input-sm" placeholder="Mail body" value="{{  old('body')  }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="status"> <b>Status <sup class="text-danger">*</sup></b> </label>

                        <div class="col-sm-9">
                            <select id="status" name="status" class="form-control input-sm">
                                <option value="1" {{  old('status', 1) ? 'selected' : ''  }}>Active</option>
                                <option value="0" {{  old('status', 1) ? '' : 'selected'  }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Save</button>
                            <a href="{{ route('crm.mail-templates.index') }}" class="btn btn-gray btn-sm"><i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>

@endsection
