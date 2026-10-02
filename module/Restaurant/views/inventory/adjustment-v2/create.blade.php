@extends('layouts.master')
@section('title', 'Stock Adjustment Create')

@push('style')
    <style>
        input.form-control.small-box {
            height: 28px;
        }

        input.form-control.small-label-box {
            height: 24px;
            padding: 1.5px;
            border: 1px solid rgba(204, 204, 204, 0.51) !important;
        }

        .table>thead {
            background: #ddd;
        }
    </style>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-rst mm-rst-inv mm-rst-form" title="Stock adjustment" description="Adjust stock for the selected supplier.">
    <x-slot name="actions">
        @if (hasPermission('rst.stock-adjustment.index', $slugs))
                <a href="{{ route('rst.stock-adjustment.index') }}" class="mm-button">
                    <i class="ace-icon fa fa-list-alt"></i> List
                </a>
        @endif

    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <div class="row">
            <form method="POST" action="{{ route('rst.stock-adjustment.store') }}" id="purchase-form">
                @csrf

                <div class="col-md-12">
                    @include('inventory.adjustment-v2.inc.common')
                    <div class="row">
                        <div class="col-md-12">

                            @include('inventory.adjustment-v2.create.left-side')

                        </div>
                        <div class="col-md-8">

                        </div>
                        <div class="col-md-4">

                            @include('inventory.adjustment-v2.create.right-side')

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </x-mm.panel>
</x-mm.page>

@endsection

@section('script')
    @include('inventory.adjustment-v2/inc/script')
@endsection
