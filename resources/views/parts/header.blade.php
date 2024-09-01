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
                <span id="cart-quantity">0</span>
            </button>
        </div>

        @include('parts.shopping-cart')
    </nav>
</header>
