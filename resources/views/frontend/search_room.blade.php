@extends('frontend.layouts.mm-web')

@push('custom_css')

<link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
<style>
    .slider-img img{width: 100%}
</style>
@endpush

@section('search_bar')
    @include('frontend.layouts.includes.search_bar')
@endsection

@section('frontend-content')

{{-- Category Section --}}

<section class="category-body">
    <div class="container-fluid search-room-fluid">
        <div class="col-md-7">
            @php
                $img_count = $category->roomMultipleImg->count();
             @endphp
            <div class="category-slider  @if($img_count > 1) slider-active @endif">
                @foreach ($category->roomMultipleImg as $image)
                    <div class="slider-img">
                        <img class="img-fluid" src="{{ asset($image->relative_path.$image->name) }}" alt="" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-5">
            <div class="category-detail mt-50">
                <h2 class="cat-title"><span class="border-sep"></span>{{ $category->name }}</h2>
                <div class="cat-price" style="margin-top: 10px">
                    <span>{{ setting('root_currency') == 96 ? 'RM' : '৳' }}</span>{{ calculateCurrencyAmount($category->price) }}
                </div>
                <div class="cat-desc" style="{{ $category->description == null ? 'display:none' : '' }}">
                    <p>{{ $category->description }}</p>
                </div>
                <div class="cat-booking mt-50" style="display: flex">

                    @if ($totalAvailableRoom > 0)
                        <form action="{{ route('guest-registration') }}" method="GET">
                            @csrf
                            <input type="hidden" name="room_category" value="{{ $category->id }}">
                            <input type="hidden" name="room_id" value="{{ $room_id }}">
                            <input type="hidden" name="check_in" value="{{ $check_in }}">
                            <input type="hidden" name="check_out" value="{{ $check_out }}">

                            <button type="submit" class="btn btn-booking btn-animate">Book Now</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-booking btn-animate no-available-book">Book Now</button>
                    @endif

                    <button  class="btn btn-cart btn-animate" id="add-to-cart" style="margin-left: 20px">Add to Cart</button>

                </div>
            </div>
            <div class="category-aminities">
                <ul class="aminity-list">
                    @foreach ($aminities ?? [] as $key => $item)
                        <li>
                            @if ( $item != null && $item->aminities_icon != null)
                                <div class="aminity-img">
                                    <img class="img-fluid" src="{{ asset($item->aminities_icon) }}" alt="" loading="lazy">
                                </div>
                            @endif
                            <p>{{ $item != null ? $item->name : ''}}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

@endsection

@push('custom_js')
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


            $('.no-available-book').click(function(){
                Swal.fire({
                    type: 'error',
                    title: '<h4>There is no room avalibale in this category! Please select another Category</h4>',
                    timer: 2000,
                    showConfirmButton: false
                })
            })


        });
    </script>
@endpush
