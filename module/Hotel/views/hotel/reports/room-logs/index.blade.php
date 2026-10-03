@extends('layouts.master')
@section('title', 'Room Logs')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Room logs" description="Room status changes and who made them.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">

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

            <div class="btn-group">
                <button class="mm-button" type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>

        </form>
    </x-mm.panel>
    @if (collect(request()->all())->count() > 0)
        <x-mm.panel>
            <x-mm.table-scroll label="Room logs">
                @include('hotel/reports/room-logs/export/excel')
            </x-mm.table-scroll>

            <x-paginate :data="$room_logs" />

            <x-export-button :pdf=1 :excel=1 />
        </x-mm.panel>
    @endif
</x-mm.page>
@endsection

@section('js')
@endsection
