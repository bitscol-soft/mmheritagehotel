@extends('layouts.master')
@section('title', 'Banque tHall Categories List')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-banquet mm-room-inventory" title="Hall categories" description="Hall types and their guest capacity.">
        <x-slot name="actions">
            <a class="mm-button" href="{{ route('banquet.hall-categories.create') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Category
            </a>
        </x-slot>

        <x-alert-message />

        <x-mm.panel class="tw-p-4">
            <x-mm.data-table :columns="[
                ['label' => 'SL', 'align' => 'center'],
                ['label' => 'Name'],
                ['label' => 'Guest Capacity', 'align' => 'center'],
                ['label' => 'Status', 'align' => 'center'],
                ['label' => 'Action', 'align' => 'center'],
            ]" id="dynamic-table" label="Hall categories" table-class="table table-striped table-bordered table-hover">
                @forelse ($data as $item)
                    <tr>
                        <td class="center">{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="center">
                            <p>{{ $item->guest_capacity }} Person</p>
                        </td>
                        <td class="center">
                            @if ($item->status == 1)
                                <span class="label label-sm label-success">Active</span>
                            @else
                                <span class="label label-sm label-danger">In Active</span>
                            @endif
                        </td>

                        <td class="center">
                            <div class="btn-group">
                                <a class="btn btn-xs btn-success"
                                    href="{{ route('banquet.hall-categories.edit', $item->id) }}">
                                    <i class="ace-icon fa fa-edit"></i>
                                </a>

                                <button type="button"
                                    onclick="delete_item(`{{ route('banquet.hall-categories.destroy', $item->id) }}`)"
                                    class="btn btn-xs btn-sm btn-danger" title="Delete">
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
    </x-mm.page>
@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(".currency-sign").each(function() {
                $(this).text(`{!! currencySign() !!}`);
            });
        });
    </script>

    <script type="text/javascript">
        jQuery(function($) {
            var myTable =
                $('#dynamic-table')
                .DataTable({
                    bAutoWidth: false,
                    "aoColumns": [{
                            "bSortable": false
                        },
                        null, null, null, null, null,
                        {
                            "bSortable": false
                        }
                    ],
                    "aaSorting": [],

                    select: {
                        style: 'multi'
                    }
                });
        })
    </script>
@endsection
