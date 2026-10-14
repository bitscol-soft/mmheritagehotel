{{--
    Legacy public-site shell.
    Same markup as before; header/nav/footer are now extracted into shared partials
    (frontend.layouts.includes.public_*) so the new mm-web shell can reuse them.
    No visual change is intended for views that extend this layout.
--}}
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

    @include('frontend.layouts.includes.public_head')

    @stack('custom_css')

</head>
<body>

    @include('frontend.layouts.includes.public_nav')

    <!------------- BANNER ------------->
    @yield('homepage_banner')


    <!---------- SEARCH BAR AREA ---------->
    @yield('search_bar')




    <!---- YIELD FRONTEND CONTENT AREA ---->
    @yield('frontend-content')




    <!------------- CONTACT US ------------>
    <section class="contact-w3ls" id="contact" style="background:url({{ companyInfo() != null ? asset('uploads/company/extra/'.companyInfo()) : '' }}) no-repeat; background-position:center; background-attachment:fixed; background-size:100% 100%;">
        <div class="container" style="background-color: rgba(0, 0, 0, 0.55);">
            <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6 contact-w3-agile2" data-aos="flip-left">
                    <div class="contact-w3-agile1">
                        <h4>Contact Us</h4>
                        @php
                            $w = websiteInfo();
                        @endphp
                        <p>Address : {{ $w->address ?? '' }}</p>
                        <p>Telephone : {{ $w->phone_no ?? '' }}</p>
                        <p>Email : <a href="mailto:{{ $w->email ?? '' }}">{{ $w->email ?? '' }}</a></p>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 contact-w3-agile2" data-aos="flip-right">
                    <div class="contact-w3-agile1">
                        <h4>Contact Form</h4>
                        <form action="{{ route('contact.store') }}" method="post">
                            @csrf
                            <input type="text" name="name" placeholder="Name" required="">
                            <input type="email" name="email" placeholder="Email" required="">
                            <input type="text" name="phone" placeholder="Phone" required="">
                            <textarea name="message" placeholder="Message" required=""></textarea>
                            <input type="submit" value="Submit">
                        </form>
                    </div>
                </div>

            </div>
            <div class="clearfix"></div>
        </div>
    </section>



    <!------------- COPYRIGHT ------------->
    @php $wi = websiteInfo(); @endphp
    <div class="copy">
        <p>© {{ date('Y') }} {{ $wi->site_first_name }} {{ $wi->site_last_name }} . All Rights Reserved | Developed by <a href="https://www.banglafire.com" target="_blank">Banglafire Software Ltd.</a> </p>
    </div>


    @include('frontend.layouts.includes.public_footer')

    @yield('forntend_script')

    @stack('custom_js')

</body>
</html>