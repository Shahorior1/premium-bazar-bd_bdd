// Shopping cart functionality
let cart = [];
let cartTotal = 0;

// DOM elements
const cartModal = document.getElementById('cartModal');
const cartItems = document.getElementById('cartItems');
const cartCount = document.getElementById('cartCount');
const cartTotalElement = document.getElementById('cartTotal');

// Initialize cart from localStorage
function initCart() {
    const savedCart = localStorage.getItem('premiumBazarCart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCartDisplay();
    }
}

// Save cart to localStorage
function saveCart() {
    localStorage.setItem('premiumBazarCart', JSON.stringify(cart));
}

// Add item to cart
function addToCart(name, price, image) {
    const existingItem = cart.find(item => item.name === name);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: name,
            price: price,
            image: image,
            quantity: 1
        });
    }
    
    updateCartDisplay();
    saveCart();
    showAddToCartNotification(name);
}

// Remove item from cart
function removeFromCart(name) {
    cart = cart.filter(item => item.name !== name);
    updateCartDisplay();
    saveCart();
}

// Update item quantity
function updateQuantity(name, change) {
    const item = cart.find(item => item.name === name);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(name);
        } else {
            updateCartDisplay();
            saveCart();
        }
    }
}

// Update cart display
function updateCartDisplay() {
    // Update cart count
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
    
    // Update cart total
    cartTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cartTotalElement.textContent = cartTotal.toLocaleString();
    
    // Update cart items display
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Your cart is empty</p>';
    } else {
        cartItems.innerHTML = cart.map(item => `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}">
                <div class="cart-item-info">
                    <h4>${item.name}</h4>
                    <div class="cart-item-price">৳${item.price.toLocaleString()}</div>
                    <div class="cart-item-controls">
                        <button class="quantity-btn" onclick="updateQuantity('${item.name}', -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="quantity-btn" onclick="updateQuantity('${item.name}', 1)">+</button>
                        <button class="remove-item" onclick="removeFromCart('${item.name}')">Remove</button>
                    </div>
                </div>
            </div>
        `).join('');
    }
}

// Toggle cart modal
function toggleCart() {
    cartModal.style.display = cartModal.style.display === 'block' ? 'none' : 'block';
}

