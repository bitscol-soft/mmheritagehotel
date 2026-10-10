@extends('layouts.master')
@section('title','Account Controls')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Account Controls" description="Account controls grouped by account group.">
    <x-slot name="actions">
        {{-- <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
            <div class="pull-right tableTools-container" style="margin: 0 !important">
                <div class="dt-buttons btn-overlap btn-group">
                    <a href="{{request()->url()}}" class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data" data-toggle="tooltip">
                        <span>
                            <i class="fa fa-refresh bigger-110"></i>
                        </span>
                    </a>
                    @if ((hasPermission("account.controls.delete", $slugs)))
                        <a href="{{route('account-controls.create')}}" class="dt-button btn btn-white btn-info btn-bold" title="Create New" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                            <span>
                                <i class="fa fa-plus bigger-110"></i>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </div> --}}
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center', 'width' => '8%'],
                ['label' => 'Account Group', 'width' => '20%'],
                ['label' => 'Name'],
                ['label' => 'Status', 'align' => 'center', 'width' => '15%'],
                ['label' => 'Actions', 'align' => 'center', 'width' => '15%'],
            ]" id="data-table" table-class="table table-bordered table-striped" label="Account Controls">
                @foreach($accountControls as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="pl-3">{{ optional($item->accountGroup)->name }}</td>
                        <td class="pl-3">{{ $item->name }}</td>
                        <td class="text-center">
                            {!! $item->status == 1 ? '<span class="label label-info">Active</span>' : '<span class="label label-warning">Inactive</span>' !!}
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                @include('partials._user-log', ['data' => $item])
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-mm.data-table>
    </x-mm.panel>
</x-mm.page>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
@endsection

