<nav class="header">
    <div class="header-logo">
        <a class="logo-link" href="/">
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
    <div class="header-menu" id="nav-content">
        <ul class="menu-list">
            <!-- Пункт меню "Add product" -->
            <li>
                <a class="menu-link {{ request()->routeIs('products.create') ? 'active' : '' }}" href="{{ route('products.create') }}">
                    Add product
                </a>
            </li>

            <li>
                <a class="menu-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    List of Products
                </a>
            </li>

            <li>
                <a class="menu-link {{ request()->routeIs('sliders.create') ? 'active' : '' }}" href="{{ route('sliders.create') }}">
                    Add home picture
                </a>
            </li>

            <li>
                <a class="menu-link {{ request()->routeIs('sliders.index') ? 'active' : '' }}" href="{{ route('sliders.index') }}">
                    List of home pictures
                </a>
            </li>

            <li>
                <a class="menu-link {{ request()->routeIs('categories.create') ? 'active' : '' }}" href="{{ route('categories.create') }}">
                    Add category
                </a>
            </li>

            <li>
                <a class="menu-link {{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    List of categories
                </a>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-link">Log out</button>
                </form>
            </li>
        </ul>

    </div>
</nav>

<script>
    document.getElementById('nav-toggle').addEventListener('click', function() {
        this.classList.toggle('open');
        document.getElementById('nav-content').classList.toggle('open');
    });
</script>
