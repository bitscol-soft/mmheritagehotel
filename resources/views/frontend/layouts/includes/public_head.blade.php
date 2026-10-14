{{--
    Shared <head> for the public site.
    Used by:
      - frontend.layouts.master       (legacy shell, 11 public views)
      - frontend.layouts.mm-web       (new mm-web shell, home/room_view/booking-cart)
    The legacy `master` shell previously inlined this block — extracting it keeps both shells
    visually identical and means there is exactly one place to update <head> markup.
--}}

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

<script type="text/javascript" src='https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'></script>

<script type="text/javascript" src="https://www.servedby-buysellads.com/monetization.js" ></script>

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
    (function(v,d,o,ai){ai=d.createElement("script");ai.defer=true;ai.async=true;ai.src=v.location.protocol+o;d.head.appendChild(ai);})(window, document, "https://vdo.ai/core/w3layouts/vdo.ai.js");
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
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-30027142-1', 'w3layouts.com');
    ga('send', 'pageview');
</script>

<!-- sweatalert2 -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.min.css') }}">

@stack('public_head')