<header class="section page-header">
    <!-- RD Navbar-->
    <div class="rd-navbar-wrap rd-navbar-modern-wrap" style="max-height: 70px">
        <nav
            class="rd-navbar rd-navbar-modern"
            data-layout="rd-navbar-fixed"
            data-sm-layout="rd-navbar-fixed"
            data-md-layout="rd-navbar-fixed"
            data-md-device-layout="rd-navbar-fixed"
            data-lg-layout="rd-navbar-static"
            data-lg-device-layout="rd-navbar-fixed"
            data-xl-layout="rd-navbar-static"
            data-xl-device-layout="rd-navbar-static"
            data-xxl-layout="rd-navbar-static"
            data-xxl-device-layout="rd-navbar-static"
            data-lg-stick-up-offset="46px"
            data-xl-stick-up-offset="46px"
            data-xxl-stick-up-offset="70px"
            data-lg-stick-up="true"
            data-xl-stick-up="true"
            data-xxl-stick-up="true"
        >
            <div class="rd-navbar-main-outer">
                <div class="rd-navbar-main">
                    <!-- RD Navbar Panel-->
                    <div class="rd-navbar-panel">
                        <!-- RD Navbar Toggle-->
                        <button class="rd-navbar-toggle" data-rd-navbar-toggle=".rd-navbar-nav-wrap">
                            <span></span>
                        </button>

                        <!-- RD Navbar Brand-->
                        <div class="rd-navbar-brand">
                            <a class="brand" href="/">
                                <img src="{{asset('images/logo/logo-default-196x47.png')}}" alt="logo" width="196" height="47"/>
                            </a>
                        </div>
                    </div>

                    <div class="rd-navbar-main-element">
                        <div class="rd-navbar-nav-wrap">
                            <!-- RD Navbar Basket-->
                           {{-- <div class="rd-navbar-basket-wrap">
                                <button class="rd-navbar-basket fl-bigmug-line-shopping198" data-rd-navbar-toggle=".cart-inline">
                                    <span>2</span>
                                </button>

                                <a class="rd-navbar-basket rd-navbar-basket-mobile fl-bigmug-line-shopping198" href="#">
                                    <span>2</span>
                                </a>

                                <div class="cart-inline">
                                    <div class="cart-inline-header">
                                        <h5 class="cart-inline-title">In cart:<span> 2</span> Products</h5>
                                        <h6 class="cart-inline-title">Total price:<span> $800</span></h6>
                                    </div>
                                    <div class="cart-inline-body">
                                        <div class="cart-inline-item">
                                            <div class="unit align-items-center">
                                                <div class="unit-left">
                                                    <a class="cart-inline-figure" href="#">
                                                        <img src="{{asset('images/product-mini-1-108x100.png')}}" alt="" width="108" height="100" />
                                                    </a>
                                                </div>
                                                <div class="unit-body">
                                                    <h6 class="cart-inline-name"><a href="#">Blueberries</a></h6>
                                                    <div>
                                                        <div class="group-xs group-inline-middle">
                                                            <div class="table-cart-stepper">
                                                                <input class="form-input" type="number" data-zeros="true" value="1" min="1" max="1000">
                                                            </div>
                                                            <h6 class="cart-inline-title">$550</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart-inline-footer">
                                        <div class="group-sm">
                                            <a class="button button-md button-default-outline-2 button-wapasha" href="#">Go to cart</a>
                                            <a class="button button-md button-primary button-pipaluk" href="#">Checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}

                            <!-- RD Navbar Search-->
                            {{--<div class="rd-navbar-search">
                                <button class="rd-navbar-search-toggle" data-rd-navbar-toggle=".rd-navbar-search">
                                    <span></span>
                                </button>

                                <form class="rd-search" action="#">
                                    <div class="form-wrap">
                                        <label class="form-label" for="rd-navbar-search-form-input">Search...</label>
                                        <input class="rd-navbar-search-form-input form-input" id="rd-navbar-search-form-input" type="text" name="search">
                                        <button class="rd-search-form-submit fl-bigmug-line-search74" type="submit"></button>
                                    </div>
                                </form>
                            </div>--}}

                            <!-- RD Navbar Nav-->
                            <ul class="rd-navbar-nav">
                                <li class="rd-nav-item active">
                                    <a class="rd-nav-link" href="/">Home</a>
                                </li>

                                {{--<li class="rd-nav-item">
                                    <a class="rd-nav-link" href="/about-us">About Us</a>
                                </li>--}}

                                <li class="rd-nav-item">
                                    <a class="rd-nav-link" href="/contact-us">Contact Us</a>
                                </li>
                            </ul>
                        </div>

                        <div
                            class="rd-navbar-project-hamburger"
                            data-multitoggle=".rd-navbar-main"
                            data-multitoggle-blur=".rd-navbar-wrap"
                            data-multitoggle-isolate
                        >
                            <button class="rd-navbar-basket fl-bigmug-line-shopping198" id="cartButton">
                                <span>2</span>
                            </button>

                            <div class="project-close" id="cartCloseMob">
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>

                    <div class="rd-navbar-project rd-navbar-modern-project">
                        <div class="rd-navbar-project-modern-header">
                            <h4 class="rd-navbar-project-modern-title">Get in Touch</h4>
                            <div
                                class="rd-navbar-project-hamburger"
                                data-multitoggle=".rd-navbar-main"
                                data-multitoggle-blur=".rd-navbar-wrap"
                                data-multitoggle-isolate
                            >
                                <div class="project-close" id="cartClose">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>

                        <div class="rd-navbar-project-content rd-navbar-modern-project-content">
                            <div>
                                <div class="cart-inline">
                                    <div class="cart-inline-header">
                                        <h5 class="cart-inline-title">In cart:<span> 2</span> Products</h5>
                                        <h6 class="cart-inline-title">Total price:<span> $800</span></h6>
                                    </div>

                                    <div class="cart-inline-body">
                                        <div class="cart-inline-item">
                                            <div class="unit align-items-center">
                                                <div class="unit-left">
                                                    <a class="cart-inline-figure" href="#">
                                                        <img src="{{asset('images/product-mini-1-108x100.png')}}" alt="" width="108" height="100" />
                                                    </a>
                                                </div>

                                                <div class="unit-body">
                                                    <h6 class="cart-inline-name"><a href="#">Blueberries</a></h6>
                                                    <div>
                                                        <div class="group-xs group-inline-middle">
                                                            <div class="table-cart-stepper">
                                                                <input class="form-input" type="number" data-zeros="true" value="1" min="1" max="1000">
                                                            </div>

                                                            <h6 class="cart-inline-title">$550</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cart-inline-footer">
                                        <div class="group-sm">
                                            <a class="button button-md button-default-outline-2 button-wapasha" href="#">Go to cart</a>
                                            <a class="button button-md button-primary button-pipaluk" href="#">Checkout</a>
                                        </div>
                                    </div>
                                </div>

                                <ul class="rd-navbar-modern-contacts">
                                    <li>
                                        <div class="unit unit-spacing-sm">
                                            <div class="unit-left">
                                                <span class="icon fa fa-phone"></span>
                                            </div>

                                            <div class="unit-body">
                                                <a class="link-phone" href="tel:#">+1 323-913-4688</a>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="unit unit-spacing-sm">
                                            <div class="unit-left">
                                                <span class="icon fa fa-location-arrow"></span>
                                            </div>

                                            <div class="unit-body">
                                                <a class="link-location" href="#">4730 Crystal Springs Dr, Los Angeles, CA 90027</a>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="unit unit-spacing-sm">
                                            <div class="unit-left">
                                                <span class="icon fa fa-envelope"></span>
                                            </div>

                                            <div class="unit-body">
                                                <a class="link-email" href="mailto:#">mail@demolink.org</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <ul class="list-inline rd-navbar-modern-list-social">
                                    <li><a class="icon fa fa-facebook" href="#"></a></li>
                                    <li><a class="icon fa fa-twitter" href="#"></a></li>
                                    <li><a class="icon fa fa-google-plus" href="#"></a></li>
                                    <li><a class="icon fa fa-instagram" href="#"></a></li>
                                    <li><a class="icon fa fa-pinterest" href="#"></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
