<?php
/**
 * Template part for displaying the visual reviews section
 */
?>
<section id="visual-reviews" class="visual-reviews">
    <div class="container">
        <h2 class="section-title"><?php echo get_theme_mod('reviews_title', 'আমাদের সন্তুষ্ট গ্রাহকদের ছবি ও মতামত'); ?></h2>
        <p class="section-subtitle"><?php echo get_theme_mod('reviews_subtitle', 'প্রকৃত গ্রাহকদের ছবি এবং তাদের অভিজ্ঞতার সরাসরি প্রমাণ'); ?></p>

        <div class="visual-reviews-grid">
            <?php
            $reviews = get_posts(array(
                'post_type' => 'testimonial',
                'posts_per_page' => 6,
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            foreach ($reviews as $review) :
                $customer_name = get_post_meta($review->ID, '_customer_name', true);
                $rating = get_post_meta($review->ID, '_rating', true);
                $photo = get_the_post_thumbnail_url($review->ID) ?: get_template_directory_uri() . '/assets/Blank Profile.jpg';
                $time_ago = human_time_diff(get_the_time('U', $review), current_time('timestamp')) . ' আগে';
            ?>
            <div class="visual-review-item">
                <div class="review-image-container">
                    <img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_attr($customer_name); ?>" class="customer-photo">
                    <div class="review-overlay">
                        <div class="customer-name"><?php echo esc_html($customer_name); ?></div>
                        <div class="review-stars">
                            <?php for ($i = 0; $i < $rating; $i++) : ?>
                                ⭐
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="review-text">
                    <p><?php echo get_the_content(null, false, $review); ?></p>
                    <div class="review-date"><?php echo esc_html($time_ago); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="reviews-summary">
            <?php
            $total_customers = get_theme_mod('total_customers', '৫০০+');
            $success_rate = get_theme_mod('success_rate', '৯৮%');
            $average_rating = get_theme_mod('average_rating', '৫⭐');
            ?>
            <div class="summary-item">
                <h3><?php echo esc_html($total_customers); ?></h3>
                <p>সন্তুষ্ট গ্রাহক</p>
            </div>
            <div class="summary-item">
                <h3><?php echo esc_html($success_rate); ?></h3>
                <p>সফলতার হার</p>
            </div>
            <div class="summary-item">
                <h3><?php echo esc_html($average_rating); ?></h3>
                <p>গড় রেটিং</p>
            </div>
        </div>
    </div>
</section> 