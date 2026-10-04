@extends('layouts.master')
@section('title', 'Booking Purpose')

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
                <x-mm.panel class="tw-p-4">
                    <x-mm.data-table :columns="[
                        ['label' => 'SL', 'width' => '5%', 'align' => 'center'],
                        ['label' => 'Name', 'width' => '50%', 'align' => 'center'],
                        ['label' => 'Status', 'width' => '20%', 'align' => 'center'],
                        ['label' => 'Action', 'width' => '10%', 'align' => 'center'],
                    ]" label="Booking setup records" table-class="table table-striped table-bordered table-hover">
                        @forelse ($booking_purpose->where('rule', 1)->get() as $item)
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
                        @empty
                            <x-no-table-record />
                        @endforelse
                    </x-mm.data-table>
                </x-mm.panel>
            @endif

            <!-- For Platform -->
            @if (request('type') == 'platform')
                <x-mm.panel class="tw-p-4">
                    <x-mm.data-table :columns="[
                        ['label' => 'SL', 'width' => '5%', 'align' => 'center'],
                        ['label' => 'Name', 'width' => '50%', 'align' => 'center'],
                        ['label' => 'Status', 'width' => '20%', 'align' => 'center'],
                        ['label' => 'Action', 'width' => '10%', 'align' => 'center'],
                    ]" label="Booking setup records" table-class="table table-striped table-bordered table-hover">
                        @forelse ($booking_purpose->where('rule', 2)->get() as $item)
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
                        @empty
                            <x-no-table-record />
                        @endforelse
                    </x-mm.data-table>
                </x-mm.panel>
            @endif

        </div>
    </div>
    </x-mm.page>
@endsection

@section('js')
@endsection
