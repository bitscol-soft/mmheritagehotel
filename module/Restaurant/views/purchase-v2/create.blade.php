@extends('layouts.master')
@section('title', 'Purchase Create')

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
    <x-mm.page class="mm-rst mm-rst-purchase mm-rst-purchase-create" title="Purchase create" description="Choose the supplier and account, add products and submit the purchase.">
        @if (hasPermission('rst.purchases.index', $slugs))
            <x-slot name="actions">
                <a href="{{ route('rst.purchases.index') }}" class="mm-button mm-button-secondary">
                    <i class="ace-icon fa fa-list-alt" aria-hidden="true"></i> List
                </a>
            </x-slot>
        @endif

        <x-alert-message />

        <x-mm.panel class="tw-p-4">
            <form method="POST" action="{{ route('rst.purchases.store') }}" id="purchase-form">
                @csrf

                @include('purchase-v2.inc.common')
                <div class="row">
                    <div class="col-md-9 mm-rst-purchase-lines">

                        @include('purchase-v2.create.left-side')

                    </div>
                    <div class="col-md-3">

                        @include('purchase-v2.create.right-side')

                    </div>
                </div>
            </form>
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('script')
    @include('purchase-v2/inc/script')
@endsection
