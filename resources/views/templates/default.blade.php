<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
<head>
    <title>SB&DT HomeCraft Premium Quality</title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="google-site-verification" content="wOJE027oWJ5giOYNSXA2ofrMNm9D4uzAUq6Dj5ee5h8" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('/favicon.png') }}" type="image/png">

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon.png') }}">

    <!-- Icons for different sizes -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}">

    <!-- Android Manifest -->
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}">

    <!-- Safari Pinned Tab -->
    <link rel="mask-icon" href="{{ asset('/safari-pinned-tab.svg') }}" color="#5bbad5">

    <!-- Microsoft Tile Icon -->
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="{{ asset('/mstile-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Stylesheets -->
    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <!-- PhotoSwipe CSS -->
    @if (Route::is('product.show'))
        <!-- PhotoSwipe core CSS file -->
        <link rel="stylesheet" href="https://unpkg.com/photoswipe@4.1.3/dist/photoswipe.css">
        <!-- PhotoSwipe default skin CSS file -->
        <link rel="stylesheet" href="https://unpkg.com/photoswipe@4.1.3/dist/default-skin/default-skin.css">
    @endif
</head>
<body>
{{--    <div class="preloader">--}}
{{--        <div class="preloader-body">--}}
{{--            <div class="cssload-container">--}}
{{--                <span></span>--}}
{{--                <span></span>--}}
{{--                <span></span>--}}
{{--                <span></span>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    @include('parts.header')
    <div class="page">
        @yield('content')
    </div>
    @include('parts.footer')
    <div class="snackbars" id="form-output-global"></div>
    <script src="{{asset('js/core.min.js')}}"></script>
    <script src="{{asset('js/script.js')}}"></script>
    @if(Route::is('product.show'))
        <!-- PhotoSwipe JavaScript files -->
        <script src="https://unpkg.com/photoswipe@4.1.3/dist/photoswipe.min.js" defer></script>
        <script src="https://unpkg.com/photoswipe@4.1.3/dist/photoswipe-ui-default.min.js" defer></script>
    @endif
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>
</html>