// Show add to cart notification
function showAddToCartNotification(productName) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-check-circle"></i>
            <span>${productName} কার্টে যোগ হয়েছে!</span>
        </div>
    `;
    
    // Add notification styles
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        z-index: 3000;
        animation: slideInRight 0.3s ease, slideOutRight 0.3s ease 2.7s;
        max-width: 300px;
        font-family: 'Noto Sans Bengali', 'Inter', sans-serif;
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Payment selection variables
let selectedPaymentMethod = 'cash_on_delivery';
let selectedAddress = null;
let currentDeliveryCharge = 100;

// Show payment options modal
function showPaymentOptions() {
    if (cart.length === 0) {
        alert('আপনার কার্ট খালি!');
        return;
    }
    
    const paymentModal = document.getElementById('paymentModal');
    paymentModal.style.display = 'block';
    toggleCart(); // Close cart modal
}

// Close payment modal
function closePaymentModal() {
    const paymentModal = document.getElementById('paymentModal');
    paymentModal.style.display = 'none';
}

// Select payment method and proceed to address
function selectPayment(paymentMethod) {
    selectedPaymentMethod = paymentMethod;
    closePaymentModal();
    showAddressModal();
}

// Show address modal
function showAddressModal() {
    const addressModal = document.getElementById('addressModal');
    addressModal.style.display = 'block';
    updateDeliveryCharge(); // Initialize delivery charge
}

// Close address modal
function closeAddressModal() {
    const addressModal = document.getElementById('addressModal');
    addressModal.style.display = 'none';
}

// Update delivery charge based on district selection
function updateDeliveryCharge() {
    const districtSelect = document.getElementById('district');
    const deliveryChargeElement = document.getElementById('deliveryChargeAmount');
    
    if (!districtSelect || !deliveryChargeElement) return;
    
    const selectedDistrict = districtSelect.value;
    
    // Dhaka and nearby areas have lower delivery charge
    const dhakaAreas = ['dhaka', 'gazipur', 'narayanganj'];
    
    if (dhakaAreas.includes(selectedDistrict)) {
        currentDeliveryCharge = 80;
        deliveryChargeElement.textContent = '৳৮০';
    } else if (selectedDistrict && selectedDistrict !== '') {
        currentDeliveryCharge = 120;
        deliveryChargeElement.textContent = '৳১২০';
    } else {
        currentDeliveryCharge = 100;
        deliveryChargeElement.textContent = '৳১০০';
    }
}

// Handle address form submission
function handleAddressSubmission(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const addressData = {
        customerName: formData.get('customerName'),
        customerPhone: formData.get('customerPhone'),
        customerEmail: formData.get('customerEmail') || '',
        district: formData.get('district'),
        upazila: formData.get('upazila'),
        fullAddress: formData.get('fullAddress'),
        deliveryCharge: currentDeliveryCharge
    };
    
    // Validate required fields
    if (!addressData.customerName || !addressData.customerPhone || 
        !addressData.district || !addressData.upazila || !addressData.fullAddress) {
        alert('অনুগ্রহ করে সকল প্রয়োজনীয় ক্ষেত্র পূরণ করুন');
        return;
    }
    
    // Validate phone number (basic validation)
    const phoneRegex = /^01[3-9]\d{8}$/;
    if (!phoneRegex.test(addressData.customerPhone.replace(/\s+/g, ''))) {
        alert('অনুগ্রহ করে সঠিক মোবাইল নম্বর দিন (০১xxxxxxxxx)');
        return;
    }
    
    // Validate email if provided
    if (addressData.customerEmail) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(addressData.customerEmail)) {
            alert('অনুগ্রহ করে সঠিক ইমেইল ঠিকানা দিন');
            return;
        }
    }
    
    selectedAddress = addressData;
    closeAddressModal();
    checkout(selectedPaymentMethod, addressData);
}

// Updated checkout function
async function checkout(paymentMethod = 'cash_on_delivery', addressData = null) {
    if (cart.length === 0) {
        alert('আপনার কার্ট খালি!');
        return;
    }
    
    // Show loading notification
    showPaymentProcessingNotification(paymentMethod);
    
    try {
        // Calculate subtotal
        const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        
        // Generate unique order number
        const orderNumber = generateOrderNumber();
        
        // Prepare order data
        const orderData = {
            orderNumber: orderNumber,
            items: cart.map(item => ({
                name: item.name,
                price: item.price,
                quantity: item.quantity,
                image: item.image || ''
            })),
            subtotal: subtotal,
            deliveryCharge: addressData ? addressData.deliveryCharge : currentDeliveryCharge,
            paymentMethod: paymentMethod,
            customerInfo: addressData || null,
            timestamp: new Date().toISOString()
        };

        // For Cash on Delivery orders, create Pathao delivery order
        if (paymentMethod === 'cash_on_delivery' && addressData && window.pathaoAPI) {
            try {
                showPathaoProcessingNotification();
                const pathaoResponse = await window.pathaoAPI.createDeliveryOrder(orderData);
                
                // Store Pathao consignment ID for tracking
                orderData.pathaoConsignmentId = pathaoResponse.consignment_id;
                orderData.pathaoTrackingCode = pathaoResponse.invoice_id;
                
                console.log('Pathao delivery order created:', pathaoResponse);
                showPathaoSuccessNotification(pathaoResponse.invoice_id);
            } catch (pathaoError) {
                console.error('Pathao API error:', pathaoError);
                // Continue with order even if Pathao fails
                showPathaoErrorNotification();
            }
        }
        
        // Save order data to localStorage for the confirmation page
        localStorage.setItem('lastOrderData', JSON.stringify(orderData));
        
        // Clear cart after checkout
        cart = [];
        updateCartDisplay();
        saveCart();
        
        // Redirect to order confirmation page after a delay
        setTimeout(() => {
            window.location.href = 'order-confirmation.html';
        }, 2000);
        
    } catch (error) {
        console.error('Checkout error:', error);
        alert('অর্ডার প্রসেসিং এ সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।');
        
        // Remove loading notification
        const notification = document.querySelector('.payment-processing-notification');
        if (notification && notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }
}

// Generate unique order number
function generateOrderNumber() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hour = String(now.getHours()).padStart(2, '0');
    const minute = String(now.getMinutes()).padStart(2, '0');
    const random = Math.floor(Math.random() * 100).toString().padStart(2, '0');
    
    return `PBD-${year}${month}${day}-${hour}${minute}${random}`;
}

// Show Pathao processing notification
function showPathaoProcessingNotification() {
    const notification = document.createElement('div');
    notification.className = 'pathao-processing-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <div class="pathao-logo">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <div class="notification-text">
                <h4>Pathao ডেলিভারি অর্ডার তৈরি করা হচ্ছে...</h4>
                <p>আপনার অর্ডারটি Pathao ডেলিভারি সিস্টেমে যুক্ত করা হচ্ছে</p>
            </div>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        z-index: 10000;
        max-width: 350px;
        font-family: 'Noto Sans Bengali', 'Inter', sans-serif;
        animation: slideInRight 0.5s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Store reference for later removal
    window.pathaoNotification = notification;
}

// Show Pathao success notification
function showPathaoSuccessNotification(trackingCode) {
    // Remove processing notification
    if (window.pathaoNotification && window.pathaoNotification.parentNode) {
        window.pathaoNotification.parentNode.removeChild(window.pathaoNotification);
    }
    
    const notification = document.createElement('div');
    notification.className = 'pathao-success-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-text">
                <h4>Pathao ডেলিভারি অর্ডার সফল!</h4>
                <p>ট্র্যাকিং কোড: <strong>${trackingCode}</strong></p>
                <p>আপনি শীঘ্রই ডেলিভারি আপডেট পাবেন</p>
            </div>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
        z-index: 10000;
        max-width: 350px;
        font-family: 'Noto Sans Bengali', 'Inter', sans-serif;
        animation: slideInRight 0.5s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Show Pathao error notification
function showPathaoErrorNotification() {
    // Remove processing notification
    if (window.pathaoNotification && window.pathaoNotification.parentNode) {
        window.pathaoNotification.parentNode.removeChild(window.pathaoNotification);
    }
    
    const notification = document.createElement('div');
    notification.className = 'pathao-error-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="notification-text">
                <h4>Pathao ডেলিভারি সংযোগে সমস্যা</h4>
                <p>আপনার অর্ডার গ্রহণ করা হয়েছে</p>
                <p>আমরা ম্যানুয়ালি ডেলিভারি ব্যবস্থা করব</p>
            </div>
        </div>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3);
        z-index: 10000;
        max-width: 350px;
        font-family: 'Noto Sans Bengali', 'Inter', sans-serif;
        animation: slideInRight 0.5s ease;
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Smooth scroll to products section
function scrollToProducts() {
    document.getElementById('products').scrollIntoView({
        behavior: 'smooth'
    });
}

// Mobile menu toggle
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const hamburger = document.querySelector('.hamburger');
    
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
}

// Search functionality
function searchProducts(query) {
    const productCards = document.querySelectorAll('.product-card');
    const searchQuery = query.toLowerCase();
    
    productCards.forEach(card => {
        const productName = card.querySelector('h3').textContent.toLowerCase();
        const productDescription = card.querySelector('p').textContent.toLowerCase();
        
        if (productName.includes(searchQuery) || productDescription.includes(searchQuery)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Filter products by category
function filterProducts(category) {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        if (category === 'all') {
            card.style.display = 'block';
        } else {
            const productName = card.querySelector('h3').textContent.toLowerCase();
            if (productName.includes(category.toLowerCase())) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
}

// Form submission handling
function handleContactForm(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const name = formData.get('name') || event.target.elements[0].value;
    const email = formData.get('email') || event.target.elements[1].value;
    const phone = formData.get('phone') || event.target.elements[2].value;
    const message = formData.get('message') || event.target.elements[3].value;
    
    // Basic validation
    if (!name || !email || !phone || !message) {
        alert('অনুগ্রহ করে সকল ক্ষেত্র পূরণ করুন');
        return;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('অনুগ্রহ করে একটি বৈধ ইমেইল ঠিকানা দিন');
        return;
    }
    
    // Simulate form submission
    alert(`আপনার বার্তার জন্য ধন্যবাদ, ${name}! আমরা ২৪ ঘন্টার মধ্যে আপনার সাথে যোগাযোগ করব।`);
    event.target.reset();
}

// Scroll animations
function handleScrollAnimations() {
    const animateElements = document.querySelectorAll('.product-card, .about-feature, .contact-item, .review-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'slideInUp 0.6s ease forwards';
            }
        });
    }, {
        threshold: 0.1
    });
    
    animateElements.forEach(element => {
        observer.observe(element);
    });
}

// Header scroll effect
function handleHeaderScroll() {
    const header = document.querySelector('.header');
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            header.style.background = 'rgba(44, 90, 160, 0.95)';
            header.style.backdropFilter = 'blur(10px)';
        } else {
            header.style.background = 'linear-gradient(135deg, #2c5aa0 0%, #1e3a66 100%)';
            header.style.backdropFilter = 'none';
        }
        
        lastScrollTop = scrollTop;
    });
}

// Product image lazy loading
function handleImageLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Product quick view
function showQuickView(productName, productImage, productPrice, productDescription) {
    const quickViewModal = document.createElement('div');
    quickViewModal.className = 'quick-view-modal';
    quickViewModal.innerHTML = `
        <div class="quick-view-content">
            <span class="close-quick-view" onclick="closeQuickView()">&times;</span>
            <div class="quick-view-details">
                <img src="${productImage}" alt="${productName}">
                <div class="quick-view-info">
                    <h2>${productName}</h2>
                    <p>${productDescription}</p>
                    <div class="quick-view-price">৳${productPrice.toLocaleString()}</div>
                    <button class="add-to-cart" onclick="addToCart('${productName}', ${productPrice}, '${productImage}'); closeQuickView();">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
    `;
    
    quickViewModal.style.cssText = `
        display: block;
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        animation: fadeIn 0.3s ease;
    `;
    
    document.body.appendChild(quickViewModal);
}

function closeQuickView() {
    const quickViewModal = document.querySelector('.quick-view-modal');
    if (quickViewModal) {
        quickViewModal.remove();
    }
}

// Newsletter subscription
function subscribeNewsletter() {
    const email = prompt('Enter your email to subscribe to our newsletter:');
    
    if (email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailRegex.test(email)) {
            alert('Thank you for subscribing! You will receive updates about our latest products and offers.');
        } else {
            alert('Please enter a valid email address.');
        }
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart
    initCart();
    
    // Set up mobile menu toggle
    const hamburger = document.querySelector('.hamburger');
    if (hamburger) {
        hamburger.addEventListener('click', toggleMobileMenu);
    }
    
    // Set up contact form
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactForm);
    }
    
    // Set up address form
    const addressForm = document.getElementById('addressForm');
    if (addressForm) {
        addressForm.addEventListener('submit', handleAddressSubmission);
    }
    
    // Set up scroll animations
    handleScrollAnimations();
    
    // Set up header scroll effect
    handleHeaderScroll();
    
    // Set up image lazy loading
    handleImageLazyLoading();
    
    // Initialize reviews slider
    initReviewsSlider();
    
    // Start auto-slide
    startAutoSlide();
    
    // Set up touch events for slider
    const reviewsSlider = document.querySelector('.reviews-slider');
    if (reviewsSlider) {
        reviewsSlider.addEventListener('touchstart', handleTouchStart, { passive: true });
        reviewsSlider.addEventListener('touchend', handleTouchEnd, { passive: true });
        
        // Pause auto-slide on hover
        reviewsSlider.addEventListener('mouseenter', stopAutoSlide);
        reviewsSlider.addEventListener('mouseleave', startAutoSlide);
    }
    
    // Close cart when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === cartModal) {
            toggleCart();
        }
        
        // Close payment modal when clicking outside
        const paymentModal = document.getElementById('paymentModal');
        if (event.target === paymentModal) {
            closePaymentModal();
        }
        
        // Close address modal when clicking outside
        const addressModal = document.getElementById('addressModal');
        if (event.target === addressModal) {
            closeAddressModal();
        }
    });
    
    // Close mobile menu when clicking nav links
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            const navMenu = document.querySelector('.nav-menu');
            const hamburger = document.querySelector('.hamburger');
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });
    
    // Add smooth scrolling to all anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading states for buttons
    const buttons = document.querySelectorAll('button');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 500);
            }
        });
    });
});

// Add custom styles for animations and notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .cart-notification .notification-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .cart-notification i {
        font-size: 1.2rem;
    }
    
    .quick-view-content {
        background: white;
        margin: 5% auto;
        padding: 2rem;
        border-radius: 20px;
        width: 90%;
        max-width: 800px;
        position: relative;
    }
    
    .close-quick-view {
        position: absolute;
        top: 1rem;
        right: 1.5rem;
        font-size: 2rem;
        cursor: pointer;
        color: #aaa;
    }
    
    .close-quick-view:hover {
        color: #000;
    }
    
    .quick-view-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: center;
    }
    
    .quick-view-info h2 {
        color: #2c5aa0;
        margin-bottom: 1rem;
    }
    
    .quick-view-price {
        font-size: 1.5rem;
        color: #ff6b6b;
        font-weight: 700;
        margin: 1rem 0;
    }
    
    .hamburger.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-5px, 6px);
    }
    
    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }
    
    .hamburger.active span:nth-child(3) {
        transform: rotate(45deg) translate(-5px, -6px);
    }
    
    .loading {
        position: relative;
        pointer-events: none;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @media (max-width: 768px) {
        .quick-view-details {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .quick-view-content {
            width: 95%;
            margin: 10% auto;
            padding: 1.5rem;
        }
    }
`;

