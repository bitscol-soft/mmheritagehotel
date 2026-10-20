@extends('layouts.master')
@section('title','Registration Terms List')

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Registration terms" description="Terms shown on the guest registration form.">
    <x-slot name="actions"><a class="mm-button" href="{{ route('guest-registration-terms.create') }}"><i class="fa fa-plus"></i> Add New Registration Term</a></x-slot>
    @include('partials._alert_message')

    @include('guest-registration-terms.include.filter')

    <x-mm.panel>
        <x-mm.table-scroll label="Registration terms">
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
                                    <a class="mm-button" href="{{ route('guest-registration-terms.edit', $item->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('guest-registration-terms.destroy', $item->id) }}" method="POST" style="display:inline" onsubmit="return confirm('You want to delete permanently !')">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="btn btn-xs btn-sm btn-danger" title="Delete" aria-label="Delete registration term">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-mm.table-scroll>
    </x-mm.panel>
</x-mm.page>
@endsection
