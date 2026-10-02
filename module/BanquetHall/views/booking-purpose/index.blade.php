@extends('layouts.master')
@section('title', 'Booking Purpose')
@section('page-header') <i class="fa fa-info-circle"></i> Booking Purpose @stop

@section('css')
    {{-- @include('guests.include.css') --}}
@endsection

@section('content')
    <div class="page-header">
        <a class="btn btn-xs btn-info" href="{{ route('booking-purpose.create') }}?type={{ request('type') == 'purpose' ? 'purpose' : 'platform' }}" style="float: right; margin: 0 2px;"> <i
                class="fa fa-plus"></i> Add New </a>
        <h1>
            <i class="fa fa-info-circle green"></i> Booking @if (request('type') == 'purpose') Purpose @else Platform @endif
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            @include('booking-purpose/include/filter')

            <!-- For Purpose -->
            @if (request('type') == 'purpose')
                <div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%" >SL</th>
                                <th width="50%" class="center">Name</th>
                                <th width="20%" class="center">Status</th>
                                <th width="10%" class="center">Action</th>
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
                                            <a class="btn btn-sm btn-success"
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
                    </table>
                </div>
            @endif


            <!-- For Platform -->
            @if (request('type') == 'platform')
                <div>
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%" >SL</th>
                                <th width="50%" class="center">Name</th>
                                <th width="20%" class="center">Status</th>
                                <th width="10%" class="center">Action</th>
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
                                            <a class="btn btn-sm btn-success"
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
                    </table>
                </div>
            @endif
            {{-- @include('partials._paginate', ['data' => $booking_purpose]) --}}

        </div>
    </div>
@endsection

@section('js')
@endsection
