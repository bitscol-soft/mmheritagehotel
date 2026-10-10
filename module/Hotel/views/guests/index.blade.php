@extends('layouts.master')
@section('title', 'Guest List')

@section('content')
    <x-mm.styles />
    <x-mm.page title="Guest directory" description="Find guest details, manage records and stay in touch.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('guests.create', ['type' => 'upload']) }}">
                <i class="fa fa-upload" aria-hidden="true"></i> Upload Guests
            </a>
            <a class="mm-button" href="{{ route('guests.create') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Guests
            </a>
        </x-slot>

        {{-- W1.7: list-level toolbar. The primary actions live in the page
             actions slot above; the toolbar demonstrates the pattern with
             a trailing reset control for the filter form. The row count
             badge stays below the toolbar in its original location. --}}
        <x-mm.toolbar label="Guest list actions">
            <x-slot:trailing>
                <button type="reset" form="searchForm" class="mm-button mm-button-ghost">
                    <i class="fa fa-undo" aria-hidden="true"></i> Reset
                </button>
            </x-slot:trailing>
        </x-mm.toolbar>

        <x-mm.badge>Total: {{ $guests->total() }}</x-mm.badge>

    @include('partials._alert_message')

    <div class="tw-space-y-4">

            @include('guests/include/filter')

            <div class="mm-panel tw-overflow-hidden">
            <form id="submitSendSmsForm" action="{{ route('guests.send-sms') }}" method="GET">
                @csrf

                <input type="hidden" id="isAllSelected" name="isAllSelected" value="0">
                <input type="hidden" name="isFromGuestList" value="1">

                <div class="tw-flex tw-flex-wrap tw-justify-end tw-gap-2 tw-p-4 mm-no-print">
                    <button type="button" onclick="selectEveryone()" id="selectEveryone" class="mm-button mm-button-secondary send-sms-btn">
                        <i class="fa fa-check" aria-hidden="true"></i> Select All
                    </button>
                    <button type="submit" class="mm-button send-sms-btn">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i> Send SMS
                    </button>
                </div>

                @php
                    $guestColumns = [
                        ['label' => '<label class="inline"><input type="checkbox" id="selectAll" class="ace" aria-label="Select all visible guests"><span class="lbl"></span></label>', 'raw' => true, 'align' => 'center'],
                        ['label' => 'SL'],
                        ['label' => 'Name', 'align' => 'center'],
                        ['label' => 'Phone', 'align' => 'center'],
                        ['label' => 'Gender', 'align' => 'center'],
                        ['label' => 'Email', 'align' => 'center'],
                        ['label' => 'NID / Passport', 'align' => 'center'],
                        ['label' => 'Country', 'align' => 'center'],
                        ['label' => 'Action', 'align' => 'center'],
                    ];
                @endphp
                <x-mm.data-table label="Guest directory records"
                    table-class="table table-striped table-bordered table-hover"
                    :columns="$guestColumns">
                    @forelse ($guests as $item)
                        <tr>
                            <td class="text-center">
                                <label class="inline">
                                    <input type="checkbox" name="guest_id[]" value="{{ $item->id }}" class="ace" aria-label="Select {{ $item->name }}">
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
                            <td colspan="9" class="text-center tw-p-6">
                                <strong class="text-danger"><i class="fa fa-exclamation-triangle"></i> No guests found{{ request()->hasAny(['name','phone_no','nid_no']) ? ' for this search' : '' }}.</strong>
                            </td>
                        </tr>
                    @endforelse
                </x-mm.data-table>

            </form>
            </div>
            @include('partials._paginate', ['data' => $guests])

    </div>
    </x-mm.page>
@endsection



@section('js')
    @include('guests.include.script')
@endsection
