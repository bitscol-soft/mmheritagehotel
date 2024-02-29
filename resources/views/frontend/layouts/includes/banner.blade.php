<?php

    $banner = getBanner();
?>


<div id="home" class="w3ls-banner">
    <!-- banner-text -->
    <div class="slider">
        <div class="callbacks_container">
            <ul class="rslides callbacks callbacks1" id="slider4">
                @foreach ($banner as $item)
                    <li>
                        <div class="w3layouts-banner-top" style="background: url({{ asset($item->banner_image) }}) no-repeat center;">
                            <div class="container">
                                <div class="agileits-banner-info">
                                    <h4>{{ $item->banner_title }}</h4>
                                    <h3>{{ $item->banner_sub_title }}</h3>
                                        <p>{{ $item->banner_short_desc }}</p>
                                    <div class="agileits_w3layouts_more menu__item">
                                        <a href="#" class="menu__link" data-toggle="modal" data-target="#myModal">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach


            </ul>
        </div>
        <div class="clearfix"> </div>
        <!--banner Slider starts Here-->
    </div>
    <div class="thim-click-to-bottom">
        <a href="#about" class="scroll">
            <i class="fa fa-long-arrow-down" aria-hidden="true"></i>
        </a>
    </div>
</div>
