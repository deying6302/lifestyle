<header class="header-with-topbar">
    <div
        class="header-top-bar top-bar-light bg-base-color disable-fixed md-border-bottom border-color-transparent-dark-very-light">
        <div class="container-fluid">
            <div class="row h-40px align-items-center m-0">
                <div class="col-12 justify-content-center alt-font fs-13 fw-500 text-uppercase">
                    <div class="text-dark-gray">
                        {{ __('frontend.header.title') }}
                    </div>
                    <a href="#" class="text-dark-gray fw-600 ms-5px text-dark-gray-hover"><span
                            class="text-decoration-line-bottom">{{ __('frontend.header.shop_now') }}</span></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg header-light bg-white disable-fixed center-logo">
        <div class="container-fluid">
            <div class="col-auto col-xxl-3 col-lg-2 menu-logo">
                <div class="header-icon d-none d-lg-flex">
                    <div class="widget-text icon alt-font">
                        <a href="{{ route('contact') }}"><i
                                class="feather icon-feather-map-pin d-inline-block me-5px"></i><span
                                class="d-none d-xxl-inline-block">{{ __('frontend.header.find_store') }}</span></a>
                    </div>
                    <div class="widget-text icon alt-font">
                        <a href="https://www.instagram.com/" target="_blank"><i
                                class="feather icon-feather-instagram d-inline-block me-5px"></i><span
                                class="d-none d-xxl-inline-block">100k {{ __('frontend.header.followers') }}</span></a>
                    </div>
                </div>
                <a class="navbar-brand" href="{{ route('home') }}">
                    @if ($logoIcon)
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black) }}"
                            data-at2x="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black_2x) }}"
                            alt class="default-logo" />
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black) }}"
                            data-at2x="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black_2x) }}"
                            alt class="alt-logo" />
                        <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black) }}"
                            data-at2x="{{ getImage(imagePath()['logoIcon']['path'] . '/' . $logoIcon->logo_black_2x) }}"
                            alt class="mobile-logo" />
                    @else
                        <img src="" alt="" class="default-logo" />
                        <img src="" alt="" class="alt-logo" />
                        <img src="" alt="" class="mobile-logo" />
                    @endif

                </a>
            </div>
            <div class="col-auto col-xxl-6 col-lg-8 menu-order">
                <button class="navbar-toggler float-end" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                    <span class="navbar-toggler-line"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav alt-font navbar-left justify-content-end">
                        <li class="nav-item {{ set_active(['home']) }}">
                            <a href="{{ route('home') }}" class="nav-link">{{ __('frontend.header.home') }}</a>
                        </li>
                        <li class="nav-item dropdown submenu">
                            <a href="{{ route('shop') }}" class="nav-link">{{ __('frontend.header.shop') }}</a>
                            <i class="fa-solid fa-angle-down dropdown-toggle" id="navbarDropdownMenuLink1"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <div class="dropdown-menu submenu-content" aria-labelledby="navbarDropdownMenuLink1">
                                <div class="d-lg-flex mega-menu m-auto flex-column">
                                    <div
                                        class="row row-cols-1 row-cols-lg-5 row-cols-md-3 row-cols-sm-3 mb-50px md-mb-25px xs-mb-15px">
                                        <div class="col">
                                            <ul>
                                                <li class="sub-title">Men</li>
                                                <li><a href="#">Jeans</a></li>
                                                <li><a href="#">Trousers</a></li>
                                                <li><a href="#">Swimwear</a></li>
                                                <li><a href="#">Casual shirts</a></li>
                                                <li><a href="#">Rain jackets</a></li>
                                                <li><a href="#">Loungewear</a></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <ul>
                                                <li class="sub-title">Women</li>
                                                <li><a href="#">Dupattas</a></li>
                                                <li><a href="#">Leggings</a></li>
                                                <li><a href="#">Ethnic wear</a></li>
                                                <li><a href="#">Kurtas & suits</a></li>
                                                <li><a href="#">Western wear</a></li>
                                                <li><a href="#">Dress materials</a></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <ul>
                                                <li class="sub-title">Kids</li>
                                                <li><a href="#">Dresses</a></li>
                                                <li><a href="#">Jumpsuits</a></li>
                                                <li><a href="#">Track pants</a></li>
                                                <li><a href="#">Ethnic wear</a></li>
                                                <li><a href="#">Value packs</a></li>
                                                <li><a href="#">Loungewear</a></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <ul>
                                                <li class="sub-title">Divided</li>
                                                <li><a href="#">Tops</a></li>
                                                <li><a href="#">Dresses</a></li>
                                                <li><a href="#">Shorts</a></li>
                                                <li><a href="#">Swimwear</a></li>
                                                <li><a href="#">Jeans</a></li>
                                                <li><a href="#">Jackets</a></li>
                                            </ul>
                                        </div>
                                        <div class="col">
                                            <ul>
                                                <li class="sub-title">Accessories</li>
                                                <li><a href="#">Shoes</a></li>
                                                <li><a href="#">Scarves</a></li>
                                                <li><a href="#">Watches</a></li>
                                                <li><a href="#">Wristwear</a></li>
                                                <li><a href="#">Backpacks</a></li>
                                                <li><a href="#">Sunglasses</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-2">
                                        <div class="col">
                                            <a href="shop.html"><img
                                                    src="{{ asset('frontend/images/demo-fashion-store-menu-banner-01.jpg') }}"
                                                    alt /></a>
                                        </div>
                                        <div class="col">
                                            <a href="shop.html"><img
                                                    src="{{ asset('frontend/images/demo-fashion-store-menu-banner-02.jpg') }}"
                                                    alt /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown submenu">
                            <a href="collection.html" class="nav-link">{{ __('frontend.header.collection') }}</a>
                            <i class="fa-solid fa-angle-down dropdown-toggle" id="navbarDropdownMenuLink2"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"></i>
                            <div class="dropdown-menu submenu-content" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="d-lg-flex mega-menu m-auto flex-column">
                                    <div
                                        class="row row-cols-2 row-cols-lg-6 row-cols-md-3 row-cols-sm-2 md-mx-0 align-items-center justify-content-center">
                                        <div class="col md-mb-25px">
                                            <a href="collection.html" class="justify-content-center mb-10px">
                                                <img src="{{ asset('frontend/images/demo-fashion-store-menu-category-01.jpg') }}"
                                                    class="border-radius-4px w-100" alt />
                                            </a>
                                            <a href="collection.html"
                                                class="btn btn-hover-animation fw-500 text-uppercase-inherit justify-content-center pt-0 pb-0">
                                                <span>
                                                    <span class="btn-text text-dark-gray fs-17">Polo t-shirts</span>
                                                    <span class="btn-icon"><i
                                                            class="fa-solid fa-arrow-right icon-very-small w-auto"></i></span>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="col md-mb-25px">
                                            <a href="collection.html" class="justify-content-center mb-10px">
                                                <img src="{{ asset('frontend/images/demo-fashion-store-menu-category-02.jpg') }}"
                                                    class="border-radius-4px w-100" alt />
                                            </a>
                                            <a href="collection.html"
                                                class="btn btn-hover-animation fw-500 text-uppercase-inherit justify-content-center pt-0 pb-0">
                                                <span>
                                                    <span class="btn-text text-dark-gray fs-17">Sunglasses</span>
                                                    <span class="btn-icon"><i
                                                            class="fa-solid fa-arrow-right icon-very-small w-auto"></i></span>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="col md-mb-25px">
                                            <a href="collection.html" class="justify-content-center mb-10px">
                                                <img src="{{ asset('frontend/images/demo-fashion-store-menu-category-03.jpg') }}"
                                                    class="border-radius-4px w-100" alt />
                                            </a>
                                            <a href="collection.html"
                                                class="btn btn-hover-animation fw-500 text-uppercase-inherit justify-content-center pt-0 pb-0">
                                                <span>
                                                    <span class="btn-text text-dark-gray fs-17">Skinny blazer</span>
                                                    <span class="btn-icon"><i
                                                            class="fa-solid fa-arrow-right icon-very-small w-auto"></i></span>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="col sm-mb-25px">
                                            <a href="collection.html" class="justify-content-center mb-10px">
                                                <img src="{{ asset('frontend/images/demo-fashion-store-menu-category-04.jpg') }}"
                                                    class="border-radius-4px w-100" alt />
                                            </a>
                                            <a href="collection.html"
                                                class="btn btn-hover-animation fw-500 text-uppercase-inherit justify-content-center pt-0 pb-0">
                                                <span>
                                                    <span class="btn-text text-dark-gray fs-17">Casual shoes</span>
                                                    <span class="btn-icon"><i
                                                            class="fa-solid fa-arrow-right icon-very-small w-auto"></i></span>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="collection.html" class="justify-content-center mb-10px">
                                                <img src="{{ asset('frontend/images/demo-fashion-store-menu-category-05.jpg') }}"
                                                    class="border-radius-4px w-100" alt />
                                            </a>
                                            <a href="collection.html"
                                                class="btn btn-hover-animation fw-500 text-uppercase-inherit justify-content-center pt-0 pb-0">
                                                <span>
                                                    <span class="btn-text text-dark-gray fs-17">Winter jackets</span>
                                                    <span class="btn-icon"><i
                                                            class="fa-solid fa-arrow-right icon-very-small w-auto"></i></span>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="col">
                                            <a href="collection.html" class="justify-content-center mb-10px">
                                                <img src="{{ asset('frontend/images/demo-fashion-store-menu-category-06.jpg') }}"
                                                    class="border-radius-4px w-100" alt />
                                            </a>
                                            <a href="collection.html"
                                                class="btn btn-hover-animation fw-500 text-uppercase-inherit justify-content-center pt-0 pb-0">
                                                <span>
                                                    <span class="btn-text text-dark-gray fs-17">Men's shorts</span>
                                                    <span class="btn-icon"><i
                                                            class="fa-solid fa-arrow-right icon-very-small w-auto"></i></span>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav alt-font navbar-right justify-content-start">
                        <li class="nav-item">
                            <a href="{{ route('blog') }}" class="nav-link">{{ __('frontend.header.blog') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact') }}"
                                class="nav-link">{{ __('frontend.header.contact') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-auto col-xxl-3 col-lg-2 text-end">
                <div class="header-icon">
                    <div class="header-cart-icon icon">
                        <div class="header-cart dropdown">
                            <a href="javascript:void(0);"><i class="fa fa-language me-5px"
                                    style="display: contents"></i><span class="d-none d-xxl-inline-block"
                                    style="margin-left: 4px;">{{ __('frontend.header.translate.title') }}</span></a>
                            <ul class="cart-item-list">
                                <li class="cart-item align-items-center">
                                    <div class="product-image">
                                        <a href="{{ route('language.switch', ['lang' => 'vi']) }}"><img
                                                src="{{ asset('frontend/images/flag/viet_nam.png') }}"
                                                class="cart-thumb" alt /></a>
                                    </div>
                                    <div class="product-detail fw-600">
                                        <a
                                            href="{{ route('language.switch', ['lang' => 'vi']) }}">{{ __('frontend.header.translate.viet_nam') }}</a>
                                        <span
                                            class="item-ammount fw-400">{{ __('frontend.header.translate.vietnamese') }}</span>
                                    </div>
                                </li>
                                <li class="cart-item align-items-center">
                                    <div class="product-image">
                                        <a href="{{ route('language.switch', ['lang' => 'en']) }}"><img
                                                src="{{ asset('frontend/images/flag/america.jpg') }}"
                                                class="cart-thumb" alt /></a>
                                    </div>
                                    <div class="product-detail fw-600">
                                        <a
                                            href="{{ route('language.switch', ['lang' => 'en']) }}">{{ __('frontend.header.translate.america') }}</a>
                                        <span
                                            class="item-ammount fw-400">{{ __('frontend.header.translate.english') }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="header-search-icon icon alt-font">
                        <a href="javascript:void(0)" class="search-form-icon header-search-form"><i
                                class="feather icon-feather-search me-5px"></i><span
                                class="d-none d-xxl-inline-block">{{ __('frontend.header.search.title') }}</span></a>
                        <div class="search-form-wrapper">
                            <button title="Close" type="button" class="search-close alt-font">
                                ×
                            </button>
                            <form id="search-form" role="search" method="get" class="search-form text-left"
                                action="https://craftohtml.themezaa.com/search-result.html">
                                <div class="search-form-box">
                                    <h2 class="text-dark-gray text-center mb-4 fw-600 alt-font ls-minus-1px">
                                        {{ __('frontend.header.search.search_title') }}
                                    </h2>
                                    <input class="search-input alt-font" id="search-form-input5e219ef164995"
                                        placeholder="{{ __('frontend.header.search.search_placeholder') }}"
                                        name="s" value type="text" autocomplete="off" />
                                    <button type="submit" class="search-button">
                                        <i class="feather icon-feather-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="widget-text icon alt-font">
                        <a href="{{ route('buyer.auth') }}"><i class="feather icon-feather-user d-inline-block me-5px"></i><span
                                class="d-none d-xxl-inline-block">Account</span></a>
                    </div>
                    <div class="header-cart-icon icon">
                        <div class="header-cart dropdown">
                            <a href="{{ route('cart.index') }}">
                                <i class="feather icon-feather-shopping-bag"></i><span id="cart-count"
                                    class="cart-count alt-font text-white bg-dark-gray"> </span></a>
                            <ul class="cart-item-list" id="cartItemList" style="padding: 12px 14px; width: max-content"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
