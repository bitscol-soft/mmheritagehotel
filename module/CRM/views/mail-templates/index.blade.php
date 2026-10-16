@extends('layouts.master')
@section('title', 'CRM Mail Template')

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-inv" title="Mail Template Lists" description="Templates mailed by the scheduled CRM invoice command.">
    <x-slot name="actions">
        @if (hasPermission('crm.mail_templates.create', $slugs))
            <a class="mm-button" href="{{ route('crm.mail-templates.create') }}"><i class="fa fa-plus"></i> Add New</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        @if ($templates->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Body</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($templates as $template)
                            <tr>
                                <td>{{ $template->subject }}</td>
                                <td>{{ $template->body }}</td>
                                <td class="text-center">
                                    @if ($template->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        @if (hasPermission('crm.mail_templates.edit', $slugs))
                                            <a href="{{ route('crm.mail-templates.edit', $template->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif
                                        @if (hasPermission('crm.mail_templates.delete', $slugs))
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                onclick="delete_item('{{ route('crm.mail-templates.destroy', $template->id) }}')">
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
