@extends('layouts.master')

@section('title', 'Account Setups')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Account Setups" description="Configured system account setups and their active status.">
        <x-mm.panel class="tw-p-4">
            <x-mm.data-table :columns="[
                ['label' => '#', 'width' => '8%'],
                ['label' => 'Name'],
                ['label' => 'Status', 'width' => '15%'],
            ]" id="dataTable" table-class="table table-striped table-bordered table-hover" label="Account Setups">
                @forelse($data as $key => $setup)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $setup->name }}</td>
                        <td>
                            @if($setup->status)
                                <span class="label label-success arrowed-in">Active</span>
                            @else
                                <span class="label label-grey arrowed-in">Inactive</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">No account setups found.</td></tr>
                @endforelse
            </x-mm.data-table>
        </x-mm.panel>
    </x-mm.page>
@stop
