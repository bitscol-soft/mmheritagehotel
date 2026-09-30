@extends('layouts.master')

@section('title', 'Account Setups')

@section('page-header')
    <i class="fa fa-cogs"></i> Account Setups
@stop

@section('content')
    <div class="widget-box">
        <div class="widget-header">
            <h5 style="font-weight:600"><i class="fa fa-list"></i> Account Setups</h5>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <table class="table table-striped table-bordered table-hover" id="dataTable">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($data as $key => $setup)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $setup->name }}</td>
                            <td>
                                @if($setup->status)
                                    <span class="label label-success arrowed-in">Active</span>
                                @else
                                    <span class="label label-grey arrowed-in">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">No account setups found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
