@extends('layouts.master')
@section('title', 'Room Logs')

@section('page-header')
    <i class="fa fa-info-circle"></i> Room Logs
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

        table thead th {
            background-color: #4d8cb3;
            color: #fff;
        }
    </style>
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

                        @include('partials._alert_message')

                        <!-- Search -->
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <form>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>

                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Room</span>
                                                        <select name="room_id" class="form-control chosen-select">
                                                            <option value=""></option>
                                                            @foreach ($rooms as $id => $room)
                                                                <option value="{{ $room->id }}">{{ $room->name }} ->
                                                                    {{ $room->room_number }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Date</span>
                                                        <input type="text" name="from_date"
                                                            value="{{ request('from_date') }}"
                                                            class="form-control date-picker" autocomplete="off">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                        <input type="text" name="to_date"
                                                            value="{{ request('to_date') }}"
                                                            class="form-control date-picker" autocomplete="off">
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
                        @if (collect(request()->all())->count() > 0)
                            <div class="row">
                                <div class="col-sm-12 px-4">

                                    @include('hotel/reports/room-logs/export/excel')

                                    <x-paginate :data="$room_logs" />
                                </div>
                                <x-export-button :pdf=1 :excel=1 />
                            </div>
                        @endif


                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')
@endsection
