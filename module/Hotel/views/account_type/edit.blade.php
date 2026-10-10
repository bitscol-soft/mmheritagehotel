@extends('layouts.master')
@section('title',' Edit Account Type')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Edit account type" description="Rename the account type or change its status.">
    <x-slot name="actions"><a class="mm-button mm-button-secondary" href="{{ route('account-type.index') }}"><i class="fa fa-list-alt"></i> Account types</a></x-slot>
    @include('partials._alert_message')

    <x-mm.panel class="mm-setup-narrow">
        <form class="form-horizontal" id="companyForm" action="{{ route('account-type.update', $account->id) }}" method="post">
            @csrf
            @method('PUT')

            {{-- W3.4: replaced LaravelCollective Form::text() / raw
                 <select> markup with <x-mm.field> and <x-mm.select>.
                 The field id, name, value, placeholder, and validation
                 error hooks are preserved byte-identically. --}}
            <div class="tw-grid tw-gap-4 sm:tw-grid-cols-2">
                <x-mm.field label="Account type" id="account-type-name" name="name" value="{{ $account->name }}" placeholder="Edit account" :error="$errors->first('name')" />
                <x-mm.select name="status" label="Status"
                    :options="['1' => 'Active', '0' => 'In Active']"
                    selected="{{ $account->status }}"
                    placeholder="Select option"
                    :error="$errors->first('status')" />
            </div>

            <div class="tw-mt-6 tw-flex tw-justify-end tw-gap-2">
                <button type="submit" class="mm-button">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    Save
                </button>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')


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
