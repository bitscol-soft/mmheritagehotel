@extends('layouts.master')

@section('title', 'Kitchen Order List')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-rst mm-rst-board" title="Kitchen board" description="Accept new orders, mark them ready and send them to be served.">
    <x-slot name="actions">
        <a href="#" id="mm-focused-fullscreen-btn" data-mm-fullscreen-toggle aria-pressed="false" class="mm-button mm-button-secondary mm-focused-fs-btn" title="Toggle distraction-free Focused Fullscreen Mode (Alt+Z)">
            <i class="ace-icon fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen
        </a>
    </x-slot>
    <x-alert-message />

    <div id="mm-fullscreen-hud" class="mm-no-print" role="region" aria-label="Focused fullscreen controls">
        <div class="mm-fullscreen-hud-left">
            <span class="mm-fullscreen-hud-badge"><i class="fa fa-arrows-alt" aria-hidden="true"></i> Focused Fullscreen Mode</span>
            <span><strong>Kitchen Display Board (KDS)</strong> · Distraction-Free Pass Terminal (Press <code>Esc</code> or <code>Alt+Z</code> to exit)</span>
        </div>
        <a href="#" id="mm-fullscreen-exit-btn" data-mm-fullscreen-exit class="mm-fullscreen-exit-btn">
            <i class="fa fa-compress" aria-hidden="true"></i> Exit Fullscreen
        </a>
    </div>

    <div class="mm-kds-badge-row mm-no-print" role="note" aria-label="KDS pacing legend">
        <span class="mm-kds-course-badge"><i class="fa fa-clock-o" aria-hidden="true"></i> SLA Timer · Green on time</span>
        <span class="mm-kds-course-badge is-hold"><i class="fa fa-pause" aria-hidden="true"></i> Course Hold · Fire on Call</span>
        <span class="mm-kds-mod-badge is-allergy"><i class="fa fa-warning" aria-hidden="true"></i> Allergen Alert · Check Before Plating</span>
        <span class="mm-kds-mod-badge"><i class="fa fa-leaf" aria-hidden="true"></i> Modifier · Prep Note Attached</span>
    </div>

    <div class="mm-board-layout">
        <div class="mm-board-orders">
            @foreach ($orders as $order)

                @if ($order->order_status != 'Complete')
                    <article class="mm-board-card">
                        <header class="mm-board-card-head">
                            <h6>Order No : {{ ++$loop->index }}
                            </h6>
                        </header>

                        <div class="mm-board-card-body">
                            <p class="alert alert-info" style="font-size: 12px">
                                <b>Order : {{ $order->invoice_no }}</b> <br>
                                <b>Table No : {{ $order->table_no }}</b><br>
                                <b>Waiter No : {{ $order->waiter_no }}</b><br>
                                @foreach ($order->order_items as $item)
                                    <b>Item {{ ++$loop->index }} : {{ $item->product->name }} ×
                                        {{ $item->qty }}</b><br>
                                @endforeach
                            </p>
                        </div>

                        <footer class="mm-board-card-foot">
                            <button class="btn btn-xs btn-danger pull-left">
                                <i class="ace-icon fa fa-times"></i>
                                <span class="bigger-50">don't accept</span>
                            </button>
                            @if ($order->order_status == 'Pending')
                                <form action="{{ route('kit.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="Cooking">
                                    <button class="btn btn-xs btn-success pull-right">
                                        <span class="bigger-50">Accept</span>
                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                    </button>
                                </form>
                            @elseif ($order->order_status == 'Cooking')
                                <form action="{{ route('kit.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="Ready">
                                    <button class="btn btn-xs btn-warning pull-right">
                                        <span class="bigger-50">Cooking</span>
                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                    </button>
                                </form>
                            @elseif ($order->order_status == 'Ready')
                                <form action="{{ route('kit.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="Complete">
                                    <button class="btn btn-xs btn-info pull-right">
                                        <span class="bigger-50">To Serve</span>
                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                    </button>
                                </form>
                            @else
                                <span class="label label-sm label-danger">No New Order Found</span>
                            @endif

                        </footer>
                    </article>
                @endif
            @endforeach
        </div>

        <div class="render-currency-class mm-board-list">
            <x-mm.panel>
                <h2 class="mm-setup-title"><i class="fa fa-plus-circle"></i> Order List</h2>
                <x-mm.table-scroll label="Order list">
                    <table id="data-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="1%">Sl</th>
                                <th width="10%" style="text-align: center">Invoice ID</th>
                                <th width="15%" class="text-center">Date</th>
                                <th class="text-center" width="1%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        {{ ++$loop->index }}
                                    </td>
                                    <td>
                                        {{ $order->invoice_no }}
                                    </td>
                                    <td>
                                        {{ $order->date }}
                                    </td>
                                    <td>
                                        {{ $order->order_status }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </x-mm.table-scroll>
            </x-mm.panel>
        </div>
    </div>
</x-mm.page>
@endsection

@section('js')

    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    @include('currency-conversions.inc.script')

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }
    </script>
@stop
