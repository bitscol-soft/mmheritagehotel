@extends('frontend.layouts.mm-web')

@section('website_header') Restaurant Food Menu |@endsection

@push('custom_css')
    <link href="{{ asset('food_menu/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('food_menu/css/responsive.css') }}" rel="stylesheet" />
@endpush

@section('frontend-content')
    <section class="food_section layout_padding">
        <div class="container">
            <div class="heading_container heading_center">
                <h2>
                    Our Menu
                </h2>
            </div>

            <ul class="filters_menu">
                <li class="active" data-filter="*">All</li>
                @foreach ($categories as $category)
                    <li data-filter=".{{ $category->name }}">{{ Str::upper($category->name) }}</li>
                @endforeach
            </ul>

            <div class="filters-content">
                <div class="row grid">
                    @foreach ($products as $product)
                        <div class="col-sm-6 col-lg-3 all {{ optional($product->category)->name }}">
                            <div class="box">
                                <div>
                                    <div class="img-box">
                                        <img src="https://game-icons.net/icons/000000/transparent/1x1/delapouite/hot-meal.png"
                                            alt="{{ $product->name }}" loading="lazy">
                                    </div>
                                    <div class="detail-box">
                                        <h5>
                                            {{ $product->name }}
                                        </h5>
                                        <p>
                                            {{ optional($product->category)->name }}
                                        </p>
                                        <div class="options">
                                            <h6>
                                                {{ $product->sale_price }} ৳
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@push('custom_js')
    <script src="https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js"></script>
    <script src="{{ asset('food_menu/js/custom.js') }}"></script>
@endpush
