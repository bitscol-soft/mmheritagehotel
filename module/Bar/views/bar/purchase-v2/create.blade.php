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

      .table>thead{
        background: #ddd;
      }
    </style>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-bar mm-rst mm-rst-inv mm-rst-form mm-rst-purchase mm-rst-purchase-create" title="Purchase create" description="Choose the supplier and account, add products and submit the purchase.">
    <x-slot name="actions">
        @if (hasPermission('bar.purchases.index', $slugs))
                <a class="mm-button" href="{{ route('bar.purchases.index') }}">
                    <i class="ace-icon fa fa-list-alt"></i> List
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <div class="row">
            <form method="POST" action="{{ route('bar.purchases.store') }}" id="purchase-form">
                @csrf

                <div class="col-md-12">
                    @include('bar.purchase-v2.inc.common')
                    <div class="row">
                        <div class="col-md-9">

                            @include('bar.purchase-v2.create.left-side')

                        </div>
                        <div class="col-md-3">

                            @include('bar.purchase-v2.create.right-side')

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('script')
    @include('bar.purchase-v2/inc/script')
@endsection
