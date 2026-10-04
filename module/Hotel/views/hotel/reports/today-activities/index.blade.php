@extends('layouts.master')

@section('title', 'Today Report')

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Today report" description="Check-ins, check-outs and reservations for one day.">
    <x-alert-message />
    <x-mm.panel class="mm-report-filter hidden-print">
        <form action="" method="GET" class="mm-setup-filter mm-report-form">
            <div class="mm-report-field">
                <div class="input-group">
                    <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                    <input type="text" class="date-picker form-control text-center"
                        autocomplete="off" name="date" value="{{ request('date', date('Y-m-d')) }}"
                        placeholder="Date">
                </div>
            </div>
            <div class="mm-report-field">
                <div class="btn-group">
                    <button type="submit" class="mm-button">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                        <i class="fa fa-refresh"></i>
                    </a>
                </div>
            </div>

        </form>
    </x-mm.panel>
    @if (request('date'))
        <x-mm.panel>
            <x-mm.table-scroll label="Today report">
                @if ($booking_count == 0)
                    <div class="text-center">
                        <strong style="font-size: 18px" class="text-danger">
                            No data found.
                        </strong>
                    </div>
                @else

                    <input type="hidden" name="date" value="{{ $date }}">
                    <hr>

                    <table class="table" style="border: none">

                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none"
                                    style="padding: 0 7px 0 0 !important; width: 20%">Total Check In :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_check_in"
                                    value="{{ $total_check_in }}">
                            </th>
                            <th style="border: none"></th>
                            <th class="text-right border-none"
                                    style="padding: 0 7px 0 0 !important; width: 20%">Total Reservation
                                : </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_reservation"
                                    value="{{ $total_reservation }}">
                            </th>
                        </tr>
                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Total
                                Check Out : </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_check_out"
                                    value="{{ $total_check_out }}">
                            </th>

                            <th style="border: none"></th>
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Total
                                Cancelled : </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_cancelled"
                                    value="{{ $total_cancel }}">
                            </th>
                        </tr>
                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Total Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_room"
                                    value="{{ $total_room }}">
                            </th>

                            <th style="border: none"></th>
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Booked Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_booked_room"
                                    value="{{ $total_booked_room }}">
                            </th>
                        </tr>
                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Today Dirty Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_dirty_room"
                                    value="{{ $total_dirty_room }}">
                            </th>

                            <th style="border: none"></th>
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Today Maintainance Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_room_maintenance"
                                    value="{{ $total_maintenance_room }}">
                            </th>
                        </tr>
                    </table>

                    {{-- W3.5b: transaction list extracted to a partial. The summary stats table
                         above stays inline (it has a custom borderless layout with label/value
                         pairs that doesn't fit the data-table component). --}}
                    @include('hotel.reports.today-activities.transactions')

                @endif

            </x-mm.table-scroll>
        </x-mm.panel>
    @endif
</x-mm.page>
@endsection

