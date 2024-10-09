document.addEventListener('DOMContentLoaded', function () {
    /*=============== SHOW MENU ===============*/
    const showMenu = (toggleId, navId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId);

        toggle.addEventListener('click', () => {
            // Add show-menu class to nav menu
            nav.classList.toggle('show-menu');

            // Add show-icon to show and hide the menu icon
            toggle.classList.toggle('show-icon');

            // Toggle body scroll
            document.body.classList.toggle('no-scroll', nav.classList.contains('show-menu'));
        });
    };

    showMenu('nav-toggle', 'nav-menu');

    const categoryToggle = document.getElementById('categories-toggle');
    const dropdownItems = document.querySelectorAll('.dropdown__item');
    const subitemToggles = document.querySelectorAll('.subitem-toggle');

// Function to close all dropdowns
    const closeAllDropdowns = () => {
        dropdownItems.forEach(item => {
            item.classList.remove('active');
            const menu = item.querySelector('.dropdown__menu');
            if (menu) {
                menu.classList.remove('active');
            }
            const subitems = item.querySelectorAll('.dropdown__subitem');
            subitems.forEach(subitem => subitem.classList.remove('active'));
        });
    };

// Toggle main categories on mobile
    categoryToggle.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent event bubbling
        const isActive = categoryToggle.parentElement.classList.contains('active');
        closeAllDropdowns();
        if (!isActive) {
            categoryToggle.parentElement.classList.add('active');
            const menu = categoryToggle.nextElementSibling;
            if (menu) {
                menu.classList.add('active');
            }
        }
        // Toggle body scroll
        document.body.classList.toggle('no-scroll', !isActive);
    });

// Toggle subcategories on mobile
    subitemToggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.stopPropagation(); // Prevent event bubbling
            e.preventDefault();
            const subitem = toggle.closest('.dropdown__subitem');
            subitem.classList.toggle('active');
        });
    });

// Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown__item')) {
            closeAllDropdowns();
            // Enable body scroll
            document.body.classList.remove('no-scroll');
        }
    });

    document.querySelectorAll('.view-product').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            // Перенаправлення на сторінку продукту
            window.location.href = url;
        });
    });

    /*===========================CART========================*/
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
            const price = this.getAttribute('data-price');
            const img = this.getAttribute('data-img');
            const stockQuantity = parseInt(this.getAttribute('data-quantity'), 10);
            const colorId = this.getAttribute('data-color-id');
            const colorName = this.getAttribute('data-color-name');

            if (stockQuantity > 0) {
                addToCart(id, name, price, img, stockQuantity, colorId, colorName);
            } else {
                alert(`This product${colorName ? ` in color "${colorName}"` : ''} is out of stock and cannot be added to the cart.`);
            }
        });
    });

    function addToCart(id, name, price, img, stockQuantity, colorId, colorName=null) {
        const existingProductIndex = cart.findIndex(product => product.id === id && product.colorId === colorId);

        if (existingProductIndex !== -1) {
            if (cart[existingProductIndex].stockQuantity > 0) {
                cart[existingProductIndex].cartQuantity += 1;
                cart[existingProductIndex].stockQuantity -= 1;
            } else {
                alert(`This product${colorName ? ` in color "${colorName}"` : ''} is out of stock and cannot be added to the cart.`);
                return;
            }
        } else {
            cart.push({
                id,
                name,
                price,
                img,
                cartQuantity: 1,
                stockQuantity: stockQuantity - 1,
                initialCount: stockQuantity,
                colorId,
                colorName // Додаємо назву кольору
            });
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
                        <div class="cart-item-header-text">
                            <h6 class="cart-row-name">${product.name}</h6>
                            ${product.colorName ? `<p><strong>Color: </strong>${product.colorName}</p>` : ''}
                        </div>
                        <button class="btn-remove-item" data-id="${product.id}" data-color-id="${product.colorId}">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                    <div class="cart-item-body">
                        <h6 class="cart-row-price">$${(product.price * product.cartQuantity).toFixed(2)}</h6>
                        <div class="cart-quantity-control">
                            <button class="quantity-control-btn minus" data-id="${product.id}" data-color-id="${product.colorId}">-</button>
                            <input type="number" class="cart-row-quantity" value="${product.cartQuantity}" min="1" max="100" readonly>
                            <button
                                class="quantity-control-btn plus"
                                data-id="${product.id}"
                                data-color-id="${product.colorId}"
                                data-color-name="${product.colorName }"
                            >+</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            cartItemsContainer.appendChild(productElement);
            totalQuantity += product.cartQuantity;
            totalPrice += product.price * product.cartQuantity;
        });

        document.getElementById('cart-quantity').innerText = totalQuantity;
        document.getElementById('cart-quantity-header').innerText = ` ${totalQuantity}`;
        document.getElementById('cart-total-price').innerText = ` $${totalPrice.toFixed(2)}`;

        // Додаємо обробники подій для кнопок +, -, і видалення товару
        document.querySelectorAll('.quantity-control-btn').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                const colorId = this.getAttribute('data-color-id');
                const colorName = this.getAttribute('data-color-name');
                const operation = this.classList.contains('plus') ? 'increment' : 'decrement';
                updateCartItemQuantity(productId, colorId, colorName, operation);
            });
        });

        document.querySelectorAll('.btn-remove-item').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.getAttribute('data-id');
                const colorId = this.getAttribute('data-color-id');
                removeCartItem(productId, colorId);
            });
        });

        function removeCartItem(productId, colorId) {
            const productIndex = cart.findIndex(product => product.id === productId && product.colorId === colorId);
            if (productIndex !== -1) {
                // Відновлення значення stockQuantity
                cart[productIndex].stockQuantity += cart[productIndex].cartQuantity;
                cart.splice(productIndex, 1);
                localStorage.setItem('cart', JSON.stringify(cart)); // Оновлюємо дані в localStorage
                updateCart();
            }
        }


        if (totalQuantity === 0) {
            document.getElementById('paypal-button-container').style.display = 'none';
        } else {
            document.getElementById('paypal-button-container').style.display = 'block';
        }
    }

    function updateCartItemQuantity(productId, colorId, colorName, operation) {
        const productIndex = cart.findIndex(product => product.id === productId && product.colorId === colorId);
        if (productIndex !== -1) {
            if (operation === 'increment') {
                if (cart[productIndex].stockQuantity > 0) {
                    cart[productIndex].cartQuantity += 1;
                    cart[productIndex].stockQuantity -= 1;
                } else {
                    alert(
                        `This product${colorName ? ` in color "${colorName}"` : ''} is out of stock and cannot be added to the cart.`
                    );

                    return;
                }
            } else if (operation === 'decrement' && cart[productIndex].cartQuantity > 1) {
                cart[productIndex].cartQuantity -= 1;
                cart[productIndex].stockQuantity += 1;
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
        }
    }

    function clearCart() {
        // Відновлюємо значення stockQuantity для всіх товарів
        cart.forEach(product => {
            product.stockQuantity = product.initialCount;
        });
        cart = []; // Очищаємо кошик
        localStorage.removeItem('cart'); // Видаляємо дані з localStorage
        updateCart();
    }

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

//===================GALLERY==================//
    const thumbnails = document.querySelectorAll('.thumbnail img');
    const pswpElement = document.querySelector('.pswp');

// Модальне вікно для відео
    const videoModal = document.getElementById("videoModal");
    const modalVideo = document.getElementById("modalVideo");
    const closeModal = document.getElementsByClassName("close")[0];

    closeModal.onclick = function() {
        videoModal.style.display = "none";
        modalVideo.pause();
        modalVideo.removeAttribute("src");
    };

    window.onclick = function(event) {
        if (event.target == videoModal) {
            videoModal.style.display = "none";
            modalVideo.pause();
            modalVideo.removeAttribute("src");
        }
    };

    const initPhotoSwipeFromDOM = function(gallerySelector) {
        const parseThumbnailElements = function(el) {
            const items = [];
            el.querySelectorAll('.gallery-item').forEach(function(linkEl) {
                if (!linkEl.classList.contains('video-item')) {
                    const item = {
                        src: linkEl.getAttribute('href'),
                        w: parseInt(linkEl.getAttribute('data-pswp-width'), 10),
                        h: parseInt(linkEl.getAttribute('data-pswp-height'), 10),
                        msrc: linkEl.querySelector('img') ? linkEl.querySelector('img').getAttribute('src') : null,
                        el: linkEl
                    };
                    items.push(item);
                }
            });
            return items;
        };

        const openPhotoSwipe = function(index, galleryElement) {
            if (!window.PhotoSwipe) {
                console.error('PhotoSwipe is not loaded.');
                return;
            }

            const items = parseThumbnailElements(galleryElement);
            const options = {
                galleryUID: galleryElement.getAttribute('data-pswp-uid'),
                index: index,
                getThumbBoundsFn: function(index) {
                    const thumbnail = items[index].el.querySelector('img');
                    const pageYScroll = window.pageYOffset || document.documentElement.scrollTop;
                    const rect = thumbnail.getBoundingClientRect();
                    return {x: rect.left, y: rect.top + pageYScroll, w: rect.width};
                },
                showHideOpacity: true,
                bgOpacity: 0.8
            };

            const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
        };

        const galleryElements = document.querySelectorAll(gallerySelector);
        galleryElements.forEach(function(galleryEl, i) {
            galleryEl.setAttribute('data-pswp-uid', i + 1);

            galleryEl.querySelectorAll('.gallery-item').forEach(function(linkEl) {
                if (!linkEl.classList.contains('video-item')) {
                    linkEl.addEventListener('click', function(e) {
                        e.preventDefault();
                        const allItems = galleryEl.querySelectorAll('.gallery-item');
                        const index = Array.from(allItems).findIndex(item => item === linkEl);
                        openPhotoSwipe(index, galleryEl);
                    });
                } else {
                    // Для відео відкриваємо модальне вікно
                    linkEl.addEventListener('click', function(e) {
                        e.preventDefault();
                        const videoSrc = linkEl.getAttribute('href');
                        modalVideo.setAttribute('src', videoSrc);
                        videoModal.style.display = "flex";
                    });
                }
            });
        });
    };

    initPhotoSwipeFromDOM('.my-gallery');


    const mainImageLinks = document.querySelectorAll('.gallery-item');
    mainImageLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const allItems = Array.from(document.querySelectorAll('.main-image .gallery-item'));
            const index = allItems.indexOf(this);
            if (window.openPhotoSwipe) {
                openPhotoSwipe(index, document.querySelector('.my-gallery'));
            } else {
                console.error('openPhotoSwipe function is not defined.');
            }
        });
    });

