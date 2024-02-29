@extends('layouts.master')
@section('title', ' Night Audit')

@section('page-header')
    <i class="fa fa-gear"></i> Total <span class="badge badge-info">{{ count($nightaudits ?? []) }}</span>
@stop

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main">



                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <form>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Date</span>
                                                        <input type="text" name="from_date"
                                                            value="{{ request('from_date') }}" autocomplete="off"
                                                            class="form-control date-picker">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                        <input type="text" name="to_date"
                                                            value="{{ request('to_date') }}" autocomplete="off"
                                                            class="form-control date-picker">
                                                    </div>

                                                </td>
                                                <td style="width: 15%">
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-success" type="submit">
                                                            <i class="fa fa-search"></i> Search
                                                        </button>
                                                        <a href="{{ request()->url() }}" class="btn btn-sm">
                                                            <i class="fa fa-refresh"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </form>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-xs-12">

                                @if (collect(request()->all())->count() > 0)
                                    @include('hotel/reports/night-closing/export/excel')

                                    <x-paginate :data="$nightaudits" />
                                    <x-export-button :pdf=1 :excel=1 />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
