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
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Poppins:300,400,500">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/fonts.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
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
        @include('parts.header')
        <div class="page">
            @yield('content')
        </div>
        @include('parts.footer')
    <div class="snackbars" id="form-output-global"></div>
    <script src="{{asset('js/core.min.js')}}"></script>
    <script src="{{asset('js/script.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let cartButton = document.getElementById('cartButton');
            let cartClose = document.getElementById('cartClose');
            let cartCloseMob = document.getElementById('cartCloseMob');

            if (cartButton) {
                cartButton.addEventListener('click', function() {
                    this.style.display = 'none';
                });
            }

            if (cartClose) {
                cartClose.addEventListener('click', function() {
                    cartButton.style.display = 'block';
                });
            }

            if (cartCloseMob) {
                cartCloseMob.addEventListener('click', function() {
                    setTimeout(function() {
                        cartButton.style.display = 'block';
                    }, 300);
                });
            }
        });

        // Hide the cart initially
        document.querySelector('.cart-inline').style.display = 'none';

        // Function to update visibility of the cart based on item count
        function updateCartVisibility() {
            let itemCount = parseInt(document.querySelector('.cart-inline-header span').textContent);
            if (itemCount > 0) {
                document.querySelector('.cart-inline').style.display = 'block';
            } else {
                document.querySelector('.cart-inline').style.display = 'none';
            }
        }

        // Add event listener to "Add to cart" buttons
        let addToCartButtons = document.querySelectorAll('.product-button .button');
        addToCartButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                console.log(1)
                let itemName = this.closest('.unit').querySelector('.product-title a').textContent;
                let itemPrice = parseFloat(this.closest('.unit').querySelector('.product-price').textContent.replace('$', ''));
                let itemCountElement = document.querySelector('.cart-inline-header span');
                let itemCount = parseInt(itemCountElement.textContent);
                console.log(itemCount)
                itemCount++;
                itemCountElement.textContent = itemCount;

                let totalPriceElement = document.querySelector('.cart-inline-header .cart-inline-title span');
                let totalPrice = parseFloat(totalPriceElement.textContent.replace('$', ''));
                totalPrice += itemPrice;
                totalPriceElement.textContent = '$' + totalPrice.toFixed(2);

                // Update cart visibility
                updateCartVisibility();
            });
        });

        // document.addEventListener('DOMContentLoaded', function() {
        //     let header = document.querySelector('.page-header');
        //     let categories = document.querySelector('.categories');
        //     let swiper = document.querySelector('.swiper-slide');
        //     let sectionProducts = document.querySelector('.section-products');
        //
        //     // Get the height of the header
        //     let headerHeight = header.offsetHeight;
        //     let swiperHeight = swiper.offsetHeight;
        //     let categoriesHeight = categories.offsetHeight;
        //
        //     // Function to adjust the position of categories
        //     function adjustCategoriesPosition() {
        //         let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        //
        //         if (scrollTop > headerHeight + swiperHeight) {
        //             categories.classList.add('fixed');
        //             sectionProducts.style.paddingTop = categoriesHeight + 'px'
        //         } else {
        //             categories.classList.remove('fixed');
        //             sectionProducts.removeAttribute('style')
        //         }
        //     }
        //
        //     // Attach scroll event listener
        //     window.addEventListener('scroll', adjustCategoriesPosition);
        //
        //     // Initial adjustment
        //     adjustCategoriesPosition();
        // });

    </script>
</body>
</html>
