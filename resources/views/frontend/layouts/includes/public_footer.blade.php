{{--
    Shared public-site footer (contact section + copyright + script includes).
    Used by both shells (legacy + mm-web).
--}}

<!---------- INCLUDE JS AREA ---------->
@include('frontend.layouts.includes.js')

{{-- Allow child views to push JS that should land after the shell's main JS but before the dropdown handlers below. --}}
@stack('public_footer_before_dropdowns')

<script>
    $('.submenu_item').hover(
        function () {
            $(this).show();
        },
        function () {
            $(this).hide();
        }
    );

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