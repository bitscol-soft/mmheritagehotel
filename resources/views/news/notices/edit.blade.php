@extends('layouts.master')

@section('title','Notice Edit')

@section('page-header')
    <i class="fa fa-list"></i> Notice Edit
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />

@endsection


@section('content')
    <form class="form-horizontal supplier-pi-form" action="{{ route('notices.update', $notice->id) }}" method="post" style="width: 98%; margin-left: 1%;">
        @csrf @method('PUT')
        <div style="margin-bottom: 20px !important; margin-top: 20px !important;">
            <!-- panel heading -->
            <div class="row panel-head" style="height: 30px; margin: 0 0 10px 0 !important;">
                <span style="line-height: 30px; font-size: 22px; color: #1B6AAA !important; text-decoration: underline" class="text-primary;">Notice Edit</span>
            </div>

            @include('partials._alert_message')

            <div class="row">

                <div class="col-sm-12">
                    <form class="form-horizontal" action="" method="get">
                        <table class="table table-bordered">
                            <tr>
                                <td>Company <strong class="text-danger">*</strong></td>
                                <td>Department</td>
                                <td>Publish At<strong class="text-danger">*</strong></td>
                                <td>Expire At<strong class="text-danger">*</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="company" class="chosen-select-220 required" required="required">
                                        <option value=""> - Select - </option>
                                        @foreach($companies as $id => $company)
                                            <option value="{{ $id }}" {{ old('company') == $id || $notice->company_id == $id ? 'selected' : ''  }}>{{ $company }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="department" class="chosen-select-220">
                                        <option value=""> - Select - </option>
                                        @foreach($departments as $id => $department)
                                            <option value="{{ $id }}" {{ old('department') == $id || $notice->department_id == $id? 'selected' : ''  }}>{{ $department }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td width="160px">
                                    <input type="text" name="publish_at" required class="form-control input-sm date-picker"  autocomplete="off" value="{{ old('publish_at') ?? fdate($notice->publish_at, 'd-m-Y') }}">
                                </td>
                                <td width="160px">
                                    <input type="text" name="expire_at" required class="form-control input-sm date-picker"  autocomplete="off" value="{{ old('expire_at') ?? fdate($notice->expire_at, 'd-m-Y') }}">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>


                <div class="col-sm-12">
                    <label for="">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') ?? $notice->title }}" required>
                </div>
                <div class="col-sm-12 mt-2">
                    <label for="">Description</label>
                    <input id="x" type="hidden" name="description" value="{{ old('description') ?? $notice->description }}" required>
                    <trix-editor input="x" style="height: 250px"></trix-editor>
                </div>


                <div class="col-sm-12 mt-1">
                    <button type="submit" class="btn btn-sm btn-info pull-right ml-1"><i class="fa fa-file-archive-o"></i> Update</button>
                    <a href="{{ route('notices.index') }}" class="btn btn-sm btn-success pull-right"><i class="fa fa-list"></i> List</a>
                </div>

            </div>
        </div>
    </form>
@endsection

@section('js')
    

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <!--  Select Box Search-->
    <script type="text/javascript" src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <!--  date Picker-->
    <script type="text/javascript" src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix-core.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.js"></script>

@endsection
