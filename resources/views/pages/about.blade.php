@extends('layouts.base')

@section('content')
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">About</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li>About</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-0 ps-8 pe-8 lg-ps-3 lg-pe-3 position-relative xs-px-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <img src="images/demo-fashion-store-about-01.jpg" class="w-100" alt />
                    <div
                        class="absolute-middle-left top-0px left-15 w-170px h-170px border-radius-100 sm-w-110px sm-h-110px sm-left-15px">
                        <img src="images/demo-fashion-store-about-03.png"
                            class="position-absolute top-50 translate-middle-y" alt />
                        <img src="images/demo-fashion-store-about-02.png" class="animation-rotation" alt />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-0">
        <div class="container">
            <div class="row"
                data-anime='{ "el": "childs", "translateY": [15, 0], "opacity": [0,1], "duration": 500, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-lg-5">
                    <div class="alt-font text-dark-gray mb-15px fs-20">
                        <span class="text-highlight">The fashion core collection!<span
                                class="bg-base-color h-8px bottom-0px"></span></span>
                    </div>
                    <h2 class="alt-font text-dark-gray fw-400 ls-minus-1px">
                        The journey of <span class="fw-600">crafto lifestyle.</span>
                    </h2>
                </div>
                <div class="col-12 col-lg-6 offset-lg-1 last-paragraph-no-margin">
                    <p>
                        Lorem ipsum is simply dummy text of the printing and typesetting
                        industry. lorem ipsum has been the industry's standard dummy text
                        ever since the 1500s, when an unknown.
                    </p>
                    <p>
                        Lorem ipsum is simply dummy text of the printing and typesetting
                        industry. lorem ipsum has been the industry's standard be dummy
                        text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden position-relative p-0">
        <div class="container">
            <div class="absolute-middle-left w-100 h-2px bg-base-color"></div>
            <div class="row align-items-center">
                <div class="col-12 col-md-12 position-relative">
                    <div class="outside-box-right-30"
                        data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <div class="swiper magic-cursor drag-cursor"
                            data-slider-options='{ "slidesPerView": 1, "spaceBetween": 35, "loop": false, "autoplay": { "delay": 2000, "disableOnInteraction": false }, "pagination": { "el": ".slider-four-slide-pagination-1", "clickable": true, "dynamicBullets": false }, "keyboard": { "enabled": true, "onlyInViewport": true }, "breakpoints": { "992": { "slidesPerView": 4 }, "768": { "slidesPerView": 3, "spaceBetween": 45 }, "320": { "slidesPerView": 2 } }, "effect": "slide" }'>
                            <div class="swiper-wrapper align-items-center">
                                <div class="swiper-slide">
                                    <img src="images/demo-fashion-store-about-04.jpg" alt />
                                </div>

                                <div class="swiper-slide">
                                    <img src="images/demo-fashion-store-about-05.jpg" alt />
                                </div>

                                <div class="swiper-slide">
                                    <img src="images/demo-fashion-store-about-06.jpg" alt />
                                </div>

                                <div class="swiper-slide">
                                    <img src="images/demo-fashion-store-about-07.jpg" alt />
                                </div>

                                <div class="swiper-slide">
                                    <img src="images/demo-fashion-store-about-08.jpg" alt />
                                </div>

                                <div class="swiper-slide">
                                    <img src="images/demo-fashion-store-about-09.jpg" alt />
                                </div>

                                <div class="swiper-slide"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gradient-top-very-light-gray overlap-height pb-4 sm-pb-50px">
        <div class="container overlap-gap-section">
            <div class="row mb-4 xs-mb-8">
                <div class="col-12 text-center">
                    <h2 class="alt-font text-dark-gray mb-0 ls-minus-2px">
                        We care our
                        <span class="text-highlight fw-600">customers<span
                                class="bg-base-color h-5px bottom-2px"></span></span>
                    </h2>
                </div>
            </div>
            <div class="row row-cols-auto row-cols-lg-4 row-cols-sm-2 position-relative"
                data-anime='{ "el": "childs", "translateX": [50, 0], "opacity": [0,1], "duration": 600, "delay":100, "staggervalue": 150, "easing": "easeOutQuad" }'>
                <div class="col align-self-start">
                    <div class="feature-box text-start ps-30px sm-ps-20px">
                        <div class="feature-box-icon position-absolute left-0px top-10px">
                            <h1 class="fs-100 opacity-1 fw-800 ls-minus-1px mb-0">01</h1>
                        </div>
                        <div class="feature-box-content last-paragraph-no-margin pt-30 lg-pt-60px sm-pt-40px">
                            <span class="text-dark-gray fs-19 d-inline-block fw-600 mb-5px">Business founded</span>
                            <p class="w-90 xl-w-95">
                                Lorem ipsum is simply text the printing typesetting standard
                                dummy.
                            </p>
                            <span class="w-60px h-2px bg-dark-gray mt-20px d-inline-block"></span>
                        </div>
                    </div>
                </div>

                <div class="col align-self-end mt-30px">
                    <div class="feature-box text-start ps-30px sm-ps-20px">
                        <div class="feature-box-icon position-absolute left-0px top-10px">
                            <h1 class="alt-font fs-90 opacity-1 fw-800 ls-minus-1px mb-0">
                                02
                            </h1>
                        </div>
                        <div class="feature-box-content last-paragraph-no-margin pt-30 lg-pt-60px sm-pt-40px">
                            <span class="text-dark-gray fs-19 d-inline-block fw-600 mb-5px">Build new office</span>
                            <p class="w-90 xl-w-95">
                                Lorem ipsum is simply text the printing typesetting standard
                                dummy.
                            </p>
                            <span class="w-60px h-2px bg-dark-gray mt-20px d-inline-block"></span>
                        </div>
                    </div>
                </div>

                <div class="col align-self-start xs-mt-30px">
                    <div class="feature-box text-start ps-30px sm-ps-20px">
                        <div class="feature-box-icon position-absolute left-0px top-10px">
                            <h1 class="alt-font fs-90 opacity-1 fw-800 ls-minus-1px mb-0">
                                03
                            </h1>
                        </div>
                        <div class="feature-box-content last-paragraph-no-margin pt-30 lg-pt-60px sm-pt-40px">
                            <span class="text-dark-gray fs-19 d-inline-block fw-600 mb-5px">Relocates headquarter</span>
                            <p class="w-90 xl-w-95">
                                Lorem ipsum is simply text the printing typesetting standard
                                dummy.
                            </p>
                            <span class="w-60px h-2px bg-dark-gray mt-20px d-inline-block"></span>
                        </div>
                    </div>
                </div>

                <div class="col align-self-end mt-30px">
                    <div class="feature-box text-start ps-30px sm-ps-20px">
                        <div class="feature-box-icon position-absolute left-0px top-10px">
                            <h1 class="alt-font fs-90 opacity-1 fw-800 ls-minus-1px mb-0">
                                04
                            </h1>
                        </div>
                        <div class="feature-box-content last-paragraph-no-margin pt-30 lg-pt-60px sm-pt-40px">
                            <span class="text-dark-gray fs-19 d-inline-block fw-600 mb-5px">Revenues of millions</span>
                            <p class="w-90 xl-w-95">
                                Lorem ipsum is simply text the printing typesetting standard
                                dummy.
                            </p>
                            <span class="w-60px h-2px bg-dark-gray mt-20px d-inline-block"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row justify-content-center mb-10 overlap-section">
                <div class="col-xl-9 col-lg-10"
                    data-anime='{ "el": "childs", "translateY": [0, 0], "opacity": [0,1], "duration": 500, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div
                        class="row align-items-center justify-content-center bg-white box-shadow-medium-bottom border border-color-extra-medium-gray border-radius-100px sm-border-radius-6px md-mx-0">
                        <div
                            class="col-lg-6 p-20px border-end border-color-transparent-dark-very-light text-center ls-minus-05px align-items-center d-flex justify-content-center md-border-end-0 md-pb-10px">
                            <i class="bi bi-emoji-smile text-dark-gray icon-extra-medium me-10px"></i>
                            <span class="text-dark-gray fs-20 text-start fw-500">Join the <span
                                    class="fw-700">10000+</span> people trusting
                                us.</span>
                        </div>
                        <div
                            class="col-lg-6 p-20px md-pt-0 text-center ls-minus-05px align-items-center d-flex justify-content-center">
                            <i class="bi bi-star text-dark-gray icon-extra-medium me-10px"></i>
                            <span class="text-dark-gray fs-20 text-start fw-500">4.9 out of 5 - <span
                                    class="fw-700">8549</span> Total
                                reviews.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center mb-10">
                <div class="col-lg-6 position-relative md-mb-70px">
                    <div class="w-75 position-relative xs-w-80"
                        data-anime='{ "effect": "slide", "direction": "rl", "color": "#fee300", "duration": 1000, "delay": 300 }'>
                        <img class="w-100" src="images/demo-fashion-store-about-10.jpg" alt />
                        <div class="position-absolute right-minus-100px xs-right-minus-50px top-30px w-210px h-210px xs-w-130px xs-h-130px border-radius-100 lg-top-minus-10px md-top-60px sm-top-30px"
                            data-bottom-top="transform: translateY(30px)" data-top-bottom="transform: translateY(-30px)">
                            <img src="images/demo-fashion-store-about-13.png"
                                class="position-absolute top-50 translate-middle-y" alt />
                            <img src="images/demo-fashion-store-about-12.png" class="animation-rotation" alt />
                        </div>
                    </div>
                    <div class="position-absolute right-minus-15px md-right-15px bottom-minus-30px w-55 md-w-50 overflow-hidden"
                        data-bottom-top="transform: translateY(50px)" data-top-bottom="transform: translateY(-50px)"
                        data-anime='{ "effect": "slide", "direction": "lr", "color": "#fee300", "duration": 1000, "delay": 1000 }'>
                        <img class="w-100" src="images/demo-fashion-store-about-11.jpg" alt />
                    </div>
                </div>
                <div class="col-12 col-lg-5 offset-lg-1">
                    <div class="alt-font text-dark-gray mb-15px fs-20"
                        data-anime='{ "translateX": [15, 0], "opacity": [0,1], "duration": 400, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <span class="text-highlight">Our fashion store mission<span
                                class="bg-base-color h-8px bottom-0px"></span></span>
                    </div>
                    <h2 class="alt-font text-dark-gray mb-20px fw-400 ls-minus-1px w-90 lg-fs-50 lg-w-100"
                        data-anime='{ "translateX": [15, 0], "opacity": [0,1], "duration": 400, "delay": 200, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        Quality product with
                        <span class="fw-600">exceptional price-value.</span>
                    </h2>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="accordion accordion-style-02" id="accordion-style-02"
                                data-active-icon="icon-feather-minus" data-inactive-icon="icon-feather-plus"
                                data-anime='{ "el": "childs", "translateX": [50, 0], "opacity": [0,1], "duration": 400, "delay": 500, "staggervalue": 300, "easing": "easeOutQuad" }'>
                                <div class="accordion-item active-accordion">
                                    <div class="accordion-header border-bottom border-color-extra-medium-gray">
                                        <a href="#" data-bs-toggle="collapse"
                                            data-bs-target="#accordion-style-02-01" aria-expanded="true"
                                            data-bs-parent="#accordion-style-02">
                                            <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                <i class="feather icon-feather-minus"></i><span
                                                    class="fw-600 fs-19">Fashions fade style is eternal</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="accordion-style-02-01" class="accordion-collapse collapse show"
                                        data-bs-parent="#accordion-style-02">
                                        <div
                                            class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                            <p>
                                                We deliver customized marketing campaign to use your
                                                audience to make a positive move.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <div class="accordion-header border-bottom border-color-extra-medium-gray">
                                        <a href="#" data-bs-toggle="collapse"
                                            data-bs-target="#accordion-style-02-02" aria-expanded="false"
                                            data-bs-parent="#accordion-style-02">
                                            <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                <i class="feather icon-feather-plus"></i><span class="fw-600 fs-19">I make
                                                    clothes. Women make fashion</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="accordion-style-02-02" class="accordion-collapse collapse"
                                        data-bs-parent="#accordion-style-02">
                                        <div
                                            class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                            <p>
                                                We deliver customized marketing campaign to use your
                                                audience to make a positive move.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <div class="accordion-header border-bottom border-color-transparent">
                                        <a href="#" data-bs-toggle="collapse"
                                            data-bs-target="#accordion-style-02-03" aria-expanded="false"
                                            data-bs-parent="#accordion-style-02">
                                            <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                <i class="feather icon-feather-plus"></i><span
                                                    class="fw-600 fs-19">Something new fashion for everyone</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="accordion-style-02-03" class="accordion-collapse collapse"
                                        data-bs-parent="#accordion-style-02">
                                        <div
                                            class="accordion-body last-paragraph-no-margin border-bottom border-color-transparent">
                                            <p>
                                                We deliver customized marketing campaign to use your
                                                audience to make a positive move.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row position-relative clients-style-08"
                data-anime='{"translateY": [0, 0], "opacity": [0,1], "duration": 300, "delay": 200, "staggervalue": 300, "easing": "easeOutQuad" }'>
                <div class="col swiper text-center feather-shadow"
                    data-slider-options='{ "slidesPerView": 2, "spaceBetween":0, "speed": 4000, "loop": true, "pagination": { "el": ".slider-four-slide-pagination-2", "clickable": false }, "allowTouchMove": false, "autoplay": { "delay":0, "disableOnInteraction": false }, "navigation": { "nextEl": ".slider-four-slide-next-2", "prevEl": ".slider-four-slide-prev-2" }, "keyboard": { "enabled": true, "onlyInViewport": true }, "breakpoints": { "1200": { "slidesPerView": 4 }, "992": { "slidesPerView": 3 }, "768": { "slidesPerView": 2 } }, "effect": "slide" }'>
                    <div class="swiper-wrapper marquee-slide">
                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-asos.svg" class="h-30px" alt /></a>
                        </div>

                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-chanel.svg" class="h-30px" alt /></a>
                        </div>

                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-gucci.svg" class="h-30px" alt /></a>
                        </div>

                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-celine.svg" class="h-30px" alt /></a>
                        </div>

                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-adidas.svg" class="h-30px" alt /></a>
                        </div>

                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-asos.svg" class="h-30px" alt /></a>
                        </div>

                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-chanel.svg" class="h-30px" alt /></a>
                        </div>

                        <div class="swiper-slide">
                            <a href="#"><img src="images/logo-gucci.svg" class="h-30px" alt /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
