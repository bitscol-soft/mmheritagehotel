

@extends('layouts.master')
@section('title','Weekly movement')
@section('page-header')
    <i class="fa fa-list"></i> Weekly movement (Issue)
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style>
        .bg-dark{
            background-color: #C9DAF8;
        }
    </style>

@stop


@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-gs mm-rst mm-rst-inv" title="Weekly movement (issue)" description="Items issued from the general store, week by week.">
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')


        <!-- filter -->
        <div class="row">
            <form class="form-horizontal" action="{{ route('weakly.movement.issue') }}" method="get">
                @csrf
                <div class="col-sm-12">
                    <x-mm.table-scroll label="Weekly movement">
                        <table class="table table-bordered">

                            <tr>
                                <th class="bg-dark">Company</th>
                                <th class="bg-dark">Department</th>
                                <th class="bg-dark">{{ $systemSetting->value != null ? $systemSetting->value : "Reference" }}</th>
                                <th class="bg-dark">To</th>
                                <th class="bg-dark center">Action</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <select name="company_id" class="form-control chosen-select">
                                            <option selected value="">select</option>
                                            @foreach($companies as $id => $name)
                                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected':'' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <select name="department_id" class="form-control chosen-select">
                                            <option selected value="">select</option>
                                            @foreach($departments as $id => $department)
                                                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected':'' }}>{{ $department }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" class="form-control input-sm" placeholder="Search by reference number" name="reference" id="" value="{{ request('reference') }}" >
                                </td>
                                <td>
                                    <div class="input-group" style="width: 150px">
                                        <span class="input-group-addon">To</span>
                                        <input type="text" class="form-control input-sm date-picker" readonly name="from_date" value="{{ request('from_date') ?? fdate(today()) }}" autocomplete="off">
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-corner">
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <a href="{{ route('goods-requisitions.index') }}" class="btn btn-xs btn-pink"><i class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </x-mm.table-scroll>
                </div>
            </form>
        </div>

        <div class="json_table">

            <div class="row">
                <div class="col-xs-12">
                    <x-mm.table-scroll label="Weekly movement">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background: #C9DAF8 !important; color:black !important">
                                    <th>SL</th>
                                    <th>Issue Date</th>
                                    <th>GIN Number</th>
                                    <th>Date</th>
                                    <th>Requisition No</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>{{ $systemSetting->value != null ? $systemSetting->value : "Reference" }}</th>
                                    <th>Total Qty</th>
                                    <th>View Details</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($goods_requisitions as $key => $goods_requisition)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td style="font-weight: bold !important;">{{ fdate($goods_requisition->issue_date) }}</td>
                                        <td class="text-primary">{{ $goods_requisition->issue_number }}</td>
                                        <td>{{ $goods_requisition->goods_requisition_date }}</td>
                                        <td>{{ $goods_requisition->form_number }}</td>
                                        <td>{{ $goods_requisition->company->name }}</td>
                                        <td>{{ $goods_requisition->department->name }}</td>
                                        <td>{{ $goods_requisition->goods_requisition_reference }}</td>
                                        <td>{{ $goods_requisition->goods_requisition_details->sum('quantity') }}</td>
                                        <td style="min-width: 90px" class="text-center">

                                            <span class="btn btn-info btn-minier btn-round popover-success" title="View Log"
                                                  data-rel="popover"
                                                  data-placement="top"
                                                  data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Log Information"
                                                  data-content="<p><b>Received By : </b>{{ $goods_requisition->updated_user->name }}.</p> <p> <b>Created At :</b> {{ fdate($goods_requisition->updated_at)  }} </p>">
                                                <i class="fa fa-info-circle"></i>
                                            </span>
                                                <a href="#goods-requisition-details{{ $goods_requisition->id }}" role="button" data-toggle="modal" class="btn btn-minier btn-purple btn-round" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                        </td>
                                    </tr>
                                @endforeach

                                @if (count($goods_requisitions) == 0)
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <b class="text-danger">No records found!</b>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </x-mm.table-scroll>

                    @if (count($goods_requisitions) != 0)
                        @include('partials._paginate', ['data' => $goods_requisitions])

                        <div class="pull-left" style="margin-top:10px; margin-left:10px">
                            <span onclick="exportData('{{ url('export-gs-as-excel') }}')" style="margin-right: 5px; cursor: pointer;">
                                <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                            </span>
                                    <span onclick="exportData('{{ url('export-gs-as-pdf') }}')" style="margin-right: 5px; cursor: pointer;">
                                <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}">
                            </span>
                        </div>

                        <form class="exportForm" method="POST">
                            @csrf
                            <input type="hidden" name="model" value="GIN List">
                            <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                            <input type="hidden" name="department_id" value="{{ request('department_id') }}">
                            <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                            <input type="hidden" name="gin_number" value="{{ request('gin_number') }}">
                        </form>
                    @endif

                </div>
            </div>

        </div>
        <input type="hidden" id="csrf" value="{{ csrf_token() }}">



        {{-- goods_requisitions detail modals --}}
        @foreach($goods_requisitions as $key => $goods_requisition)

            <div id="goods-requisition-details{{ $goods_requisition->id }}" class="modal" tabindex="-1">
                <div class="modal-dialog modal-lg" style="width: 75% !important; margin-left: auto !important; margin-right: auto !important;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="blue bigger"><i class="fa fa-eye"></i> View Goods Requisition Details</h4>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <dl id="dt-list-1" class="dl-horizontal">
                                        <p class="text-center">Date : {{ $goods_requisition->goods_requisition_date }}</p>
                                        <p class="text-center">Goods Requisition No : {{ $goods_requisition->form_number }}</p>
                                        <p class="text-center">Issue Number : {{ $goods_requisition->issue_number ?? 'Not Used' }}</p>
                                        <p class="text-center">Company : {{ $goods_requisition->company->name }}</p>

                                        <table class="table table-bordered">
                                            <tr>
                                                <th>SL</th>
                                                <th>Items</th>
                                                <th>Item Unit</th>
                                                <th>Remarks</th>
                                                <th>Stock In Hand</th>
                                                <th>Issue Quantity</th>
                                                <th>Received From</th>
                                            </tr>
                                            @php
                                                $total_received_amount = 0;
                                                $total_required_quantity = 0;
                                                $total_received_quantity = 0;
                                            @endphp
                                            @foreach ($goods_requisition->goods_requisition_details as $i => $requisition)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $requisition->item->name  }}</td>
                                                    <td>{{ $requisition->item->item_unit->name   }}</td>
                                                    <td>{{ $requisition->remarks  }}</td>
                                                    <td>{{ $requisition->item->current_stock  }}</td>
                                                    <td>{{ $requisition->quantity  }}</td>
                                                    <td>
                                                        @if (collect($requisition_from_items)[$key][$i])
                                                            <a target="_blank" href="{{ route('items.show', optional(collect($requisition_from_items)[$key][$i])->id) }}" >Item, </a>
                                                        @endif
                                                        @foreach($requisition_from_receives[$key][$i] as $j => $receive_item)
                                                            <a target="_blank" href="{{ route('grn.list.show', $receive_item->id) }}" >{{ $receive_item->form_number }},</a>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5">Total</td>
                                                <td>{{ $goods_requisition->goods_requisition_details->sum('quantity') }}</td>
                                                <td></td>
                                            </tr>
                                        </table>

                                    </dl>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-sm" data-dismiss="modal">
                                <i class="ace-icon fa fa-times"></i>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    

    <script type="text/javascript">
        function exportData(url)
        {
            $('.exportForm').attr('action', url).submit();
        }
    </script>

    <script type="text/javascript">
        $('[data-rel=popover]').popover({html:true});
    </script>

    <!--  Select Box Search-->
    <script type="text/javascript">

        jQuery(function($){

            if(!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect:true});
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({'width': '220px'});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if(event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width':'220px'});
                    })
                });


                $('#chosen-multiple-style .btn').on('click', function(e){
                    var target = $(this).find('input[type=radio]');
                    var which = parseInt(target.val());
                    if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
                    else $('#form-field-select-4').removeClass('tag-input-style');
                });
            }

        })
    </script>

    <!--datepicker plugin-->
    <script type="text/javascript">
        jQuery(function($) {

            $('.date-picker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd',
                todayHighlight: true
            })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

        })
    </script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">

        //  delete confirmation goods requisition
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
