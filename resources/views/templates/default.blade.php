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

        //cart
        document.addEventListener('DOMContentLoaded', function () {
            let cart = [];

            // Перевірка, чи є дані в localStorage
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                cart = JSON.parse(savedCart);
                updateCart();
            }

            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const price = parseFloat(this.getAttribute('data-price'));
                    const img = this.getAttribute('data-img');
                    addToCart(id, name, price, img);
                });
            });

            function addToCart(id, name, price, img) {
                const existingProductIndex = cart.findIndex(product => product.id === id);

                if (existingProductIndex !== -1) {
                    cart[existingProductIndex].quantity += 1;
                } else {
                    cart.push({ id, name, price, img, quantity: 1 });
                }

                // Зберігання кошика в localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                updateCart();
            }

            function updateCart() {
                const cartItemsContainer = document.getElementById('cart-items');
                cartItemsContainer.innerHTML = '';

                let totalQuantity = 0;
                let totalPrice = 0;

                cart.forEach(product => {
                    const productElement = document.createElement('div');
                    productElement.innerHTML = `
                        <div class="unit align-items-center cart-row-item">
                            <div class="unit-left">
                                <a class="cart-row-figure" href="#">
                                    <img class="cart-img" src="${product.img}" alt="${product.name}" width="100" height="100" />
                                </a>
                            </div>
                            <div class="unit-body">
                                <div class="cart-item-header">
                                    <h6 class="cart-row-name">${product.name}</h6>
                                    <button class="btn-remove-item" data-id="${product.id}">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </div>
                                <div class="cart-item-body">
                                    <h6 class="cart-row-price">$${(product.price * product.quantity).toFixed(2)}</h6>
                                    <div class="cart-quantity-control">
                                        <button class="quantity-control-btn minus" data-id="${product.id}">-</button>
                                        <input type="number" class="cart-row-quantity" value="${product.quantity}" min="1" max="100" readonly>
                                        <button class="quantity-control-btn plus" data-id="${product.id}">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    cartItemsContainer.appendChild(productElement);
                    totalQuantity += product.quantity;
                    totalPrice += product.price * product.quantity;
                });

                document.getElementById('cart-count').innerText = totalQuantity;
                document.getElementById('cart-count-header').innerText = ` ${totalQuantity}`;
                document.getElementById('cart-total-price').innerText = ` $${totalPrice.toFixed(2)}`;

                // Додаємо обробники подій для кнопок + і -
                document.querySelectorAll('.quantity-control-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const productId = this.closest('.cart-row-item').querySelector('.btn-remove-item').getAttribute('data-id');
                        const operation = this.classList.contains('plus') ? 'increment' : 'decrement';
                        updateCartItemQuantity(productId, operation);
                    });
                });
            }

            function updateCartItemQuantity(productId, operation) {
                const productIndex = cart.findIndex(product => product.id === productId);
                if (productIndex !== -1) {
                    if (operation === 'increment') {
                        cart[productIndex].quantity += 1;
                    } else if (operation === 'decrement' && cart[productIndex].quantity > 1) {
                        cart[productIndex].quantity -= 1;
                    }
                    localStorage.setItem('cart', JSON.stringify(cart));
                    updateCart();
                }
            }

            function clearCart() {
                cart = []; // Очищаємо кошик
                localStorage.removeItem('cart'); // Видаляємо дані з localStorage
                updateCart();
            }

            document.getElementById('cart-items').addEventListener('click', function (event) {
                if (event.target.closest('.btn-remove-item')) {
                    const id = event.target.closest('.btn-remove-item').getAttribute('data-id');
                    const productIndex = cart.findIndex(product => product.id === id);
                    if (productIndex !== -1) {
                        cart.splice(productIndex, 1);
                        localStorage.setItem('cart', JSON.stringify(cart)); // Оновлюємо дані в localStorage
                        updateCart();
                    }
                }
            });

            document.getElementById('clear-cart').addEventListener('click', clearCart);

            // Відкриття кошика
            document.getElementById('cartButton').addEventListener('click', function () {
                document.querySelector('.shopping-cart.navbar-modern-project').classList.add('open');
                document.querySelector('.shopping-cart-btn').classList.add('active');
            });

            // Закриття кошика
            document.querySelector('.shopping-cart-close').addEventListener('click', function () {
                document.querySelector('.shopping-cart.navbar-modern-project').classList.remove('open');
                document.querySelector('.shopping-cart-btn').classList.remove('active');
            });
        });

    </script>
</body>
</html>
