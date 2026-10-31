@extends('layouts.master')
@section('title', 'CRM Customer')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-inv" title="Customer Lists" description="Company / contact master used by bookings, banquet and POS.">
    <x-slot name="actions">
        @if (hasPermission('crm.customers.create', $slugs))
            <a class="mm-button" href="{{ route('crm.customers.create') }}"><i class="fa fa-plus"></i> Add New</a>
        @endif
    </x-slot>

    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">
            <div class="input-group">
                <label class="input-group-addon" for="">
                    Organization
                </label>

                <input type="text" name="org_name" class="form-control" placeholder="Search organization"
                    value="{{ request('org_name') }}">
            </div>
            <div class="input-group">
                <label class="input-group-addon" for="">
                    City
                </label>

                <input type="text" name="city" class="form-control" placeholder="Search city"
                    value="{{ request('city') }}">
            </div>
            <div class="input-group">
                <button type="submit" class="mm-button mm-button-primary">
                    <i class="fa fa-search"></i>
                    Search</button>
            </div>
        </form>
    </x-mm.panel>

    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        @if ($customers->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Organization</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>City</th>
                            <th class="text-center">Lead</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->org_name }}</td>
                                <td>{{ $customer->c_name }}</td>
                                <td>{{ $customer->org_phone }}</td>
                                <td>{{ $customer->org_email }}</td>
                                <td>{{ $customer->city }}</td>
                                <td class="text-center">{{ $customer->leads ? 'Yes' : 'No' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        @if (hasPermission('crm.customers.edit', $slugs))
                                            <a href="{{ route('crm.customers.edit', $customer->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif
                                        @if (hasPermission('crm.customers.delete', $slugs))
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                onclick="delete_item('{{ route('crm.customers.destroy', $customer->id) }}')">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center">
                <span class="text-warning">No Records Founds Yet!</span>
            </div>
        @endif
        <br>
        <!-- INPUTS -->
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
@endsection
