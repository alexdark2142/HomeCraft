<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Handmade Wood Store</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="logo">
            <img src="{{asset('img/logo.png')}}" alt="Handmade Wood Store">
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
        <div class="cta">
            <a href="#" class="btn">Shop Now</a>
        </div>
    </div>
</header>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Welcome to our Handmade Wood Store</h1>
            <p>Discover the beauty of artisan-crafted wood products</p>
            <a href="#" class="btn">Explore Now</a>
        </div>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2>Featured Products</h2>
        <div class="product-grid">
            <div class="product-item">
                <img src="product1.jpg" alt="Product 1">
                <h3>Handcrafted Wooden Bowl</h3>
                <p>$29.99</p>
                <a href="#" class="btn">View Details</a>
            </div>
            <div class="product-item">
                <img src="product2.jpg" alt="Product 2">
                <h3>Wooden Serving Tray</h3>
                <p>$39.99</p>
                <a href="#" class="btn">View Details</a>
            </div>
            <div class="product-item">
                <img src="product3.jpg" alt="Product 3">
                <h3>Wooden Cutting Board</h3>
                <p>$24.99</p>
                <a href="#" class="btn">View Details</a>
            </div>
        </div>
    </div>
</section>

<section class="about-us">
    <div class="container">
        <h2>About Us</h2>
        <div class="about-content">
            <p>We are passionate about creating handcrafted wood products that bring warmth and beauty to your home. Our skilled artisans carefully select the finest wood materials and use traditional techniques to craft each piece with precision and care.</p>
            <p>At our Handmade Wood Store, we value quality, craftsmanship, and sustainability. We believe in preserving the art of woodworking and sharing the natural beauty of wood with the world.</p>
        </div>
    </div>
</section>

<section class="testimonials">
    <div class="container">
        <h2>What Our Customers Say</h2>
        <div class="testimonial-grid">
            <div class="testimonial-item">
                <blockquote>
                    "Absolutely stunning craftsmanship! I purchased a custom-made wooden table from Handmade Wood Store, and I couldn't be happier with the quality and attention to detail. Highly recommend!"
                </blockquote>
                <cite>- Emily S.</cite>
            </div>
            <div class="testimonial-item">
                <blockquote>
                    "I've been searching for the perfect wooden gift for my friend's wedding, and Handmade Wood Store had exactly what I was looking for. The personalized engraving added a special touch!"
                </blockquote>
                <cite>- Jason R.</cite>
            </div>
        </div>
    </div>
</section>

<section class="gallery">
    <div class="container">
        <h2>Gallery</h2>
        <div class="gallery-images">
            <div class="gallery-item">
                <img src="gallery1.jpg" alt="Gallery Image 1">
            </div>
            <div class="gallery-item">
                <img src="gallery2.jpg" alt="Gallery Image 2">
            </div>
            <div class="gallery-item">
                <img src="gallery3.jpg" alt="Gallery Image 3">
            </div>
            <!-- Add more gallery items as needed -->
        </div>
    </div>
</section>

<section class="contact">
    <div class="container">
        <h2>Contact Us</h2>
        <div class="contact-info">
            <p>If you have any questions or inquiries, feel free to contact us:</p>
            <ul>
                <li>Email: info@handmadewoodstore.com</li>
                <li>Phone: +123-456-7890</li>
                <li>Address: 123 Wood Street, City, Country</li>
            </ul>
        </div>
        <div class="contact-form">
            <h3>Send us a message</h3>
            <form action="#" method="post">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <p class="company-name">
                    <span class="small">SB&amp;DT</span><br>
                    <span class="large">HomeCraft</span><br>
                    <span class="small">Premium Quality</span>
                </p>
            </div>
            <div class="footer-links">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Products</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa fa-facebook"></i></a>
                <a href="#" class="icon"><i class="fa fa-twitter"></i></a>
                <a href="#" class="icon"><i class="fa fa-instagram"></i></a>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2024 SB&amp;DT HomeCraft Premium Quality. All rights reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
