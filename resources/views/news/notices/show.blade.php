@extends('layouts.master')

@section('title','Notice Details')

@section('content')
    <x-mm.styles />
    <x-mm.page title="Notice Details" description="Published notice details.">
        <x-slot name="actions">
            <a href="{{ route('notices.index') }}" class="btn btn-sm btn-default">
                <i class="fa fa-list"></i> List
            </a>
            @if(hasPermission('notices.create', $slugs))
                <a href="{{ route('notices.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> Add New
                </a>
            @endif
        </x-slot>

        <x-mm.panel class="tw-p-4">
            <div class="row" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12">
                    <table style="font-size: 13px">
                        <tr>
                            <td>Publish At</td>
                            <td style="width: 20px;" class="text-center">:</td>
                            <td>{{ fdate($notice->publish_at, 'Y-m-d') . ' at ' . fdate($notice->publish_at, 'h:i:s a') }}</td>
                        </tr>
                        <tr>
                            <td>Company</td>
                            <td style="width: 20px;" class="text-center">:</td>
                            <td>{{ optional($notice->company)->name }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-12">
                    <h3 class="text-primary">Title: {{ $notice->title }}</h3>
                </div>
                <div class="col-sm-12">
                    {!! $notice->description !!}
                    <br><br>
                </div>
            </div>
        </x-mm.panel>
    </x-mm.page>
@endsection
