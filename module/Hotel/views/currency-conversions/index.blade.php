@extends('layouts.master')
@section('title','Currency Conversions')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Currency conversions" description="Exchange rates and the date each rate takes effect.">
    <x-alert-message />

    <div class="mm-setup-split">
        <div class="mm-setup-stack">
            <x-mm.panel>
        <form action="" class="mm-setup-filter">
            <div class="input-group">
                <span class="input-group-addon">Currency</span>
                <input type="text" name="currency_id" class="form-control" placeholder="Currency">
            </div>
            <div class="input-group">
                <span class="input-group-addon">Rate</span>
                <input type="text" name="rate" class="form-control" placeholder="Rate">
            </div>
            <div class="input-group">
                <span class="input-group-addon">Effected Date</span>
                <input type="text" name="effected_date" class="form-control date-picker" placeholder="Effected Date" autocomplete="off">
            </div>
            <button class="mm-button" aria-label="Search"><i class="fa fa-search"></i> Search</button>
            <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset"><i class="fa fa-refresh"></i> Reset</a>
        </form>
    </x-mm.panel>

            <x-mm.panel>
                <x-mm.table-scroll label="Currency conversions">
                    <table id="data-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-center">Currency</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Effected Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($currencyConversions as $key => $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ optional($data->currency)->name }}</td>
                                    <td class="text-center">{{ $data->rate }}</td>
                                    <td class="text-center">{{ $data->effected_date }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            @if ($data->id == 1 || $data->id == 2)
                                            @else
                                                <a href="{{ route('currency-conversions.edit', $data->id) }}"
                                                    class="btn btn-xs btn-sm btn-success render-currency-view" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                <button type="button" onclick="delete_check({{ $data->id }})"
                                                    class="btn btn-xs btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif
                                        </div>

                                        <form action="{{ route('currency-conversions.destroy', $data->id) }}"
                                            id="deleteCheck_{{ $data->id }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-mm.table-scroll>
            </x-mm.panel>
        </div>

        <div class="render-currency-class">
            @include('currency-conversions.create')
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
