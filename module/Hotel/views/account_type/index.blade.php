@extends('layouts.master')
@section('title','Add New Account Type')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Account types" description="Payment accounts available when collecting money.">
    @include('partials._alert_message')

    <div class="mm-setup-split">
        <x-mm.panel>
            {{-- W3.4: converted from <x-mm.table-scroll> + raw <table>
                 to <x-mm.data-table>. --}}
            <x-mm.data-table label="Account types"
                table-class="table table-striped table-bordered table-hover"
                :columns="[
                    ['label' => 'SL'],
                    ['label' => 'Name', 'align' => 'center'],
                    ['label' => 'Action', 'align' => 'center'],
                ]">
                @forelse ($account as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item->name }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                <a href="{{ route('account-type.edit', $item->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <button type="button" onclick="delete_check({{ $item->id }})" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>

                            <form action="{{ route('account-type.destroy',$item->id)}}" id="deleteCheck_{{ $item->id }}" method="POST">
                                @csrf
                                @method("DELETE")
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No account types found.</td>
                    </tr>
                @endforelse
            </x-mm.data-table>
        </x-mm.panel>

        <x-mm.panel>
            <h2 class="mm-setup-title">Add account type</h2>
            <form class="form-horizontal" id="companyForm" action="{{ route('account-type.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                {{-- W3.4: replaced Bootstrap-3 form-group markup with
                     <x-mm.field>. The field name, value (with old() for
                     validation repopulation), and placeholder are
                     preserved byte-identically. --}}
                <x-mm.field label="Account type name" id="account-type-name" name="name" value="{{ old('name') }}" placeholder="Account type name" :error="$errors->first('name')" />

                <div class="tw-mt-4 tw-flex tw-justify-end tw-gap-2">
                    <button type="submit" class="mm-button">
                        <i class="fa fa-save" aria-hidden="true"></i>
                        Save
                    </button>
                </div>
            </form>
        </x-mm.panel>
    </div>
</x-mm.page>
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
@stop
