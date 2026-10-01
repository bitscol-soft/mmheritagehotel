@extends('layouts.master')
@section('title', 'Guest List')
@section('page-header') <i class="fa fa-info-circle"></i> Guest List @stop

@section('css')
    @include('guests.include.css')
@endsection


@section('content')
    <div class="page-header">


        <a class="btn btn-xs btn-info" href="{{ route('guests.create', ['type' => 'upload']) }}" style="float: right; margin: 0 2px;">
            <i class="fa fa-upload"></i> Upload Guests
        </a>
        <a class="btn btn-xs btn-info" href="{{ route('guests.create') }}" style="float: right; margin: 0 2px;"> <i
                class="fa fa-plus"></i> Add New Guests </a>
        <h1>
            <i class="fa fa-info-circle green"></i> Guest User List
            <span class="badge badge-info">Total: {{ $guests->total() }}</span>
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            @include('guests/include/filter')

            <form id="submitSendSmsForm" action="{{ route('guests.send-sms') }}" method="GET">
                @csrf

                <input type="hidden" id="isAllSelected" name="isAllSelected" value="0">
                <input type="hidden" name="isFromGuestList" value="1">

                <div class="form-actions center" style="text-align: right !important; margin: 0; padding: 8px 8px; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                    <a onclick="selectEveryone()" id="selectEveryone" class="btn btn-sm btn-info send-sms-btn" style="transition: 300ms; background-color: #4F99C6 !important; color: white !important; border-color: #4F99C6 !important;">
                        &#10003; Select All
                    </a>
                    <button type="submit" class="btn btn-sm btn-success send-sms-btn" style="transition: 300ms; background-color: #87B87F !important; color: white !important;">
                        <i class="fa fa-paper-plane"></i> Send SMS
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <label class="inline">
                                        <input type="checkbox" id="selectAll" class="ace">
                                        <span class="lbl"></span>
                                    </label>
                                </th>
                                <th>SL</th>
                                <th class="center">Name</th>
                                <th class="center">Phone</th>
                                <th class="center">Gender</th>
                                <th class="center">Email</th>
                                <th class="center">NID / Passport</th>
                                <th class="center">Country</th>
                                <th class="center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($guests as $item)
                                <tr>
                                    <td class="text-center">
                                        <label class="inline">
                                            <input type="checkbox" name="guest_id[]" value="{{ $item->id }}" class="ace">
                                            <span class="lbl">&nbsp;</span>
                                        </label>
                                    </td>
                                    <td class="center">{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone_no }}</td>
                                    <td>
                                        @if ($item->gender == 1)
                                            Male
                                        @elseif($item->gender == 2)
                                            Female
                                        @else
                                            Others
                                        @endif
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->nid_no }}</td>
                                    <td>{{ optional($item->country)->name }}</td>
                                    <td class="center">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-success" href="{{ route('guests.edit', $item->id) }}" title="Edit guest">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <a class="btn btn-sm btn-info" href="{{ route('guests.invoice', $item->id) }}" target="_blank" title="Guest invoice">
                                                <i class="fa fa-print"></i>
                                            </a>

                                            <button type="button"
                                                onclick="delete_item(`{{ route('guests.destroy', $item->id) }}`)"
                                                class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center" style="font-size: 15px; line-height: 44px; background: #fdf0f0;">
                                        <strong class="text-danger"><i class="fa fa-exclamation-triangle"></i> No guests found{{ request()->hasAny(['name','phone_no','nid_no']) ? ' for this search' : '' }}.</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </form>
            @include('partials._paginate', ['data' => $guests])

        </div>
    </div>
@endsection



@section('js')
    @include('guests.include.script')
@endsection
