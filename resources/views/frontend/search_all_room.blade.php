@extends('frontend.layouts.mm-web')


@section('website_header') All Category |@endsection


@push('custom_css')

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">

    <style>
        .slider-img img{width: 100%}

        .guest_capacity {

            background-color: #fff;
            border: 1px solid #d1b85a;
            height: 40px;
            text-align: center;
        }
        .guest_capacity:focus{
            border: 1px solid #d1b85a
        }
        .input-box{margin-bottom: 10px}
        .input-box span{
            background-color: #d1b85a;
            padding: 11px 29px;
            color: #fff;
            margin-left: -4px;
        }

    </style>

    <style>
        .aminity-icon {
            font-size: 5em;
            color: #803d98;
        }

        .cbp-ig-grid li > .w3_grid_effect:hover .aminity-icon {
            color: #ffce14;
        }

        .price-gd-top img{height: 255px !important;}
        #ui-datepicker-div{z-index: 99999999 !important;}
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

        .book-submit{
            margin-top: 10px
        }
    </style>

    <style>
        .category-slider .category-img{

        }
    </style>

@endpush


@section('search_bar')
    @include('frontend.layouts.includes.search_bar')
@endsection


@section('frontend-content')

    <section class="category-body">
        @foreach ($room_categories as $category)
            <div class="container-fluid search-room-fluid all-category">

                <div class="col-md-7">
                    @php
                        $img_count = $category->roomMultipleImg->count();
                    @endphp
                    @if($img_count > 1)
                        <div class="category-slider slider-active">
                            @foreach ($category->roomMultipleImg as $image)
                                <div class="slider-img">
                                    <img class="img-fluid category-img" src="{{ asset($image->relative_path.$image->name) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    @elseif ($img_count == 1)
                        @if (file_exists(optional($category->roomSingleImg)->relative_path.optional($category->roomSingleImg)->name))
                            <img src="{{ asset(optional($category->roomSingleImg)->relative_path.optional($category->roomSingleImg)->name) }}" alt="{{ optional($category->roomSingleImg)->name }}" class="img-responsive category-img" style="width: 100%" />
                        @else
                            <img src="{{ asset('frontend/assets/images/1.jpg') }}" alt="{{ optional($category->roomSingleImg)->name }}" class="img-responsive category-img" style="width: 100%;" />
                        @endif
                    @else
                        <div class="category-slider">
                            <div class="slider-img">
                                <img class="img-fluid category-img" src="{{ asset('frontend/assets/images/1.jpg') }}" alt="">
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-5">
                    <div class="category-detail">
                        <h2 class="cat-title"><span class="border-sep"></span>{{ $category->name }}</h2>
                        <div class="cat-price" style="margin-top: 10px">
                            <h3 class="cat-room-price"><span>{{ setting('root_currency') == 96 ? 'RM' : '৳' }} </span>{{ calculateCurrencyAmount($category->price) }}</h3>
                        </div>
                        <div class="cat-desc" style="{{ $category->description == null ? 'display:none' : '' }}">
                            <p>{{ $category->description }}</p>
                        </div>

                        <div class="category-aminities">
                            <ul class="aminity-list">
                                @foreach (roomAminities($category->id) as $key => $item)
                                    <li>
                                        @if ($item->aminities_icon != null)
                                            <div class="aminity-img">
                                                <img class="img-fluid" src="{{ asset($item->aminities_icon) }}" alt="">
                                            </div>
                                        @endif
                                        <p>{{ $item->name }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>


                        <div class="cat-booking" style="margin-top: 20px">
                            {{-- <div class="input-box">
                                <input type="number" name="total_guest" value="1" min="1"  class="guest_capacity">
                                <span>Guest</span>
                            </div> --}}

                            <button class="btn btn-cart btn-animate" onclick="location.href = '{{ $category->url_slug ? route('view.room',$category->url_slug) : '#' }}'">View More</button>
                            <button type="button" class="btn btn-booking btn-animate" data-toggle="modal" data-target=".booking_process{{ $category->id }}">Book Now</button>
                        </div>
                    </div>

                </div>

            </div>

            <!-------- MODAL -------->
            <div class="modal fade booking_process{{ $category->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">

                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="roomCategory">{{ $category->name }}</h4>

                            @if (file_exists(optional($category->roomSingleImg)->relative_path.optional($category->roomSingleImg)->name))
                                <img src="{{ asset(optional($category->roomSingleImg)->relative_path.optional($category->roomSingleImg)->name) }}" alt="{{ optional($category->roomSingleImg)->name }}" class="img-responsive category-img" style="width: 538px; height: 250px;" />
                            @else
                                <img src="{{ asset('frontend/assets/images/1.jpg') }}" alt=" " class="img-responsive category-img" style="width: 538px; height: 250px;">
                            @endif

                            <div class="booking-details">

                                <form action="{{ route('guest-registration') }}" method="GET" class="available-room-form">
                                    @csrf

                                    <input type="hidden" name="room_id" class="room-id" value="">
                                    <input type="hidden" name="room_category" class="room-category" value="">
                                    <input type="hidden" name="check_in" class="check-in" value="">
                                    <input type="hidden" name="check_out" class="check-out" value="">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group" style="display: flex; flex-direction: column; padding: 0 20px;">
                                                <label for="">Arrival Date</label>
                                                <input class="arrivalDate{{ $category->id }}" type="date" value="{{ request('check_in') ?? '' }}" style="padding: 0 5px;" placeholder="Select A Date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group" style="display: flex; flex-direction: column; padding: 0 20px;">
                                                <label for="">Depature Date</label>
                                                <input class="depatureDate{{ $category->id }}" type="date" value="{{ request('check_out') ?? '' }}" style="padding: 0 5px;" placeholder="Select A Date" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="book-submit mt-2">
                                    <button type="button" class="btn-book" onclick="submitAvaialbelRoom({{ $category->id }})">Book Now</button>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </section>

@endsection


@push('custom_js')

    @include('frontend.layouts.includes.frontend-booking-script')

    <script type="text/javascript" src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(".slider-active").owlCarousel({
                nav: true,
                dots: false,
                autoplay: false,
                loop: true,
                margin: 10,
                navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
                responsive:{
                    0:{
                        items:1
                    },
                    600:{
                        items:1
                    },
                    1000:{
                        items:1
                    }
                }

            });
        });
    </script>
@endpush
