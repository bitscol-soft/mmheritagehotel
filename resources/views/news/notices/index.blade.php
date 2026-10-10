@extends('layouts.master')

@section('title','Notice List')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@stop

@section('content')
    <x-mm.styles />
    <x-mm.page title="Notice List" description="Published company notices.">
        <x-slot name="actions">
            @if(hasPermission('notices.create', $slugs))
                <a href="{{ route('notices.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> Add New
                </a>
            @endif
        </x-slot>

        <x-mm.panel class="tw-p-4">
            <x-mm.data-table :columns="[
                ['label' => 'Sl'],
                ['label' => 'Date'],
                ['label' => 'Company'],
                ['label' => 'Title'],
                ['label' => 'Action', 'width' => '130px', 'align' => 'center'],
            ]" table-class="table table-bordered table-striped" label="Notice List">
                @forelse($notices as $key => $notice)
                    <tr class="text-{{ $notice->is_view == 1 ? ''  : 'primary' }}">
                        <td>{{ $key + $notices->firstItem() }}  </td>
                        <td>{{ fdate($notice->publish_at) }}</td>
                        <td>{{ optional($notice->company)->name }}</td>
                        <td>{{ $notice->title }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                <span class="btn btn-info btn-xs popover-success"
                                      data-rel="popover"
                                      data-placement="top"
                                      data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Log Information"
                                      data-content="<p>Created By: {{ optional($notice->created_user)->name }}.</p> <p> Created At : {{ $notice->created_at }} </p>
                                       <hr/>
                                       <p>Approved By: {{ optional($notice->approved_user)->name }}.</p> <p> Updated At : {{ $notice->update_at }} </p>">
                                    <i class="fa fa-info-circle"></i>
                                </span>

                                <a href="{{ route('notices.show', $notice->id) }}" target="__blank" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>

                                @if(hasPermission('notices.edit', $slugs))
                                    <a href="{{ route('notices.edit', $notice->id) }}" class="btn btn-xs btn-blue"><i class="fa fa-edit"></i></a>
                                @endif

                                @if(hasPermission('notices.delete', $slugs))
                                    <button class="btn btn-xs btn-danger" onclick="delete_item('{{ route('notices.destroy', $notice->id) }}')" type="button">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center text-danger">No records found!</td>
                    </tr>
                @endforelse
            </x-mm.data-table>
            @include('partials._paginate', ['data' => $notices])
        </x-mm.panel>
    </x-mm.page>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>
@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <!--  User log popover -->
    <script type="text/javascript">
        $('[data-rel=popover]').popover({ html:true, container:'body' });
    </script>

    <!-- delete confirm dialog -->
    <script type="text/javascript" src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

    <!--  Select Box Search-->
    <script type="text/javascript" src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <!--  date Picker-->
    <script type="text/javascript" src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
@endsection