document.head.appendChild(style);

// Performance optimization: Debounce scroll events
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Apply debounce to scroll handler
const debouncedScrollHandler = debounce(handleHeaderScroll, 10);
window.addEventListener('scroll', debouncedScrollHandler);

// Reviews Slider Functionality
let currentSlide = 0;
let totalSlides = 0;
let slidesPerView = 1;

function initReviewsSlider() {
    const reviewsTrack = document.getElementById('reviewsTrack');
    const sliderDots = document.getElementById('sliderDots');
    const reviewCards = document.querySelectorAll('.review-card');
    
    if (!reviewsTrack || !sliderDots || reviewCards.length === 0) return;
    
    totalSlides = reviewCards.length;
    
    // Set slides per view to always show one review at a time
    const updateSlidesPerView = () => {
        slidesPerView = 1; // Always show one slide at a time
    };
    
    updateSlidesPerView();
    
    // Create dots - one for each slide
    sliderDots.innerHTML = '';
    for (let i = 0; i < totalSlides; i++) {
        const dot = document.createElement('span');
        dot.className = 'dot';
        dot.onclick = () => goToSlide(i);
        sliderDots.appendChild(dot);
    }
    
    updateSlider();
    
    // Update on window resize
    window.addEventListener('resize', () => {
        updateSlidesPerView();
        updateSlider();
    });
}

