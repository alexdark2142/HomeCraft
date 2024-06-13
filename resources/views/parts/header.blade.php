<header class="section page-header">
    <!-- RD Navbar-->
    <div class="navbar-wrap navbar-modern-wrap">
        <nav
            id="navbar" class="navbar navbar-modern navbar-original navbar-static "
        >
            <div class="navbar-main-outer">
                <div class="navbar-main">
                    <!-- RD Navbar Panel-->
                    <div class="navbar-panel">
                        <!-- RD Navbar Toggle-->
                        <button class="navbar-toggle" id="navbar-nav-btn">
                            <span></span>
                        </button>
                        <!-- RD Navbar Brand-->
                        <div class="navbar-brand">
                            <a class="brand" href="/">
                                <img src="{{asset('images/logo/logo-default-196x47.png')}}" alt="logo" width="196" height="47"/>
                            </a>
                        </div>
                    </div>

                    <div class="navbar-main-element">
                        <div class="navbar-nav-wrap" id="navbar-nav-wrap">
                            <!-- RD Navbar Nav-->
                            <ul class="navbar-nav">
                                <li class="rd-nav-item active">
                                    <a class="rd-nav-link" href="/">Home</a>
                                </li>
{{--                                <li class="rd-nav-item rd-nav-dropdown">--}}
{{--                                    <a class="rd-nav-link" href="#">Categories</a>--}}
{{--                                    <ul class="rd-menu rd-navbar-dropdown">--}}
{{--                                        <li class="rd-dropdown-item">--}}
{{--                                            <a class="rd-dropdown-link" href="#">Ікони</a>--}}
{{--                                        </li>--}}
{{--                                        <li class="rd-dropdown-item">--}}
{{--                                            <a class="rd-dropdown-link" href="#">Миски</a>--}}
{{--                                            <ul class="rd-menu rd-navbar-dropdown">--}}
{{--                                                <li class="rd-dropdown-item">--}}
{{--                                                    <a class="rd-dropdown-link" href="#">Маленькі</a>--}}
{{--                                                </li>--}}
{{--                                                <li class="rd-dropdown-item">--}}
{{--                                                    <a class="rd-dropdown-link" href="#">Великі</a>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li class="rd-dropdown-item">--}}
{{--                                            <a class="rd-dropdown-link" href="#">Вазони</a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
                            </ul>
                        </div>

                        <div class="navbar-shopping-cart">
                            <button class="navbar-basket fl-bigmug-line-shopping198" id="cartButton">
                                <span id="cart-count">0</span>
                            </button>
                        </div>
                    </div>

                    <div class="shopping-cart navbar-modern-project">
                        <div class="shopping-cart-header">
                            <h4 class="shopping-cart-title">Shopping cart</h4>
                            <div class="shopping-cart-btn">
                                <div class="shopping-cart-close" id="cartClose">
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>

                        <div class="shopping-cart-content navbar-modern-project-content">
                            <div>
                                <div class="cart-inline">
                                    <div class="cart-row-body" id="cart-items"></div>
                                    <div class="cart-inline-header">
                                        <h5 class="cart-inline-title">In cart:<span id="cart-count-header"> 0</span> Products</h5>
                                        <h6 class="cart-inline-title">Total price:<span id="cart-total-price"> $0</span></h6>
                                    </div>
                                    <div class="cart-footer">
                                        <div class="cart-footer_btn group-sm">
                                            <a class="button button-md button-default-outline-2 button-wapasha" href="#" id="clear-cart">Clear cart</a>
                                            <a class="button button-md button-primary button-pipaluk" href="#">Checkout</a>
                                        </div>
                                    </div>
                                </div>

                                <ul class="navbar-modern-contacts">
                                    <li>
                                        <div class="unit unit-spacing-sm">
                                            <div class="unit-left">
                                                <span class="icon fa fa-phone"></span>
                                            </div>
                                            <div class="unit-body">
                                                <a class="link-phone" href="tel:+1 403-877-9890">+1 403-877-9890</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="unit unit-spacing-sm">
                                            <div class="unit-left">
                                                <span class="icon fa fa-location-arrow"></span>
                                            </div>
                                            <div class="unit-body">
                                                <a
                                                    class="link-location"
                                                    href="https://maps.google.com/maps?q=4649+62+St+%233+Red+Deer,+AB+T4N+2R4"
                                                    target="_blank"
                                                >
                                                    4649 62 St #3 Red Deer, AB T4N 2R4
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="unit unit-spacing-sm">
                                            <div class="unit-left">
                                                <span class="icon fa fa-envelope"></span>
                                            </div>
                                            <div class="unit-body">
                                                <a class="link-email" href="mailto:homecraft1sbdt@gmail.com">
                                                    homecraft1sbdt@gmail.com
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                <ul class="list-inline navbar-modern-list-social">
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
