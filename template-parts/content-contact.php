<?php
/**
 * Template part for displaying the contact section
 */
?>
<section id="contact" class="contact">
    <div class="container">
        <h2 class="section-title"><?php _e('যোগাযোগ করুন', 'premium-bazar-bd'); ?></h2>
        <div class="contact-content">
            <div class="contact-info">
                <?php
                $options = get_option('premium_bazar_bd_options');
                $address = isset($options['address']) ? $options['address'] : 'শাপলা চত্বর, রংপুর';
                $phone = isset($options['phone_number']) ? $options['phone_number'] : '+8801340-860039';
                $email = isset($options['email']) ? $options['email'] : 'info@bazarpremiumbd.com';
                ?>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3><?php _e('ঠিকানা', 'premium-bazar-bd'); ?></h3>
                        <p><?php echo esc_html($address); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3><?php _e('ফোন', 'premium-bazar-bd'); ?></h3>
                        <p><?php echo esc_html($phone); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3><?php _e('ইমেইল', 'premium-bazar-bd'); ?></h3>
                        <p><?php echo esc_html($email); ?></p>
                    </div>
                </div>
            </div>
            <form class="contact-form" id="contactForm">
                <?php wp_nonce_field('contact_form_submit', 'contact_nonce'); ?>
                <input type="text" name="name" placeholder="<?php _e('আপনার নাম', 'premium-bazar-bd'); ?>" required>
                <input type="email" name="email" placeholder="<?php _e('আপনার ইমেইল', 'premium-bazar-bd'); ?>" required>
                <input type="tel" name="phone" placeholder="<?php _e('আপনার ফোন নম্বর', 'premium-bazar-bd'); ?>" required>
                <textarea name="message" placeholder="<?php _e('আপনার বার্তা', 'premium-bazar-bd'); ?>" rows="5" required></textarea>
                <button type="submit"><?php _e('বার্তা পাঠান', 'premium-bazar-bd'); ?></button>
            </form>
        </div>
    </div>
</section>

<script>
jQuery(document).ready(function($) {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'contact_form_submit');
        
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('আপনার বার্তা সফলভাবে পাঠানো হয়েছে।');
                    $('#contactForm')[0].reset();
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