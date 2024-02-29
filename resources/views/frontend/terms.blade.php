@extends('frontend.layouts.master')

@section('menu_about')menu__item--current @endsection
@section('frontend-content')
    <div class="service-page" style="margin-top: 50px; margin-bottom: 100px;">
        <div class="container">
            <h1 class="text-center">{{ $data != null ? $data->terms_header_title : '' }}</h1>

            <div class="service-body" style="margin-top: 50px">
                {!! $data != null ? $data->terms_condition : '' !!}
            </div>
        </div>

    </div>
@endsection
