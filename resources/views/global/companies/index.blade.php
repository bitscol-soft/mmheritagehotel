
@extends('layouts.master')
@section('title','Company')
@section('css')

@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-index" title="Company">
        <x-slot name="actions">
            @if (hasPermission("company.infos.create", $slugs))
                <a class="btn btn-sm btn-primary" href="{{ route('company.create') }}">
                    <i class="fa fa-plus"></i> Add @yield('title')
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Group Name</th>
                            <th>Company Name</th>
                            <th>Business Type</th>
                            <th>Company Code</th>
                            <th>Contact Name</th>
                            <th>Phone Number</th>
                            <th>Logo</th>
                            <th width="12%" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($companies as $key => $company)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $company->group->name }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->business_type }}</td>
                                <td>{{ $company->code }}</td>
                                <td>{{ $company->contact_name }}</td>
                                <td>{{ $company->phone_number }}</td>
                                <td class="text-center">
                                    @if ($company->logo == "default.png")
                                        <dd><img src="{{ asset('uploads/'.$company->logo) }}" alt="{{ $company->name }}"
                                                style="width: 60px"></dd>
                                    @else
                                        <dd><img src="{{ asset('uploads/company/'.$company->logo) }}"
                                                alt="{{ $company->name }}" style="width: 60px"></dd>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-corner">
                                        @if (hasPermission("company.infos.view", $slugs))
                                            <a href="#company-details{{ $company->id }}" role="button" data-toggle="modal"
                                            class="btn btn-sm btn-purple" title="Company Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif

                                        @if (hasPermission("company.infos.edit", $slugs))
                                            <a href="{{ route('company.edit',$company->id) }}" class="btn btn-sm btn-success"
                                            title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif

                                        @if (hasPermission("company.infos.delete", $slugs))
                                        <button type="button" onclick="delete_check({{ $company->id }})"
                                                class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                        @endif
                                    </div>
                                    <form action="{{ route('company.destroy',$company->id)}}"
                                        id="deleteCheck_{{ $company->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- @include('partials._paginate', ['data' => $companies]) --}}

            </div>
            {{-- export/print/save --}}
            {{-- <div class="pull-right" style="margin-top:-20px">
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/excel-icon.png') }}"></a>
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}" ></a>
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/word-icon.png') }}"></a>
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/printer-icon.png') }}"></a>
            </div> --}}

        </div>
    </div>
        </x-mm.panel>
    </x-mm.page>

    @foreach($companies as $company)

        <div id="company-details{{ $company->id }}" class="modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="blue bigger"><i class="fa fa-bank"></i> View @yield('title') Details</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="text-center">Company Info</h4>
                                        <dl id="dt-list-1" class="dl-horizontal">

                                            <dt>Group Name</dt>
                                            <dd>{{ $company->group->name }}</dd>

                                            <dt>Company Name</dt>
                                            <dd>{{ $company->name }}</dd>

                                            <dt>Business Type</dt>
                                            <dd>{{ $company->business_type }}</dd>
{{--                                            <dd>{{ $company->business_type->name }}</dd>--}}

                                            <dt>Company Code</dt>
                                            <dd>{{ $company->code }}</dd>

                                            <dt>Head Office</dt>
                                            <dd>{{ $company->head_office }}</dd>

                                            <dt>Short Name</dt>
                                            <dd>{{ $company->short_name }}</dd>

                                            <dt>Factory</dt>
                                            <dd>{{ $company->factory }}</dd>

                                            <dt>Contact Name</dt>
                                            <dd>{{ $company->contact_name }}</dd>

                                            <dt>Position</dt>
                                            <dd>{{ $company->position }}</dd>

                                            <dt>Phone Number</dt>
                                            <dd>{{ $company->phone_number }}</dd>

                                            <dt>Fax</dt>
                                            <dd>{{ $company->fax }}</dd>

                                            <dt>Email</dt>
                                            <dd>{{ $company->email }}</dd>

                                            <dt>Day Off</dt>
                                            <dd>{{ $company->day_off }}</dd>

                                            <dt>Country</dt>
                                            <dd>{{ $company->country }}</dd>

                                            <dt>Created At</dt>
                                            <dd>{{ \Carbon\Carbon::parse($company->created_at)->format('F d, Y h:i s A') }}</dd>

                                            <dt>Updated At</dt>
                                            <dd>{{ Carbon\Carbon::parse($company->updated_at)->format('F d, Y h:i s A') }}</dd>

                                        </dl>
                                    </div>
                                    <div class="col-sm-6">
                                        <h4 class="text-center">Company Details</h4>
                                        <dl id="dt-list-1" class="dl-horizontal">

                                            <dt>Company Logo</dt>
                                            @if ($company->logo == "default.png")
                                                <dd><img src="{{ asset('uploads/'.$company->logo) }}" alt="{{ $company->name }}"
                                                         style="width: 100px"></dd>
                                            @else
                                                <dd><img src="{{ asset('uploads/company/'.$company->logo) }}"
                                                         alt="{{ $company->name }}" style="width: 100px"></dd>
                                            @endif

                                            <dt>Top Text</dt>
                                            <dd>{{ $company->top_text }}</dd>


                                            <dt>Company Name</dt>
                                            <dd>{{ $company->name }}</dd>

                                            @if ($company->company_details)
                                                <dt>Vat No</dt>
                                                <dd>{{ $company->company_details->vat_no }}</dd>

                                                <dt>Facsimile Number</dt>
                                                <dd>{{ $company->company_details->facsimile_number }}</dd>

                                                <dt>Bonded/Trade License</dt>
                                                <dd>{{ $company->company_details->bonded_license }}</dd>

                                                <dt>Membership Number</dt>
                                                <dd>{{ $company->company_details->membership_number }}</dd>

                                                <dt>BKMEA Reg No.</dt>
                                                <dd>{{ $company->company_details->bkmea_reg_no }}</dd>

                                                <dt>Import Reg Certi.</dt>
                                                <dd>{{ $company->company_details->import_reg_certi }}</dd>

                                                <dt>Export Reg Certi.</dt>
                                                <dd>{{ $company->company_details->export_reg_certi }}</dd>

                                                <dt>EPB Reg No.</dt>
                                                <dd>{{ $company->company_details->epb_reg_no }}</dd>

                                                <dt>Organogram</dt>
                                                <dd>
                                                    @php
                                                        $file = 'uploads/company/extra/' . optional($company->company_details)->organogram;
                                                    @endphp

                                                    @if (optional($company->company_details)->organogram)
                                                        <a target="_blank" href="{{ asset('uploads/company/extra/' . optional($company->company_details)->organogram) }}">
                                                            <i class="fa fa-file"></i>
                                                        </a>
                                                    @endif
                                                </dd>

                                                <dt>Created At</dt>
                                                <dd>{{ \Carbon\Carbon::parse($company->created_at)->format('F d, Y h:i s A') }}</dd>

                                                <dt>Updated At</dt>
                                                <dd>{{ Carbon\Carbon::parse($company->updated_at)->format('F d, Y h:i s A') }}</dd>

                                            @endif

                                        </dl>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="text-center">Company Bank Details</h4>
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Company Name</th>
                                                <th>Account Name</th>
                                                <th>Account No</th>
                                                <th>Bank Name</th>
                                                <th>Branch</th>
                                                <th>Swift Code</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($company->company_bank_account as $key => $company_bank)
                                                <tr>
                                                    <td>{{ $company_bank->id }}</td>
                                                    <td>{{ $company->name }}</td>
                                                    <td>{{ $company_bank->account_name }}</td>
                                                    <td>{{ $company_bank->account_number }}</td>
                                                    <td>{{ $company_bank->bank_name }}</td>
                                                    <td>{{ $company_bank->branch }}</td>
                                                    <td>{{ $company_bank->swift_code }}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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

@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    

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


    <script type="text/javascript">
        jQuery(function($) {
            $('#dynamic-table').DataTable({
                "ordering": false,
                "bPaginate": false,
                "lengthChange": false,
                "info": false
            });

        })
    </script>
@stop
