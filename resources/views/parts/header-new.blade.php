<header class="header">
    <nav class="nav container">
        <div class="nav__data">
            <div class="nav__toggle" id="nav-toggle">
                <i class="ri-menu-line nav__burger"></i>
                <i class="ri-close-line nav__close"></i>
            </div>

            <a href="/" class="nav__logo">
                <img
                    src="{{asset('images/logo/logo-default-196x47.webp')}}"
                    alt="logo"
                    width="196"
                    height="47"
                />
            </a>
        </div>

        <!--=============== NAV MENU ===============-->
        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <li><a href="/" class="nav__link">Home</a></li>

                <!--=============== DROPDOWN 1 ===============-->
                <li class="dropdown__item">
                    <div class="nav__link" id="categories-toggle">
                        Categories <i class="ri-arrow-down-s-line dropdown__arrow"></i>
                    </div>

                    <ul class="dropdown__menu">
                        @foreach ($categories as $category)
                            @if($category->subcategories->isEmpty())
                                <li>
                                    <a href="/products/{{ $category->filter_name }}" class="dropdown__link">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @else
                                <!--=============== DROPDOWN SUBMENU ===============-->
                                <li class="dropdown__subitem">
                                    <div class="dropdown__link subitem-toggle">
                                        <a href="/products/{{ $category->filter_name }}">
                                            {{ $category->name }}
                                        </a>
                                        <i class="ri-add-line dropdown__add"></i>
                                    </div>

                                    <ul class="dropdown__submenu">
                                        @foreach ($category->subcategories as $subcategory)
                                            <li>
                                                <a href="/products/{{ $category->filter_name }}/{{ $subcategory->filter_name }}" class="dropdown__sublink">
                                                    {{ $subcategory->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>

        <div class="navbar-shopping-cart">
            <button class="navbar-basket fl-bigmug-line-shopping198" id="cartButton">
                <span id="cart-count">0</span>
            </button>
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

{{--                        <li>--}}
{{--                            <div class="unit unit-spacing-sm">--}}
{{--                                <div class="unit-left">--}}
{{--                                    <span class="icon fa fa-location-arrow"></span>--}}
{{--                                </div>--}}
{{--                                <div class="unit-body">--}}
{{--                                    <a--}}
{{--                                        class="link-location"--}}
{{--                                        href="https://maps.google.com/maps?q=4649+62+St+%233+Red+Deer,+AB+T4N+2R4"--}}
{{--                                        target="_blank"--}}
{{--                                    >--}}
{{--                                        4649 62 St #3 Red Deer, AB T4N 2R4--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}

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
                        <li>
                            <a
                                class="icon fa fa-facebook"
                                target="_blank"
                                href="https://www.facebook.com/profile.php?id=61561930961959&mibextid=ZbWKwL"
                            ></a>
                        </li>

                        <li>
                            <a class="icon fa fa-instagram"
                               target="_blank"
                               href="https://www.instagram.com/homecraftsb_dt?igsh=dHE1OHZ6ZWhhc2pz&utm_source=qr"
                            ></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
