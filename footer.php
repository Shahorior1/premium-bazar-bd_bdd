    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-brand">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            echo '<img src="' . get_template_directory_uri() . '/assets/logo.png" alt="Premium Bazar BD Logo" class="footer-logo">';
                        }
                        ?>
                        <h3><?php bloginfo('name'); ?></h3>
                    </div>
                    <p><?php bloginfo('description'); ?></p>
                    <div class="social-links">
                        <?php
                        $options = get_option('premium_bazar_bd_options');
                        $facebook = isset($options['facebook_url']) ? $options['facebook_url'] : '#';
                        $instagram = isset($options['instagram_url']) ? $options['instagram_url'] : '#';
                        $youtube = isset($options['youtube_url']) ? $options['youtube_url'] : '#';
                        ?>
                        <a href="<?php echo esc_url($facebook); ?>"><i class="fab fa-facebook"></i></a>
                        <a href="<?php echo esc_url($instagram); ?>"><i class="fab fa-instagram"></i></a>
                        <a href="<?php echo esc_url($youtube); ?>"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4><?php _e('দ্রুত লিঙ্ক', 'premium-bazar-bd'); ?></h4>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer',
                        'container' => false,
                        'fallback_cb' => false,
                    ));
                    ?>
                </div>

                <div class="footer-section">
                    <h4><?php _e('ভেষজ ওষুধ', 'premium-bazar-bd'); ?></h4>
                    <?php
                    $product_categories = get_terms('product_category', array('hide_empty' => false));
                    if (!empty($product_categories) && !is_wp_error($product_categories)) :
                    ?>
                    <ul>
                        <?php foreach ($product_categories as $category) : ?>
                        <li><a href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>

                <div class="footer-section">
                    <h4><?php _e('যোগাযোগের তথ্য', 'premium-bazar-bd'); ?></h4>
                    <?php
                    $options = get_option('premium_bazar_bd_options');
                    $address = isset($options['address']) ? $options['address'] : 'শাপলা চত্বর, রংপুর';
                    $phone = isset($options['phone_number']) ? $options['phone_number'] : '+8801340-860039';
                    $email = isset($options['email']) ? $options['email'] : 'info@bazarpremiumbd.com';
                    ?>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($address); ?></p>
                    <p><i class="fas fa-phone"></i> <?php echo esc_html($phone); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo esc_html($email); ?></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>। সকল অধিকার সংরক্ষিত।</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html> 