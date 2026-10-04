{{--
    Shared public-site header (social bar + nav menu + cart).
    Used by both shells (legacy + mm-web).
    The legacy `frontend.layouts.master` previously inlined this exact block;
    extracting it keeps the two shells visually identical.
--}}

<?php $web_info = websiteInfo() ?>

<!---------- WEBSITE INFO ---------->

<!------------- HEADER ------------->
<div class="banner-top">
    <div class="social-bnr-agileits">
        <ul class="social-icons3">
            <li><a href="{{ $web_info->facebook_url }}" class="fa fa-facebook icon-border facebook"> </a></li>
            <li><a href="{{ $web_info->twitter_url }}" class="fa fa-instagram icon-border instagram"> </a></li>
            <li><a href="{{ $web_info->linkedin_url }}" class="tiktok"><img src="{{ asset('frontend/assets/images/tiktok.svg') }}" /> </a></li>
        </ul>
    </div>
    <div class="contact-bnr-w3-agile">
        <ul>
            <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:{{ $web_info->email }}">{{ $web_info->email }}</a></li>
            <li><i class="fa fa-phone" aria-hidden="true"></i>{{ $web_info->phone_no }}</li>

            {{-- Cart Section --}}
            @include('frontend.layouts.includes.cart')
        </ul>
    </div>
    <div class="clearfix"></div>
</div>


<!-------- NAVIGATION MENU -------->
<div class="w3_navigation">
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header navbar-left">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <h1>
                    <a class="navbar-brand" href="{{ route('home.page') }}">{{ $web_info->site_first_name }} <span>{{ $web_info->site_last_name }}</span>
                    <p class="logo_w3l_agile_caption">{{ $web_info->site_slogan }}</p></a>
                </h1>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <nav class="menu menu--iris">
                    <ul class="nav navbar-nav menu__list">

                        <li class="menu__item @yield('menu_home')"><a href="{{ route('home.page') }}" class="menu__link">Home</a></li>

                        <li class="menu__item ">
                            <a href="#about" class="menu__link scroll dropdown-toggle drop-about">About</a>
                            <ul class="submenu_item about-item">
                                <li><a class="menu__link" href="{{ route('privacy.policy') }}">Privacy & Policy</a></li>
                                <li><a class="menu__link" href="{{ route('terms.condition') }}">Terms & Condition</a></li>
                            </ul>
                        </li>

                        <li class="menu__item"><a href="#gallery" class="menu__link scroll">Gallery</a></li>
                        <li class="menu__item"><a href="#rooms" class="menu__link scroll">Rooms</a></li>
                        <li class="menu__item"><a href="#contact" class="menu__link scroll">Contact Us</a></li>

                        <li class="menu__item ">
                            <a href="javascript:void(0)" class="menu__link scroll dropdown-toggle drop-rest">Restaurant</a>
                            @if(pages()->count() > 0)
                            <ul class="submenu_item rest-item">
                                @foreach (pages() as $item)
                                <li><a class="menu__link" href="{{ route('pages.single', $item->slug) }}">{{ $item->title }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </li>

                    </ul>
                </nav>
            </div>
        </nav>
    </div>
</div>


<!------------- HOTEL SETTING MODAL ------->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <!-- Modal1 -->
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Hotel<span>Setting</span></h4>
                <img src="{{ asset('frontend/assets/images/1.jpg') }}" alt=" " class="img-responsive">
                <h5>We know what you love</h5>
                <p>Providing guests unique and enchanting views from their rooms with its exceptional amenities, makes Star Hotel one of bests in its kind.Try our food menu, awesome services and friendly staff while you are here.</p>
            </div>
        </div>
    </div>
</div>


@stack('public_nav')