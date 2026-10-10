@extends('layouts.master')
@section('title', ' Night Audit')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-report" title="Night Audit">
        <x-mm.panel>
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
                                    <x-export-button :pdf=1 :excel=1 :print=1 />
                                @endif
                            </div>
                        </div>
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
