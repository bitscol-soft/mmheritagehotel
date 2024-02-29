@extends('layouts.master')
@section('title', 'Purchase')
@section('page-header')
    <i class="fa fa-list"></i> Purchase Requisition Details
@stop
@section('css')

    <style>
        @media print {
            .d-print-none {
                display: none !important;
            }

            .d-none {
                display: block !important;
            }

            .border-print-none {
                border: none !important;
            }
        }

        @page {
            size: A4;

        }

        @page :left {
            margin-left: 3cm;
        }

        .d-none {
            display: none !important;
        }

        .border {
            border: 1px solid black !important;
        }
    </style>
@stop

@section('content')

{{-- @dd($purchases); --}}
    <div class="page-header d-print-none">
        @if (hasPermission('rst.purchase.create', $slugs))
            <a class="btn btn-xs btn-info" href="{{ route('rst.purchases.create') }}" style="float: right; margin: 0 2px;"> <i
                    class="fa fa-plus"></i> Add @yield('title') </a>
        @endif
        @if (hasPermission('rst.purchase.view', $slugs))
            <a href="{{ route('rst.purchases.index') }}" class="btn btn-xs btn-success" style="float: right; margin: 0 2px;"> <i
                    class="fa fa-list"></i> List </a>
        @endif

        <span class="d-print-none" onclick="printForm()" style="margin-right: 5px; cursor: pointer;">
            <img style="float: right" src="{{ asset('assets/images/export-icons/printer-icon.png') }}">
        </span>
        <h1>
            @yield('page-header')
        </h1>
    </div>



    <div class="row">
        <div class="col-sm-10 col-sm-offset-1 border-print-none" style="border: none !important;">
            <table class="table no-spacing" style="border: none !important;">
                <tr style="border: none !important;">
                    <td colspan="9" class="text-center border-print-none" style="border-top: none !important;">

                        <div style="border: none !important;">
                            <h2><b></b> {{ optional($purchases->company)->name }} </h2>
                            <h3 style="margin-top: -5px !important;" class="text-center">Purchase Form</h3>
                        </div>
                    </td>
                </tr>
                <tr>
                        <td colspan="9" style="border-top: none;">
                        <b>Purchase Form No:</b> {{ $purchases->challan_id }}
                        <b style="padding-left: 40px">Date:</b>{{ $purchases->date }}
                </tr>
                <tr>
                    <th class="border">Sl</th>
                    <th class="border">Item Description</th>
                    <th class="border">Unit</th>
                    <th class="border" width="10%">Required Qty</th>
                    <th class="border">Stock</th>
                    <th class="border">Rate</th>
                    <th class="border">Total</th>
                    <th class="border">Remarks</th>
                </tr>
                @foreach ($purchases->purchase_details as $key => $details)
                    <tr>
                        <td class="border">{{ $key + 1 }}</td>
                        <td class="border">{{ $details->product->name }}</td>
                        <td class="border">{{ $details->product->unit->name }}</td>
                        <td class="border">{{ $details->quantity }}</td>
                        <td class="border">{{ optional(optional($details->product)->rstStock)->available_quantity }}</td>
                        <td class="border">{{ $details->product->sale_price }}</td>
                        <td class="border">{{ number_format($details->product->sale_price * $details->quantity,2) }}</td>
                        <td class="border"></td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <td class="border text-right" colspan="9" style="font-size: 11px !important;">
                            <b>Created By: {{ $purchases->created_user->name . ', ' . $purchases->created_at }}
                            @if ($purchases->is_approved == 1)
                            Approved By:  {{ $purchases->updated_user->name . ',' . $purchases->updated_at }}
                            @endif
                            </b>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <table class="table no-spacing" style="border: none !important; font-size: 10px !important;">
                <tr>
                    <td style="border-top: none !important;" width="40%">
                        <b>
                            <p>Created By: </p>
                            <p>Name: {{ $purchases->created_user->name }}</p>
                            <p>Designation: {{ optional(optional($purchases->created_user->employee)->designation)->name }}
                            </p>
                            <p>Date: {{ $purchases->created_at }}</p>
                        </b>
                    </td>
                    <td style="border-top: none !important;" width="40%">
                        <b>
                            <p>Updated By:</p>
                            <p>Name: {{ $purchase->updated_user->name ?? 'N/A' }}</p>
                            <p>Designation:
                                {{ $purchases->is_approved == 1 ? optional(optional($purchases->updated_user->employee)->designation)->name : '' }}
                            </p>
                            <p>Date: {{ $purchases->updated_at ?? 'N/A' }}</p>
                        </b>
                    </td>
                </tr>
            </table>
        </div>
    </div>

@endsection


@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function printForm() {
            print()
        }

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


    <script type="text/javascript">
        jQuery(function($) {
            $('#dynamic-table').DataTable({
                "ordering": false,
                "bPaginate": true,
            });

        })
    </script>
@stop
