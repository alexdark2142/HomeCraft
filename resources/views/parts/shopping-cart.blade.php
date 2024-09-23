<div class="shopping-cart navbar-modern-project">
    <div class="shopping-cart-header">
        <h4 class="shopping-cart-title">Shopping cart</h4>
        <div class="shopping-cart-btn">
            <div class="shopping-cart-close" id="cartClose">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <div class="shopping-cart-content navbar-modern-project-content">
        <div>
            <div class="cart-inline">
                <div class="cart-row-body" id="cart-items"></div>

                <div class="cart-inline-header">
                    <h5 class="cart-inline-title">In cart:<span id="cart-quantity-header"> 0</span> Products</h5>
                    <h6 class="cart-inline-title">Total price:<span id="cart-total-price"> $0</span></h6>
                </div>

                <div class="cart-footer">
                    <div class="cart-footer_btn">
                        <a
                            class="button button-clear-cart button-default-outline-2 button-wapasha"
                            href="#"
                            id="clear-cart"
                        >
                            Clear cart
                        </a>
                        <div id="paymentResponse" class="hidden" style="display: none;"></div>
                        <div id="paypal-button-container" class="paypal-button-container">
                            @include('parts.checkout.paypal-button')
{{--                            @include('parts.checkout.card')--}}
                        </div>
                        <p id="result-message" style="display: none;"></p>
                    </div>
                </div>
            </div>

            <ul class="navbar-modern-contacts">
                <li>
                    <div class="unit unit-spacing-sm">
                        <div class="unit-left">
                            <span class="icon fa fa-phone"></span>
                        </div>
                        <div class="unit-body">
                            <a class="link-phone" href="tel:+1 403-877-9890">+1 403-877-9890</a>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="unit unit-spacing-sm">
                        <div class="unit-left">
                            <span class="icon fa fa-envelope"></span>
                        </div>
                        <div class="unit-body">
                            <a class="link-email" href="mailto:homecraft1sbdt@gmail.com">
                                homecraft1sbdt@gmail.com
                            </a>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="unit unit-spacing-sm">
                        <div class="unit-left">
                            <span class="icon fa fa-globe"></span>
                        </div>
                        <div class="unit-body">
                            <a class="link-location disabled-link" href="#">
                                Red Deer - Alberta - Canada
                            </a>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="list-inline navbar-modern-list-social">
                <li>
                    <a
                        class="icon fa fa-facebook"
                        target="_blank"
                        href="https://www.facebook.com/people/HomeCraft/61561930961959"
                    ></a>
                </li>

                <li>
                    <a class="icon fa fa-instagram"
                       target="_blank"
                       href="https://www.instagram.com/homecraftsb_dt?igsh=dHE1OHZ6ZWhhc2pz&utm_source=qr"
                    ></a>
                </li>
            </ul>
        </div>
    </div>
</div>
