@extends('layouts.master')
@section('title', 'Registration Terms List')
@section('page-header')
    <i class="fa fa-info-circle"></i> Registration Terms List
@stop

@section('content')
    <div class="page-header">
        {{-- <a class="btn btn-xs btn-info" href="{{ route('guests.create') }}" style="float: right; margin: 0 2px;"> <i
                class="fa fa-plus"></i> Add New Guests </a> --}}
        <h1>
            <i class="fa fa-info-circle green"></i> Registration Terms List
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            @include('guest-registration-terms.include.filter')
            <div>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th class="center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($bookingNotes as $item)
                            <tr>
                                <td class="center">{{ $loop->index + 1 }}</td>
                                <td>{{ strip_tags($item->title) }}</td>
                                <td class="center">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-success" href="{{ route('guest-registration-terms.edit', $item->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- @include('partials._paginate', ['data' => $bookingNotes]) --}}
        </div>
    </div>
@endsection
