@extends('frontend.layouts.mm-web')

@push('custom_css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/venobox.min.css') }}">
    <style>
        .aminity-icon {
            font-size: 5em;
            color: #803d98;
        }

        .cbp-ig-grid li>.w3_grid_effect:hover .aminity-icon {
            color: #ffce14;
        }

        .price-gd-top img {
            height: 255px !important;
        }

        #ui-datepicker-div {
            z-index: 99999999 !important;
        }

        .booking-details {

            background-color: #803d98;
            color: #fff;
            text-align: center;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .booking-details label {
            margin-bottom: 5px;
            font-weight: 300;
        }

        .booking-details input {
            color: #fff;
            text-align: center;
            background-color: transparent;
            border: 1px solid #fff;
            padding: 3px;
        }

        .btn-book {
            background-color: #ffce14;
            color: #000;
            padding: 10px 25px;
            font-size: 18px;
        }

        .book-submit {
            margin-top: 10px
        }
    </style>
@endpush

@section('menu_home')
    menu__item--current
@endsection

@section('homepage_banner')
    @include('frontend.layouts.includes.banner')
@endsection

@section('search_bar')
    @include('frontend.layouts.includes.search_bar')
@endsection

@section('frontend-content')

    <!-- banner-bottom -->
    <div class="banner-bottom">
        <div class="container">
            <!---728x90--->

            <div class="agileits_banner_bottom">
                <h3><span>{{ $feature_head->title }}</span>{{ $feature_head->sub_title }}</h3>
            </div>
            <!---728x90--->

            <div class="w3ls_banner_bottom_grids">
                <ul class="cbp-ig-grid">
                    @foreach ($feature_list as $item)
                        <li>
                            <div class="w3_grid_effect">
                                <span class="cbp-ig-icon">
                                    <i class="{{ $item->feature_icon }} aminity-icon"></i>
                                </span>
                                <h4 class="cbp-ig-title">{{ $item->title }}</h4>
                                <span class="cbp-ig-category">{{ $item->sub_title }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!---728x90--->
        </div>
    </div>



    <!-- /about -->
    <div class="about-wthree" id="about">
        <div class="container">
            <div class="ab-w3l-spa">
                <h3 class="title-w3-agileits title-black-wthree">{{ $about->about_heading }}</h3>
                <p class="about-para-w3ls">{{ $about->about_description }}</p>
                <img src="{{ asset($about->first_image) }}" class="img-responsive about-first-image" alt="Hair Salon">
                <div class="w3l-slider-img">
                    <img src="{{ asset($about->second_image) }}" class="img-responsive" alt="Hair Salon">
                </div>
                <div class="w3ls-info-about">
                    <h4>{{ $about->offer_title }}</h4>
                    <p>{{ $about->offer_description }}</p>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>



    <!-- rooms & rates -->
    <div class="plans-section" id="rooms">
        <div class="container">
            <h3 class="title-w3-agileits title-black-wthree">Rooms And Rates</h3>
            <div class="priceing-table-main">

                @if (isset($room_category))
                    @foreach ($room_category as $category)
                        <div class="col-md-4 price-grid">
                            <div class="price-block agile">
                                <div class="price-gd-top">
                                    <a href="{{ $category->url_slug ? route('view.room', $category->url_slug) : '#' }}">
                                        @if (file_exists(optional($category->roomSingleImg)->relative_path . optional($category->roomSingleImg)->name))
                                            <img src="{{ asset(optional($category->roomSingleImg)->relative_path . optional($category->roomSingleImg)->name) }}"
                                                alt="{{ optional($category->roomSingleImg)->name }}"
                                                class="img-responsive" />
                                        @else
                                            <img src="{{ asset('frontend/assets/images/1.jpg') }}" alt=" "
                                                class="img-responsive">
                                        @endif
                                        <h4>{{ $category->name }}</h4>
                                    </a>
                                </div>


                                {{-- <div class="price-gd-bottom">

                                    <div class="price-list">
                                        <ul class="short-aminities">
                                            <li>Sleeps {{ $category->can_sleep ?? 'N/A' }} -</li>
                                            <li>{{ $category->room_sqft ?? 'N/A' }} sqft -</li>
                                            <li>{{ $category->bed_details ?? 'N/A' }}</li>
                                        </ul>
                                    </div>

                                    <div class="price-selet">
                                        <h3>
                                            <span>{{ setting('root_currency') == 96 ? 'RM' : '$' }}</span> {{ calculateCurrencyAmount($category->price) }}
                                        </h3>
                                        <a href="#availability-agileits" data-toggle="modal"
                                            data-target=".booking_process{{ $category->id }}">Book Now</a>
                                    </div>
                                </div> --}}
                            </div>
                        </div>


                        <div class="modal fade booking_process{{ $category->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="roomCategory">{{ $category->name }}</h4>
                                        @if (file_exists(optional($category->roomSingleImg)->relative_path . optional($category->roomSingleImg)->name))
                                            <img src="{{ asset(optional($category->roomSingleImg)->relative_path . optional($category->roomSingleImg)->name) }}"
                                                alt="{{ optional($category->roomSingleImg)->name }}" class="img-responsive"
                                                style="width: 538px; height: 250px;" />
                                        @else
                                            <img src="{{ asset('frontend/assets/images/1.jpg') }}" alt=" "
                                                class="img-responsive" style="width: 538px; height: 250px;">
                                        @endif

                                        <div class="booking-details">

                                            <form action="{{ route('guest-registration') }}" method="GET"
                                                class="available-room-form">
                                                @csrf

                                                <input type="hidden" name="room_id" class="room-id" value="">
                                                <input type="hidden" name="room_category" class="room-category"
                                                    value="">
                                                <input type="hidden" name="check_in" class="check-in" value="">
                                                <input type="hidden" name="check_out" class="check-out" value="">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                            style="display: flex; flex-direction: column; padding: 0 20px;">
                                                            <label for="">Arrival Date</label>
                                                            <input class="arrivalDate{{ $category->id }}" type="date"
                                                                value="{{ request('check_in') ?? '' }}"
                                                                style="padding: 0 5px;" placeholder="Select A Date"
                                                                autocomplete="off">
                                                            {{-- <input name="check_in" class="arrivalDate" type="date" value="{{ request('check_in') ?? '' }}" style="padding: 0 5px;" placeholder="Select A Date" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '';}" required="" autocomplete="off"> --}}
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group"
                                                            style="display: flex; flex-direction: column; padding: 0 20px;">
                                                            <label for="">Depature Date</label>
                                                            <input class="depatureDate{{ $category->id }}" type="date"
                                                                value="{{ request('check_out') ?? '' }}"
                                                                style="padding: 0 5px;" placeholder="Select A Date"
                                                                autocomplete="off">
                                                            {{-- <input name="check_out" class="depatureDate" type="date" value="{{ request('check_out') ?? '' }}" style="padding: 0 5px;" placeholder="Select A Date" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '';}" required="" autocomplete="off"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <div class="book-submit mt-2">
                                                <button type="button" class="btn-book"
                                                    onclick="submitAvaialbelRoom({{ $category->id }})">Book Now</button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="clearfix"> </div>
            </div>
        </div>
    </div>




    <!--sevices-->
    <div class="advantages" style="background-image: url({{ asset($service->service_background_img) }})">
        <div class="container">
            <div class="advantages-main">
                <h3 class="title-w3-agileits">{{ $service->service_heading }}</h3>
                <div class="advantage-bottom">


                    @foreach ($service_list as $key => $item)
                        <div class="col-md-6 advantage-grid left-w3ls wow bounceInLeft {{ $key != 0 ? 'border-right-0' : '' }}"
                            data-wow-delay="0.3s">

                            @php
                                $listing = explode(',', $item->service_list);
                            @endphp

                            <div class="advantage-block ">
                                <i class="fa {{ $item->service_icon }}" aria-hidden="true"></i>
                                <h4 class="services-title">{{ $item->service_title }}</h4>
                                <p class="services-description">{{ $item->service_description }}</p>

                                @foreach ($listing as $list)
                                    <p><i class="fa fa-check"></i>{{ $list }}</p>
                                @endforeach

                                {{-- <p><i class="fa fa-check" aria-hidden="true"></i>Private balcony</p> --}}

                            </div>

                        </div>
                    @endforeach
                    <div class="clearfix"> </div>
                </div>
            </div>
        </div>
    </div>





    <!-- OLD GALLERY -->
    {{-- <section class="portfolio-w3ls" id="gallery">
        <h3 class="title-w3-agileits title-black-wthree">Our Gallery</h3>

        @foreach ($gallery as $galleries)
            <div class="col-md-3 gallery-grid gallery1">
                <a href="{{ asset($galleries->name) }}" class="swipebox">
                    <img src="{{ asset($galleries->name) }}" class="img-responsive gallery-image" alt="{{ $galleries->gallery_text }}" width="100%" height="100%">
                    <div class="textbox">
                    <h4>{{ $galleries->gallery_text }}</h4>
                        <p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
                    </div>
                </a>
            </div>
        @endforeach

        <div class="clearfix"> </div>
    </section> --}}



    <!-- NEW GALLERY WITH VENOBOX -->
    <div class="container">
        <section class="portfolio-w3ls" id="gallery">
    
            <h3 class="title-w3-agileits title-black-wthree">Our Gallery</h3>
    
            <div class="row" style="margin: 0">
                @foreach ($gallery as $key => $galleries)
                    <div class="col-md-3 gallery-grid gallery1">
                        <a class="venobox" data-gall="gallery01" href="{{ asset($galleries->name) }}"
                            alt="{{ $galleries->gallery_text }}" width="100%" height="100%">
                            <img src="{{ asset($galleries->name) }}" class="gallery-image img-responsive">
    
                            <div class="textbox">
                                <h4>{{ $galleries->gallery_text }}</h4>
                                <p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
    
        </section>
    </div>




    <!-- visitors -->
    {{-- <div class="w3l-visitors-agile" >
        <div class="container">
                    <h3 class="title-w3-agileits title-black-wthree">What other visitors experienced</h3>
        </div>
        <div class="w3layouts_work_grids">
            <section class="slider">
                <div class="flexslider">
                    <ul class="slides">
                        <li>
                            <div class="w3layouts_work_grid_left">
                                <img src="{{ asset('frontend/assets/images/5.jpg') }}" alt=" " class="img-responsive" />
                                <div class="w3layouts_work_grid_left_pos">
                                    <img src="{{ asset('frontend/assets/images/c1.jpg') }}" alt=" " class="img-responsive" />
                                </div>
                            </div>
                            <div class="w3layouts_work_grid_right">
                                <h4>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                Worth to come again
                                </h4>
                                <p>Sed tempus vestibulum lacus blandit faucibus.
                                    Nunc imperdiet, diam nec rhoncus ullamcorper, nisl nulla suscipit ligula,
                                    at imperdiet urna. </p>
                                <h5>Julia Rose</h5>
                                <p>Germany</p>
                            </div>
                            <div class="clearfix"> </div>
                        </li>
                        <li>
                            <div class="w3layouts_work_grid_left">
                                <img src="{{ asset('frontend/assets/images/5.jpg') }}" alt=" " class="img-responsive" />
                                <div class="w3layouts_work_grid_left_pos">
                                    <img src="{{ asset('frontend/assets/images/c2.jpg') }}" alt=" " class="img-responsive" />
                                </div>
                            </div>
                            <div class="w3layouts_work_grid_right">
                                <h4>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star-o" aria-hidden="true"></i>
                                Worth to come again
                                </h4>
                                <p>Sed tempus vestibulum lacus blandit faucibus.
                                    Nunc imperdiet, diam nec rhoncus ullamcorper, nisl nulla suscipit ligula,
                                    at imperdiet urna. </p>
                                <h5>Jahnatan Smith</h5>
                                <p>United States</p>
                            </div>
                            <div class="clearfix"> </div>
                        </li>
                        <li>
                            <div class="w3layouts_work_grid_left">
                                <img src="{{ asset('frontend/assets/images/5.jpg') }}" alt=" " class="img-responsive" />
                                <div class="w3layouts_work_grid_left_pos">
                                    <img src="{{ asset('frontend/assets/images/c3.jpg') }}" alt=" " class="img-responsive" />
                                </div>
                            </div>
                            <div class="w3layouts_work_grid_right">
                                <h4>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star-o" aria-hidden="true"></i>
                                Worth to come again
                                </h4>
                                <p>Sed tempus vestibulum lacus blandit faucibus.
                                    Nunc imperdiet, diam nec rhoncus ullamcorper, nisl nulla suscipit ligula,
                                    at imperdiet urna. </p>
                                <h5>Rosalind Cloer</h5>
                                <p>Italy</p>
                            </div>
                            <div class="clearfix"> </div>
                        </li>
                        <li>
                            <div class="w3layouts_work_grid_left">
                                <img src="{{ asset('frontend/assets/images/5.jpg') }}" alt=" " class="img-responsive" />
                                <div class="w3layouts_work_grid_left_pos">
                                    <img src="{{ asset('frontend/assets/images/c4.jpg') }}" alt=" " class="img-responsive" />
                                </div>
                            </div>
                            <div class="w3layouts_work_grid_right">
                                <h4>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star-o" aria-hidden="true"></i>
                                <i class="fa fa-star-o" aria-hidden="true"></i>
                                Worth to come again
                                </h4>
                                <p>Sed tempus vestibulum lacus blandit faucibus.
                                    Nunc imperdiet, diam nec rhoncus ullamcorper, nisl nulla suscipit ligula,
                                    at imperdiet urna. </p>
                                <h5>Amie Bublitz</h5>
                                <p>Switzerland</p>
                            </div>
                            <div class="clearfix"> </div>
                        </li>
                    </ul>
                </div>
            </section>
        </div>
    </div> --}}




@endsection


@section('forntend_script')
    @include('frontend.layouts.includes.frontend-booking-script')

    <script src="{{ asset('frontend/assets/js/venobox.min.js') }}"></script>
    <script>
        new VenoBox({
            selector: '.venobox',
            numeration: true,
            infinigall: true,
            share: true,
            spinner: 'wave'
        });
    </script>
@endsection
