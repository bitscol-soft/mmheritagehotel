@extends('layouts.master')
@section('title', 'Table List')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop

@section('content')

<x-mm.styles />
@include('bar.tables.create-modal')
@include('bar.tables.edit-modal')
<x-mm.page class="mm-hotel-setup mm-bar mm-rst mm-rst-inv" title="Tables" description="Bar tables and their seating.">
    <x-slot name="actions">
        @if (hasPermission('bar.table-manages.create', $slugs))
                <a class="mm-button" href="#modal-dialog" data-toggle="modal">
                    <i class="fa fa-plus-circle"></i> Add New Table
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <h2 class="mm-setup-title">Table map</h2>
        <div class="mm-table-map" aria-label="Bar table map">
            @forelse ($table_manages as $tile)
                <article class="mm-table-tile" aria-label="Table {{ $tile->table_no }}">
                    <header class="mm-table-tile-head">
                        <h6>{{ $tile->name ?: 'Table' }}</h6>
                        <span class="mm-table-tile-status mm-table-tile-status-{{ (int) $tile->status }}">
                            {{ status($tile->status) }}
                        </span>
                    </header>
                    <div class="mm-table-tile-body">
                        <p class="mm-table-tile-no">#{{ $tile->table_no }}</p>
                    </div>
                    <footer class="mm-table-tile-foot">
                        @if (hasPermission('bar.sales.create', $slugs) && (int) $tile->status === 1)
                            <a href="{{ route('bar.sales-v2.create') }}?table_id={{ $tile->id }}"
                                class="btn btn-xs btn-primary" title="Take order">
                                <i class="fa fa-cutlery"></i> Order
                            </a>
                        @else
                            <span></span>
                        @endif
                        <div class="btn-group btn-corner">
                            @if (hasPermission('bar.table-manages.edit', $slugs))
                                <a href="#edit-modal"
                                    onclick="editTable(`{{ route('bar.table-manages.update', $tile->id) }}`,{{ $tile }})"
                                    data-toggle="modal" class="btn btn-xs btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                            @endif
                            @if (hasPermission('bar.table-manages.delete', $slugs))
                                <button type="button"
                                    onclick="delete_item(`{{ route('bar.table-manages.destroy', $tile->id) }}`)"
                                    class="btn btn-xs btn-danger" title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif
                        </div>
                    </footer>
                </article>
            @empty
                <div class="alert alert-info" style="grid-column: 1 / -1;">No tables yet — add one to get started.</div>
            @endforelse
        </div>

        <h2 class="mm-setup-title" style="margin-top:24px">Manage tables</h2>
        <x-mm.data-table :columns="[
            ['label' => 'Sl', 'width' => '5%'],
            ['label' => 'Name'],
            ['label' => 'No'],
            ['label' => 'Status', 'width' => '10%'],
            ['label' => 'Action', 'width' => '10%', 'align' => 'center'],
        ]" id="data-table" label="Tables" table-class="table table-striped table-bordered nowrap">
            @php($sl = $table_manages->firstItem())
            @forelse ($table_manages as $item)
                <tr class="odd gradeX">
                    <td>{{ $sl++ }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->table_no }}</td>
                    <td>{{ status($item->status) }}</td>
                    <td class="text-center">

                        <div class="btn-group btn-corner">

                            @if (hasPermission('bar.table-manages.edit', $slugs))
                                <a href="#edit-modal"
                                    onclick="editTable(`{{ route('bar.table-manages.update', $item->id) }}`,{{ $item }})"
                                    data-toggle="modal" class="btn btn-sm btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                            @endif

                            @if (hasPermission('bar.table-manages.delete', $slugs))
                                <button type="button"
                                    onclick="delete_item(`{{ route('bar.table-manages.destroy', $item->id) }}`)"
                                    class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif

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

    <script>
        function editTable(url, item) {

            let $item = JSON.parse(JSON.stringify(item));
            let status = $item.status;

            $('.edit-name').val($item.name);
            $('.edit-table-no').val($item.table_no);

            var $radios = $('input:radio[name=status]');
            $radios.filter('[value=' + status + ']').prop('checked', true);

            $('#editForm').attr('action', url)
        }
    </script>
@endsection
