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

    let cart = [];

    // Перевірка, чи є дані в localStorage
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCart();
    }

    document.querySelectorAll('.view-product').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            // Перенаправлення на сторінку продукту
            window.location.href = `/product/${id}`;
        });
    });



    let popupImg = document.querySelector('.popup-img span')
    if (popupImg) {
        popupImg.onclick = () => {
            const body = document.body;

            // Сховати попап і розблокувати прокрутку сторінки
            document.querySelector('.popup-img').style.display = 'none';
            body.style.overflow = 'auto'; // Розблокування прокрутки сторінки
        };
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

    const thumbnails = document.querySelectorAll('.thumbnail img');
    const mainImage = document.querySelector('.main-image img');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            // Оновлення головного зображення
            mainImage.src = this.src;

            // Зняття класу 'selected' з усіх мініатюр
            thumbnails.forEach(thumbnail => {
                thumbnail.parentElement.classList.remove('selected');
            });

            // Додавання класу 'selected' до обраної мініатюри
            this.parentElement.classList.add('selected');
        });

        // Встановлення початкового головного зображення
        if (thumbnail.dataset.main === 'true') {
            mainImage.src = thumbnail.src;
            thumbnail.parentElement.classList.add('selected');
        }
    });
});

