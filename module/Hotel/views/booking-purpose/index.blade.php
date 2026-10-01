@extends('layouts.master')
@section('title', 'Booking Purpose')
@section('page-header') <i class="fa fa-info-circle"></i> Booking Purpose @stop

@section('css')
    {{-- @include('guests.include.css') --}}
@endsection

@section('content')
    <x-mm.styles />
    <x-mm.page :title="request('type') == 'purpose' ? 'Booking purposes' : 'Booking platforms'" description="Manage labels used to classify bookings." class="mm-booking-setup">
    <x-slot name="actions"><a class="mm-button mm-button-primary" href="{{ route('booking-purpose.create') }}?type={{ request('type') == 'purpose' ? 'purpose' : 'platform' }}">Add new</a></x-slot>
    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            @include('booking-purpose/include/filter')

            <!-- For Purpose -->
            @if (request('type') == 'purpose')
                <div>
                    <x-mm.table-scroll label="Booking setup records"><table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col" width="5%" >SL</th>
                                <th scope="col" width="50%" class="center">Name</th>
                                <th scope="col" width="20%" class="center">Status</th>
                                <th scope="col" width="10%" class="center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($booking_purpose->where('rule', 1)->get() as $item)
                                <tr>
                                    <td class="center">{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->status }}</td>

                                    <td class="center">
                                        <div class="btn-group">
                                            <a aria-label="Edit booking label" class="btn btn-sm btn-success"
                                                href="{{ route('booking-purpose.edit', $item->id) }}?type={{ request('type') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button type="button"
                                                onclick="delete_item(`{{ route('booking-purpose.destroy', $item->id) }}`)"
                                                class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table></x-mm.table-scroll>
                </div>
            @endif


            <!-- For Platform -->
            @if (request('type') == 'platform')
                <div>
                    <x-mm.table-scroll label="Booking setup records"><table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col" width="5%" >SL</th>
                                <th scope="col" width="50%" class="center">Name</th>
                                <th scope="col" width="20%" class="center">Status</th>
                                <th scope="col" width="10%" class="center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($booking_purpose->where('rule', 2)->get() as $item)
                                <tr>
                                    <td class="center">{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->status }}</td>

                                    <td class="center">
                                        <div class="btn-group">
                                            <a aria-label="Edit booking label" class="btn btn-sm btn-success"
                                                href="{{ route('booking-purpose.edit', $item->id) }}?type={{ request('type') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button type="button"
                                                onclick="delete_item(`{{ route('booking-purpose.destroy', $item->id) }}`)"
                                                class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table></x-mm.table-scroll>
                </div>
            @endif
            {{-- @include('partials._paginate', ['data' => $booking_purpose]) --}}

        </div>
    </div>
    </x-mm.page>
@endsection

@section('js')
@endsection