// Обробник натискання на мініатюри
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            const clickedSrc = this.src;

            // Сховати всі великі зображення і відео
            mainImageLinks.forEach(link => {
                link.style.display = 'none'; // Приховати всі блоки <a>
            });

            // Показати велике зображення або відео, відповідне обраній мініатюрі
            mainImageLinks.forEach(link => {
                const mediaElement = link.querySelector('img') || link.querySelector('video');
                if (mediaElement) {
                    // Перевіряємо, чи це відео
                    if (mediaElement.tagName.toLowerCase() === 'video') {
                        const videoPoster = mediaElement.getAttribute('poster'); // отримуємо постер відео
                        if (videoPoster === clickedSrc) {
                            link.style.display = 'block'; // Показати блок <a>
                            // Не показуємо відео, залишаємо його прихованим
                        }
                    } else if (mediaElement.src === clickedSrc) {
                        link.style.display = 'block'; // Показати блок <a> для зображення
                    }
                }
            });

            // Зняття класу 'selected' з усіх мініатюр
            thumbnails.forEach(thumbnail => {
                thumbnail.parentElement.classList.remove('selected');
            });

            // Додавання класу 'selected' до обраної мініатюри
            this.parentElement.classList.add('selected');
        });

        // Встановлення початкового головного зображення
        if (thumbnail.dataset.main === 'true') {
            const initialSrc = thumbnail.src;
            mainImageLinks.forEach(link => {
                const mediaElement = link.querySelector('img') || link.querySelector('video');
                if (mediaElement) {
                    // Перевірка для відео
                    if (mediaElement.tagName.toLowerCase() === 'video') {
                        const videoPoster = mediaElement.getAttribute('poster');
                        if (videoPoster === initialSrc) {
                            link.style.display = 'block'; // Показати блок <a>
                            // Не показуємо відео, залишаємо його прихованим
                        }
                    } else if (mediaElement.src === initialSrc) {
                        link.style.display = 'block'; // Показати блок <a> для зображення
                    } else {
                        link.style.display = 'none';
                    }
                }
            });
            thumbnail.parentElement.classList.add('selected');
        }
    });



    /*=======================================PAYMENT=======================================*/
    function getCartData() {
        const cartData = localStorage.getItem('cart');
        return cartData ? JSON.parse(cartData) : [];
    }

    document.getElementById('paypal-button').addEventListener('click', function () {
        // Отримати дані з кошика
        const cartData = getCartData(); // Реалізуйте цю функцію для отримання даних з кошика

        // Перевірка, чи кошик порожній
        if (cartData && cartData.length > 0) {
            // Збережіть кошик в localStorage на випадок невдалої оплати
            localStorage.setItem('cart_backup', JSON.stringify(cartData));

            // Показати попап
            const popup = document.getElementById('paypal-popup');
            popup.style.display = 'flex';

            // Надіслати дані на сервер для перевірки
            fetch('/api/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ cart: cartData })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Перенаправити на сторінку оплати PayPal після невеликої затримки
                        setTimeout(function() {
                            window.location.href = data.redirect_url;
                        }, 2000); // Затримка 2 секунди
                    } else {
                        // Сховати попап, якщо перевірка не пройшла успішно
                        popup.style.display = 'none';
                        alert('Product verification process: ' + data.message);
                    }
                })
                .catch(error => {
                    // Сховати попап у випадку помилки
                    popup.style.display = 'none';
                    console.error('Error:', error);
                });
        } else {
            // Вивести повідомлення, що кошик порожній
            alert('Your cart is empty. Please add items to your cart before proceeding to checkout.');
        }
    });
});

