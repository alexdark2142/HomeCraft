<nav class="header">
    <div class="header-logo">
        <a class="logo-link" href="/admin">
            <span class="logo-text"><i class="em em-grinning"></i>Admin</span>
        </a>
    </div>
    <div class="header-toggle">
        <button id="nav-toggle" class="toggle-button">
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
            <span class="toggle-bar"></span>
        </button>
    </div>

    <nav class="nav__menu" id="nav-menu">
        <ul class="nav__list">
            <!-- Група меню для продуктів -->
            <li class="dropdown__item">
                <div class="nav__link dropdown-toggle">
                    Orders
                    <span class="arrow">&#9662;</span>
                </div>

                <ul class="dropdown__menu">
                    <li><a class="dropdown__link" href="{{ route('orders.payment-in-progress') }}">Payment in progress</a></li>
                    <li><a class="dropdown__link" href="{{ route('orders.new') }}">New</a></li>
                    <li><a class="dropdown__link" href="{{ route('orders.prepared') }}">Ready to ship</a></li>
                    <li><a class="dropdown__link" href="{{ route('orders.shipped') }}">Shipped</a></li>
                    <li><a class="dropdown__link" href="{{ route('orders.cancelled') }}">Cancelled</a></li>
                </ul>
            </li>

            <!-- Група меню для продуктів -->
            <li class="dropdown__item">
                <div class="nav__link dropdown-toggle">
                    Products
                    <span class="arrow">&#9662;</span>
                </div>

                <ul class="dropdown__menu">
                    <li><a class="dropdown__link" href="{{ route('products.create') }}">Add product</a></li>
                    <li><a class="dropdown__link" href="{{ route('products.index') }}">List of Products</a></li>
                </ul>
            </li>

            <!-- Група меню для категорій -->
            <li class="dropdown__item">
                <div class="nav__link dropdown-toggle">
                    Categories
                    <span class="arrow">&#9662;</span>
                </div>

                <ul class="dropdown__menu">
                    <li><a class="dropdown__link" href="{{ route('categories.create') }}">Add category</a></li>
                    <li><a class="dropdown__link" href="{{ route('categories.index') }}">List of categories</a></li>
                </ul>
            </li>

            <!-- Група меню для домашніх картинок -->
            <li class="dropdown__item">
                <div class="nav__link dropdown-toggle">
                    Home Pictures
                    <span class="arrow">&#9662;</span>
                </div>

                <ul class="dropdown__menu">
                    <li><a class="dropdown__link" href="{{ route('sliders.create') }}">Add home picture</a></li>
                    <li><a class="dropdown__link" href="{{ route('sliders.index') }}">List of home pictures</a></li>
                </ul>
            </li>

            <li>
                <form class="logout" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-link">Log out</button>
                </form>
            </li>
        </ul>
    </nav>
</nav>
