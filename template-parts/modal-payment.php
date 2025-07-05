<?php
/**
 * Template part for displaying the payment options modal
 */
?>
<!-- Payment Options Modal -->
<div id="paymentModal" class="payment-modal">
    <div class="payment-content">
        <div class="payment-header">
            <h2><i class="fas fa-credit-card"></i> <?php _e('পেমেন্ট পদ্ধতি নির্বাচন করুন', 'premium-bazar-bd'); ?></h2>
            <span class="close-payment" onclick="closePaymentModal()">&times;</span>
        </div>
        <div class="payment-options">
            <div class="payment-option" onclick="selectPayment('cash_on_delivery')">
                <div class="payment-option-icon cod-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="payment-option-info">
                    <h3><?php _e('ক্যাশ অন ডেলিভারি', 'premium-bazar-bd'); ?></h3>
                    <p><?php _e('পণ্য পৌঁছানোর সময় নগদ টাকা প্রদান করুন', 'premium-bazar-bd'); ?></p>
                    <div class="payment-benefits">
                        <span><i class="fas fa-check"></i> <?php _e('নিরাপদ', 'premium-bazar-bd'); ?></span>
                        <span><i class="fas fa-check"></i> <?php _e('সহজ', 'premium-bazar-bd'); ?></span>
                        <span><i class="fas fa-check"></i> <?php _e('কোনো অতিরিক্ত চার্জ নেই', 'premium-bazar-bd'); ?></span>
                    </div>
                </div>
            </div>

            <div class="payment-option" onclick="selectPayment('bkash')">
                <div class="payment-option-icon bkash-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="payment-option-info">
                    <h3><?php _e('bKash পেমেন্ট', 'premium-bazar-bd'); ?></h3>
                    <p><?php _e('bKash এর মাধ্যমে দ্রুত ও নিরাপদ অনলাইন পেমেন্ট', 'premium-bazar-bd'); ?></p>
                    <div class="payment-benefits">
                        <span><i class="fas fa-check"></i> <?php _e('তাৎক্ষণিক পেমেন্ট', 'premium-bazar-bd'); ?></span>
                        <span><i class="fas fa-check"></i> <?php _e('২৪/৭ উপলব্ধ', 'premium-bazar-bd'); ?></span>
                        <span><i class="fas fa-check"></i> <?php _e('নিরাপদ লেনদেন', 'premium-bazar-bd'); ?></span>
                    </div>
                    <div class="bkash-info">
                        <?php
                        $options = get_option('premium_bazar_bd_options');
                        $bkash_number = isset($options['bkash_number']) ? $options['bkash_number'] : '01340-860039';
                        ?>
                        <p><strong><?php _e('আমাদের bKash নম্বর:', 'premium-bazar-bd'); ?></strong> <?php echo esc_html($bkash_number); ?></p>
                        <p class="payment-note"><?php _e('অর্ডার কনফার্ম করার পর আমরা আপনাকে পেমেন্টের নির্দেশনা দেব', 'premium-bazar-bd'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 