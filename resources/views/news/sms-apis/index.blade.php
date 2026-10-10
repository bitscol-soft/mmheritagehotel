@extends('layouts.master')

@section('title','Apis')

@section('css')
    <style type="text/css">
        .bg-dark{
            background-color: #ededed;
        }
    </style>
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page title="Sms Apis" description="Configured SMS gateway APIs and balance status.">
        <x-slot name="actions">
            @if(hasPermission('sms-apis.create', $slugs))
                <span role="button" data-toggle="modal" class="btn btn-sm btn-primary add-new-btn">
                    <i class="fa fa-plus"></i> Add New
                </span>
            @endif
        </x-slot>

        <x-mm.panel class="tw-p-4">
            @include('partials._alert_message')

            <div class="row">

                <!-- add new panel -->
                @if(hasPermission('sms-apis.create', $slugs))
                    @include('news.sms-apis.create')
                @endif

                <!-- edit api panel -->
                @if(hasPermission('sms-apis.edit', $slugs))
                    @include('news.sms-apis.edit')
                @endif

                <div class="col-sm-12">
                    <x-mm.data-table :columns="[
                        ['label' => 'SL', 'width' => '5%'],
                        ['label' => 'Api Name'],
                        ['label' => 'Username'],
                        ['label' => 'Password'],
                        ['label' => 'Sender Id'],
                        ['label' => 'Url'],
                        ['label' => 'Sms Balance', 'align' => 'center'],
                        ['label' => 'Status', 'align' => 'center'],
                        ['label' => 'Action', 'width' => '20%', 'align' => 'center'],
                    ]" table-class="table table-bordered table-striped" label="Sms Apis">
                        @forelse($apis as $key => $api)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="name">{{ $api->name }}</td>
                                <td class="username">{{ $api->username }}</td>
                                <td class="password">{{ $api->password }}</td>
                                <td class="sender_number">{{ $api->sender_number }}</td>
                                <td class="url">{{ $api->url }}</td>
                                <td class="text-center">{{ $api->balance }}</td>
                                <td class="text-center"><span class="badge badge-{{ $api->status == 0 ? 'danger' : 'success' }}">{{ $api->status == 0 ? 'De-Active' : 'Active' }}</span></td>
                                <td class="text-center" style="min-width: 100px !important;">
                                    <div class="btn-group btn-corner">

                                        @if(hasPermission('sms-apis.active', $slugs))
                                            <a href="javascript:void(0)" role="button" data-id="{{ $api->id }}" class="btn btn-success btn-xs edit-api" ><i class="fa fa-pencil-square-o"></i></a>
                                        @endif

                                        @if(hasPermission('sms-apis.edit', $slugs) && $api->status == 0 )
                                            <a href="{{ route('sms-apis.show', $api->id) }}" title="Do Active" class="btn btn-primary btn-xs" ><i class="fa fa-thumbs-up"></i></a>
                                        @endif

                                        @if(hasPermission('processes.delete', $slugs))
                                            <button class="btn btn-xs btn-danger" onclick="delete_item('{{ route('sms-apis.destroy', $api->id) }}')" type="button"><i class="fa fa-trash-o"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center text-danger font-weight-bold">No records found</td>
                            </tr>
                        @endforelse
                    </x-mm.data-table>
                </div>
            </div>
        </x-mm.panel>
    </x-mm.page>



    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')


    

    <!-- delete confirm dialog -->
    <script type="text/javascript" src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

    <!-- fill edit form -->
    <script type="text/javascript">
        $('.create-panel').hide()
        $('.edit-panel').hide()

        $('.add-new-btn').click(function () {
            $('.edit-panel').hide()
            $('.create-panel').show()
        });
        $('.close-create-panel').click(function () {
            $('.create-panel').hide()
            $('.edit-panel').hide()
        });
        $('.create-api').click(function () {
            $('.create-from').submit()
        });
        $('.update-api').click(function () {
            $('.edit-form').submit()
        });

        $('.edit-api').click(function () {
            $('.create-panel').hide()

            let api_id = $(this).data('id')
            let root = $(this).closest('tr')
            let update_route = $('.edit-form').data('base_route') + "/" + api_id

            let edit_panel = $('.edit-panel')
            $('.edit-panel').find('.name').val(root.find('.name').text())
            $('.edit-panel').find('.username').val(root.find('.username').text())
            $('.edit-panel').find('.password').val(root.find('.password').text())
            $('.edit-panel').find('.sender_number').val(root.find('.sender_number').text())
            $('.edit-panel').find('.url').text(root.find('.url').text())
            $('.edit-form').attr('action', update_route)

            edit_panel.show()
        })
    </script>


    <!-- user log popover -->
    <script type="text/javascript">
        $('[data-rel=popover]').popover({html:true});
    </script>
@endsection


