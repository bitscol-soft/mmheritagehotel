@extends('frontend.layouts.mm-web')

@section('menu_about', $page->title ?? '')

@section('frontend-content')

    <div class="service-page" style="margin-top: 50px; margin-bottom: 100px;">
        <div class="container">
            <h1 class="text-center">{{ $page->title }}</h1>

            <div class="service-body" style="margin-top: 50px">
                @if (file_exists($page->image))
                <div class="col-md-6">
                    <img src="{{ asset($page->image) }}" style="width: 100%; height: 400px;" alt="{{ $page->title }}">
                </div>
                @endif
                <div class="@if (file_exists($page->image)) col-md-6 @else col-md-12 @endif ">
                    {!! $page->short_description !!}
                </div>
                <div class="col-md-12">
                    <br>
                    {!! $page->description !!}
                </div>
            </div>
        </div>

    </div>
@endsection
