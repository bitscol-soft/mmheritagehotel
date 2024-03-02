<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

    <!-- Header Section -->
    @include('frontend.layouts.includes.header')

    <style>
        .social-icons3 .youtube {background-color: #FF0000;}
        .social-icons3 .linkedin {background-color: #0077b5;}
        .menu__item .submenu_item {
            list-style: none;
            background-color: #803d98;
            min-height: 100px;
            min-width: 200px;
            padding: 10px;
            padding-top: 25px;
            position: absolute;
            z-index: 99;
            top: 20px;
            left: 10px;
            color: #fff;
            display: none;
        }
        .submenu_item li {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .submenu_item .menu__link {
            color: #fff;
            padding: 8px;
            font-size: 18px;
            text-align: center;
            font-weight: 300;
        }
        .submenu_item .menu__link:hover{color: #ffce14}
    </style>

    <script type="text/javascript" src="https://www.ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script type="text/javascript" src="https://www.m.servedby-buysellads.com/monetization.js" ></script>

    <script>
        (function(){
            if(typeof _bsa !== 'undefined' && _bsa) {
                // format, zoneKey, segment:value, options
                _bsa.init('flexbar', 'CKYI627U', 'placement:w3layoutscom');
            }
        })();
    </script>

    <script>
        (function(){
        if(typeof _bsa !== 'undefined' && _bsa) {
            // format, zoneKey, segment:value, options
            _bsa.init('fancybar', 'CKYDL2JN', 'placement:demo');
        }
        })();
    </script>

    <script>
        (function(){
            if(typeof _bsa !== 'undefined' && _bsa) {
                // format, zoneKey, segment:value, options
                _bsa.init('stickybox', 'CKYI653J', 'placement:w3layoutscom');
            }
        })();
    </script>

    <script>
        (function(v,d,o,ai){ai=d.createElement("script");ai.defer=true;ai.async=true;ai.src=v.location.protocol+o;d.head.appendChild(ai);})(window, document, "../../../../../../../vdo.ai/core/w3layouts/vdo.ai.js");
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125810435-1"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-125810435-1');
    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','../../../../../../../www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-30027142-1', 'w3layouts.com');
        ga('send', 'pageview');
    </script>

    <!-- sweatalert2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.min.css') }}">

</head>
<body>

    <!---------- WEBSITE INFO ---------->
    <?php $web_info = websiteInfo() ?>


    <!------------- HEADER ------------->
    <div class="banner-top">
        <div class="social-bnr-agileits">
            <ul class="social-icons3">
                <li><a href="{{ $web_info->facebook_url }}" class="fa fa-facebook icon-border facebook"> </a></li>
                <li><a href="{{ $web_info->twitter_url }}" class="fa fa-instagram icon-border instagram"> </a></li>
                <!-- <li><a href="{{ $web_info->twitter_url }}" class="fa fa-twitter icon-border twitter"> </a></li> -->
                <li><a href="{{ $web_info->linkedin_url }}" class="tiktok"><img src="{{ asset('frontend/assets/images/tiktok.svg') }}" /> </a></li>
                <!-- <li><a href="{{ $web_info->youtube_url }}" class="fa fa-youtube-play icon-border youtube"> </a></li> -->
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

                            {{-- <li class="menu__item"><a href="#team" class="menu__link scroll">Team</a></li> --}}
                            <li class="menu__item"><a href="#gallery" class="menu__link scroll">Gallery</a></li>
                            <li class="menu__item"><a href="#rooms" class="menu__link scroll">Rooms</a></li>
                            <li class="menu__item"><a href="#contact" class="menu__link scroll">Contact Us</a></li>
                            {{-- <li><a href="">Restaurant</a></li> --}}

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



    <!------------- BANNER ------------->
    @yield('homepage_banner')



    <!------- HOTEL SETTING MODAL ------->
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



    <!---------- SEARCH BAR AREA ---------->
    @yield('search_bar')




    <!---- YIELD FRONTEND CONTENT AREA ---->
    @yield('frontend-content')




    <!------------- CONTACT US ------------>
    <section class="contact-w3ls" id="contact" style="background:url({{ companyInfo() != null ? asset('uploads/company/extra/'.companyInfo()) : '' }}) no-repeat; background-position:center; background-attachment:fixed; background-size:100% 100%;">
        <div class="container" style="background-color: rgba(0, 0, 0, 0.55);">
            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6 contact-w3-agile2" data-aos="flip-left">
                    <div class="contact-agileits">
                        <h4>Contact Us</h4>
                        <p class="contact-agile2">Sign Up For Our News Letters</p>
                        <form action="#" method="post" name="sentMessage" id="contactForm" novalidate>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <label class="contact-p1">Full Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <label class="contact-p1">Phone Number:</label>
                                    <input type="tel" class="form-control" name="phone" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <label class="contact-p1">Email Address:</label>
                                    <input type="email" class="form-control" name="email" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div id="success"></div>
                            <!-- For success/fail messages -->
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 contact-w3-agile1" data-aos="flip-right">
                    <h4>Connect With Us</h4>
                    <p class="contact-agile1"><strong>Phone :</strong>{{ $web_info->phone_no }}</p>
                    <p class="contact-agile1"><strong>Email :</strong> <a href="hello@hotelsetting.com">{{ $web_info->email }}</a></p>
                    <p class="contact-agile1"><strong>Address :</strong>{{ $web_info->address }}</p>

                    <div class="social-bnr-agileits footer-icons-agileinfo">
                        <ul class="social-icons3">
                            <li><a href="{{ $web_info->facebook_url }}" class="fa fa-facebook icon-border facebook"> </a></li>
                            <li><a href="{{ $web_info->twitter_url }}" class="fa fa-instagram icon-border instagram"> </a></li>
                            <!-- <li><a href="{{ $web_info->twitter_url }}" class="fa fa-twitter icon-border twitter"> </a></li> -->
                            <li><a href="{{ $web_info->linkedin_url }}" class="tiktok"><img src="{{ asset('frontend/assets/images/tiktok.svg') }}" /> </a></li>
                            <!-- <li><a href="{{ $web_info->youtube_url }}" class="fa fa-youtube-play icon-border youtube"> </a></li> -->
                        </ul>
                    </div>

                    <div class="google-map">
                        {!! $web_info->location_map !!}
                    </div>

                </div>

            </div>
            <div class="clearfix"></div>
        </div>
    </section>



    <!------------- COPYRIGHT ------------->
    <div class="copy">
        <p>© {{ date('Y') }} {{ $web_info->site_first_name }} {{ $web_info->site_last_name }} . All Rights Reserved | Developed by <a href="https://www.banglafire.com" target="_blank">Banglafire Software Ltd.</a> </p>
    </div>



    <!---------- INCLUDE JS AREA ---------->
    @include('frontend.layouts.includes.js')



    <!---------- YIELD JS AREA ---------->
    @yield('forntend_script')



    <!------------ SCRIPT JS ------------>
    <script>
        $('.dropdown-toggle').hover(

            // function () {

            //     $('.submenu_item').show();
            // },

            // function () {

            //     $('.submenu_item').hide();

            // }
        );


        $('.submenu_item').hover(
            function () {
                $(this).show();
            },
            function () {
                $(this).hide();
            }
        );



    </script>
    <script>
        $('.drop-rest').hover(
            function () {
                $('.rest-item').show();
            },
            function () {
                $('.rest-item').hide();
            }
        );

        $('.drop-about').hover(
            function () {
                $('.about-item').show();
            },
            function () {
                $('.about-item').hide();
            }
        );

    </script>


</body>
</html>
