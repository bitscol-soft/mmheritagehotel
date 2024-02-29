<script type="text/javascript" src="{{ asset('frontend/assets/js/jquery-2.1.4.min.js') }}"></script>

<!-- contact form -->
<script src="{{ asset('frontend/assets/js/jqBootstrapValidation.js') }}"></script>
<script src="{{ asset('frontend/assets/js/contact_me.js') }}"></script>

<!-- /contact form -->
<!-- Calendar -->
<script src="{{ asset('frontend/assets/js/jquery-ui.js') }}"></script>
<script>
        $(function() {
        $( "#datepicker,#datepicker1,#datepicker2,#datepicker3" ).datepicker();
        });
</script>

<!-- //Calendar -->

<!-- gallery popup -->
<link rel="stylesheet" href="{{ asset('frontend/assets/css/swipebox.css') }}">
<script src="{{ asset('frontend/assets/js/jquery.swipebox.min.js') }}"></script>

<script type="text/javascript">
    jQuery(function($) {
        $(".swipebox").swipebox();
    });
</script>

<!-- //gallery popup -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="{{ asset('frontend/assets/js/move-top.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/easing.js') }}"></script>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
<!-- flexSlider -->
<script defer src="{{ asset('frontend/assets/js/jquery.flexslider.js') }}"></script>
<script type="text/javascript">
    $(window).load(function(){
    $('.flexslider').flexslider({
        animation: "slide",
        start: function(slider){
        $('body').removeClass('loading');
        }
    });
    });
</script>
			<!-- //flexSlider -->
<script src="{{ asset('frontend/assets/js/responsiveslides.min.js') }}"></script>
<script>
    // You can also use "$(window).load(function() {"
    $(function () {
        // Slideshow 4
        $("#slider4").responsiveSlides({
        auto: true,
        pager:true,
        nav:false,
        speed: 500,
        namespace: "callbacks",
        before: function () {
            $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
            $('.events').append("<li>after event fired.</li>");
        }
        });

    });
</script>
<!--search-bar-->
<script src="{{ asset('frontend/assets/js/main.js') }}"></script>
<!--//search-bar-->
<!--tabs-->
<script src="{{ asset('frontend/assets/js/easy-responsive-tabs.js') }}"></script>

<script>
    $(document).ready(function () {
    $('#horizontalTab').easyResponsiveTabs({
    type: 'default', //Types: default, vertical, accordion
    width: 'auto', //auto or any width like 600px
    fit: true,   // 100% fit in a container
    closed: 'accordion', // Start closed if in accordion view
    activate: function(event) { // Callback function if tab is switched
    var $tab = $(this);
    var $info = $('#tabInfo');
    var $name = $('span', $info);
    $name.text($tab.text());
    $info.show();
    }
    });
    $('#verticalTab').easyResponsiveTabs({
    type: 'vertical',
    width: 'auto',
    fit: true
    });
    });
</script>
<!--//tabs-->
<!-- smooth scrolling -->
<script type="text/javascript">
    $(document).ready(function() {

    $().UItoTop({ easingType: 'easeOutQuart' });
    });
</script>
<div class="arr-w3ls">
    <a href="#home" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
</div>
<!-- //smooth scrolling -->
<script type="text/javascript" src="{{ asset('frontend/assets/js/bootstrap-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/assets/js/booking-cart.js') }}"></script>

<!-- sweetalert2 -->
<script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>

<script src="{{ asset('assets/js/toastr.min.js') }}"></script>

<script>
    @if(Session::has('message'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.success("{{ session('message') }}");
    @endif

        @if(Session::has('error'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.error("{{ session('error') }}");
    @endif

        @if(Session::has('info'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.info("{{ session('info') }}");
    @endif

        @if(Session::has('warning'))
        toastr.options =
        {
            "closeButton" : true,
            "progressBar" : true
        }
    toastr.warning("{{ session('warning') }}");
    @endif
</script>

{{-- Custom JS --}}
@stack('custom_js')
