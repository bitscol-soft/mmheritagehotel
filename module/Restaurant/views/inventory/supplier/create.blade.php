@extends('layouts.master')
@section('title', 'Add Supplier')

@section('content')

<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-rst mm-rst-inv" title="Add Supplier" description="Register a supplier for restaurant purchases.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ route('rst.suppliers.index') }}">
            <i class="fa fa-list"></i> Supplier List
        </a>
    </x-slot>

    @include('partials._alert_message')

    <x-mm.panel class="tw-p-5">
        <form class="form-horizontal" id="supplier-form" action="{{ route('rst.suppliers.store') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-sm-12">

                    <div class="form-group">
                        <label for="supplier-name" class="col-sm-3 control-label">Supplier Name <sup class="text-danger">*</sup></label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" name="name" id="supplier-name" value="{{ old('name') }}" class="form-control"
                                placeholder="Enter supplier name" autocomplete="off" required>
                            @error('name')<p role="alert">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="supplier-phone" class="col-sm-3 control-label">Phone</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="number" name="phone" id="supplier-phone" value="{{ old('phone') }}" class="form-control"
                                placeholder="Phone" autocomplete="off">
                            @error('phone')<p role="alert">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="supplier-email" class="col-sm-3 control-label">Email</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="email" name="email" id="supplier-email" value="{{ old('email') }}" class="form-control"
                                placeholder="Email" autocomplete="off">
                            @error('email')<p role="alert">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="supplier-address" class="col-sm-3 control-label">Address</label>
                        <div class="col-xs-12 col-sm-8">
                            <textarea name="address" id="supplier-address" class="form-control" rows="3" placeholder="Address">{{ old('address') }}</textarea>
                            @error('address')<p role="alert">{{ $message }}</p>@enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-actions center" style="text-align: right !important;">
                <button type="submit" class="btn-sm btn-outline-success">
                    <i class="fa fa-save"></i> Save
                </button>

                <a href="{{ route('rst.suppliers.index') }}" class="btn-sm btn btn-default">
                    <i class="fa fa-backward"></i> Back List
                </a>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>

@endsection
