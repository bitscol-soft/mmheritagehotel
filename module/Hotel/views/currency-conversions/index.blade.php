@extends('layouts.master')

@section('title', 'Currency Conversions')

@section('page-header')
    <i class="fa fa-gears"></i> Currency Conversions
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@stop

@section('content')

    <div class="page-header">
        <h1>
            <i class="fa fa-info-circle"></i> Currency Conversions List
        </h1>
    </div>

    <x-alert-message />

    <div class="row">
        <div class="col-xs-7">

            <!-- SEARCHING -->
            <div class="row">
                <form action="">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Currency</span>
                                    <input type="text" name="currency_id" class="form-control" placeholder="Currency">
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Rate</span>
                                    <input type="text" name="rate" class="form-control" placeholder="Rate">
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Effected Date</span>
                                    <input type="text" name="effected_date" class="form-control date-picker" placeholder="Effected Date" autocomplete="off">
                                </div>
                            </td>
                            <td style="width: 10%">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-info">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-default">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
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
            </div>
        </div>

        <div class="col-xs-5">
            <div class="col-sm-12 render-currency-class">
                @include('currency-conversions.create')
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
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
