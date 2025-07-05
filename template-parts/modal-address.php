<?php
/**
 * Template part for displaying the address modal
 */
?>
<!-- Address Modal -->
<div id="addressModal" class="address-modal">
    <div class="address-content">
        <div class="address-header">
            <h2><i class="fas fa-map-marker-alt"></i> <?php _e('ডেলিভারি ঠিকানা প্রদান করুন', 'premium-bazar-bd'); ?></h2>
            <span class="close-address" onclick="closeAddressModal()">&times;</span>
        </div>
        <form class="address-form" id="addressForm">
            <?php wp_nonce_field('submit_address', 'address_nonce'); ?>
            
            <div class="form-group">
                <label for="customerName"><?php _e('পূর্ণ নাম', 'premium-bazar-bd'); ?> *</label>
                <input type="text" id="customerName" name="customerName" required placeholder="<?php _e('আপনার পূর্ণ নাম লিখুন', 'premium-bazar-bd'); ?>">
            </div>

            <div class="form-group">
                <label for="customerPhone"><?php _e('মোবাইল নম্বর', 'premium-bazar-bd'); ?> *</label>
                <input type="tel" id="customerPhone" name="customerPhone" required placeholder="০১xxxxxxxxx">
            </div>

            <div class="form-group">
                <label for="customerEmail"><?php _e('ইমেইল', 'premium-bazar-bd'); ?> (<?php _e('ঐচ্ছিক', 'premium-bazar-bd'); ?>)</label>
                <input type="email" id="customerEmail" name="customerEmail" placeholder="example@email.com">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="district"><?php _e('জেলা', 'premium-bazar-bd'); ?> *</label>
                    <select id="district" name="district" required onchange="updateDeliveryCharge()">
                        <option value=""><?php _e('জেলা নির্বাচন করুন', 'premium-bazar-bd'); ?></option>
                        <?php
                        $districts = array(
                            'dhaka' => 'ঢাকা',
                            'chittagong' => 'চট্টগ্রাম',
                            'sylhet' => 'সিলেট',
                            'rajshahi' => 'রাজশাহী',
                            'khulna' => 'খুলনা',
                            'barisal' => 'বরিশাল',
                            'rangpur' => 'রংপুর',
                            'mymensingh' => 'ময়মনসিংহ',
                            'comilla' => 'কুমিল্লা',
                            'gazipur' => 'গাজীপুর',
                            'narayanganj' => 'নারায়ণগঞ্জ',
                            'other' => 'অন্যান্য'
                        );
                        foreach ($districts as $value => $label) :
                        ?>
                        <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="upazila"><?php _e('উপজেলা/থানা', 'premium-bazar-bd'); ?> *</label>
                    <input type="text" id="upazila" name="upazila" required placeholder="<?php _e('উপজেলা/থানা নাম', 'premium-bazar-bd'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="fullAddress"><?php _e('সম্পূর্ণ ঠিকানা', 'premium-bazar-bd'); ?> *</label>
                <textarea id="fullAddress" name="fullAddress" required rows="3" placeholder="<?php _e('বাড়ি/হোল্ডিং নম্বর, রোড/পাড়া/মহল্লা, এলাকার নাম', 'premium-bazar-bd'); ?>"></textarea>
            </div>

            <div class="delivery-charge-info">
                <div class="charge-item">
                    <span><?php _e('ডেলিভারি চার্জ:', 'premium-bazar-bd'); ?></span>
                    <span id="deliveryChargeAmount">৳১০০</span>
                </div>
                <div class="charge-note">
                    <i class="fas fa-info-circle"></i>
                    <span><?php _e('ঢাকার ভিতরে ৮০ টাকা, বাইরে ১২০ টাকা', 'premium-bazar-bd'); ?></span>
                </div>
            </div>

            <div class="address-actions">
                <button type="button" class="btn-secondary" onclick="closeAddressModal()"><?php _e('বাতিল', 'premium-bazar-bd'); ?></button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i>
                    <?php _e('ঠিকানা নিশ্চিত করুন', 'premium-bazar-bd'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#addressForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'submit_address');
        
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    closeAddressModal();
                    proceedToPayment(response.data);
                } else {
                    alert('দুঃখিত, একটি সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
                }
            },
            error: function() {
                alert('দুঃখিত, একটি সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
            }
        });
    });
});
</script> 