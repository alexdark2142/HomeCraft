<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
<head>
    <title>SB&DT HomeCraft Premium Quality</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport"
          content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
    <!-- Stylesheets-->
    <!--=============== REMIXICONS ===============-->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Poppins:300,400,500">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/fonts.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/header.css')}}">
    @php
        $isIElt10 = str_contains($_SERVER['HTTP_USER_AGENT'], 'MSIE')
        && !str_contains($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0')
        && !str_contains($_SERVER['HTTP_USER_AGENT'], 'Trident/6.0');
    @endphp

    @if($isIElt10)
        <div
            style="background: #212121; padding: 10px 0; box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3); clear: both; text-align:center; position: relative; z-index:1;">
            <a href="https://windows.microsoft.com/en-US/internet-explorer/">
                <img src="{{ asset('images/ie8-panel/warning_bar_0000_us.jpg') }}" border="0" height="42" width="820"
                     alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today.">
            </a>
        </div>
        <script src="{{ asset('js/html5shiv.min.js') }}"></script>
    @endif
</head>
<body>
    <div class="preloader">
        <div class="preloader-body">
            <div class="cssload-container">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
        @include('parts.header-new')
        <div class="page">
            @yield('content')
        </div>
        @include('parts.footer')
    <div class="snackbars" id="form-output-global"></div>
    <script src="{{asset('js/core.min.js')}}"></script>
    <script src="{{asset('js/script.js')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>
