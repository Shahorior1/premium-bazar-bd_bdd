<?php
/**
 * Template part for displaying the shopping cart modal
 */
?>
<!-- Shopping Cart Modal -->
<div id="cartModal" class="cart-modal">
    <div class="cart-content">
        <div class="cart-header">
            <h2><?php _e('শপিং কার্ট', 'premium-bazar-bd'); ?></h2>
            <span class="close-cart" onclick="toggleCart()">&times;</span>
        </div>
        <div id="cartItems" class="cart-items">
            <p class="empty-cart"><?php _e('আপনার কার্ট খালি', 'premium-bazar-bd'); ?></p>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <strong><?php _e('মোট:', 'premium-bazar-bd'); ?> ৳<span id="cartTotal">০</span></strong>
            </div>
            <button class="checkout-btn" onclick="showPaymentOptions()">
                <?php _e('অর্ডার সম্পন্ন করুন', 'premium-bazar-bd'); ?>
            </button>
        </div>
    </div>
</div> 