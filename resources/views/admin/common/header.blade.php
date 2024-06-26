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
            <li><a class="menu-link {{ request()->routeIs('admin.add-product') ? 'active' : '' }}" href="{{ route('admin.add-product') }}">Add product</a></li>
            <li><a class="menu-link {{ request()->routeIs('admin.products') ? 'active' : '' }}" href="{{ route('admin.products') }}">Products</a></li>
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
