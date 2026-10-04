@extends('layouts.master')
@section('title', 'Product Inventory')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style>
        .bg-dark {
            background-color: #C9DAF8;
        }
    </style>

@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv mm-rst-inventory" title="Product inventory" description="Stock on hand for the selected product filters.">
    <x-mm.panel class="mm-report-filter">
        @include('bar.inventory.includes.filter')
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')

        <div class="json_table mt-2">

            <div class="row">
                <div class="col-xs-12">
                    <x-mm.data-table :columns="[
                        ['label' => 'SL'],
                        ['label' => 'Product Name'],
                        ['label' => 'Category'],
                        ['label' => 'Opening Qty', 'align' => 'right'],
                        ['label' => 'Purchase Qty', 'align' => 'right'],
                        ['label' => 'Sold Qty', 'align' => 'right'],
                        ['label' => 'Return Qty', 'align' => 'right'],
                        ['label' => 'Available Qty', 'align' => 'right'],
                    ]" id="datatable" table-class="table table-striped table-bordered table-hover mb-2" label="Product inventory">
                        @forelse ($products as $key => $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ optional($product->category)->name }}</td>
                                <td class="text-right">{{ $product->opening_quantity }}</td>
                                <td class="text-right">{{ $product->purchased_quantity }}
                                </td>
                                <td class="text-right">
                                    @php
                                        $actual_qty = '';
                                        $sold_qty = explode('.', number_format($product->sold_quantity, 2, '.', ''));
                                        $actual_qty .= $sold_qty[0] . ' ' . optional($product->unit)->name;

                                        if ((float) $sold_qty[1] ?? '00' != '00') {
                                            $qty = $sold_qty[1] / $product->pack_size;
                                            $actual_qty .= ' ' . $qty . ' ' . optional($product->pack_unit)->name;
                                        }
                                    @endphp
                                    {{ $actual_qty }}
                                </td>
                                <td class="text-right">{{ $product->return_quantity }}</td>
                                <td class="text-right">
                                    {{ $product->available_quantity }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="30" class="text-center">
                                    <b class="text-danger">No records found!</b>
                                </td>
                            </tr>
                        @endforelse
                    </x-mm.data-table>

                </div>
            </div>

        </div>
        <input type="hidden" id="csrf" value="{{ csrf_token() }}">
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>
@stop