function moveSlide(direction) {
    const maxSlide = totalSlides - 1;
    currentSlide += direction;
    
    if (currentSlide < 0) {
        currentSlide = maxSlide; // Loop to last slide
    } else if (currentSlide > maxSlide) {
        currentSlide = 0; // Loop to first slide
    }
    
    updateSlider();
}

function goToSlide(slideIndex) {
    currentSlide = slideIndex;
    updateSlider();
}

function updateSlider() {
    const reviewsTrack = document.getElementById('reviewsTrack');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (!reviewsTrack) return;
    
    // Calculate translate percentage for single slide view
    const translateX = -currentSlide * 100;
    
    reviewsTrack.style.transform = `translateX(${translateX}%)`;
    
    // Update dots
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });
    
    // Update button states (remove disabled state for infinite loop)
    if (prevBtn) prevBtn.disabled = false;
    if (nextBtn) nextBtn.disabled = false;
}

// Auto-slide functionality
let autoSlideInterval;

function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        moveSlide(1);
    }, 5000); // Change slide every 5 seconds
}

function stopAutoSlide() {
    if (autoSlideInterval) {
        clearInterval(autoSlideInterval);
    }
}

// Touch/Swipe functionality for mobile
let touchStartX = 0;
let touchEndX = 0;

function handleTouchStart(e) {
    touchStartX = e.changedTouches[0].screenX;
}

function handleTouchEnd(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
}

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            moveSlide(1); // Swipe left - next slide
        } else {
            moveSlide(-1); // Swipe right - previous slide
        }
    }
}

// Show payment processing notification
function showPaymentProcessingNotification(paymentMethod) {
    const messages = {
        'cash_on_delivery': 'অর্ডার প্রসেসিং করা হচ্ছে...',
        'bkash': 'bKash পেমেন্ট তৈরি করা হচ্ছে...'
    };
    
    const notification = document.createElement('div');
    notification.className = 'payment-processing-notification';
    notification.innerHTML = `
        <div class="notification-content">
            <div class="processing-spinner">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <span>${messages[paymentMethod]}</span>
        </div>
    `;
    
    // Add notification styles
    notification.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, #2c5aa0 0%, #1e3a66 100%);
        color: white;
        padding: 2rem 3rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(44, 90, 160, 0.3);
        z-index: 3000;
        animation: fadeIn 0.3s ease;
        font-family: 'Noto Sans Bengali', 'Inter', sans-serif;
        text-align: center;
        min-width: 250px;
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification when redirecting
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 1400);
} 