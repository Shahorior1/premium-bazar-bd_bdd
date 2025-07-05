<?php get_header(); ?>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1><?php echo get_theme_mod('hero_title', 'সম্পূর্ণ ডায়াবেটিস সমাধান'); ?></h1>
                <p class="hero-subtitle"><?php echo get_theme_mod('hero_subtitle', 'প্রাকৃতিক ভেষজ ওষুধের মাধ্যমে আপনার স্বাস্থ্যের সম্পূর্ণ যত্ন নিন এবং ডায়াবেটিস থেকে মুক্তি পান'); ?></p>
                <div class="hero-features">
                    <?php
                    $features = get_theme_mod('hero_features', array(
                        'রক্তে শর্করার মাত্রা কমিয়ে ডায়াবেটিস নিয়ন্ত্রণ করবে',
                        'শারীরিক দূর্বলতা দূর করবে',
                        'উচ্চ রক্তচাপ নিয়ন্ত্রণ করবে',
                        'ডায়াবেটিস জনিত যৌন দূর্বলতা সমাধান করবে',
                        'রক্তে হিমোগ্লোবিনের পরিমাণ ঠিক রাখবে'
                    ));
                    foreach ($features as $feature) :
                    ?>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo esc_html($feature); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="cta-button" onclick="scrollToProducts()"><?php _e('এখনই কিনুন', 'premium-bazar-bd'); ?></button>
            </div>
            <div class="hero-image">
                <?php
                $hero_image = get_theme_mod('hero_image', get_template_directory_uri() . '/assets/Poster.jpg');
                ?>
                <img src="<?php echo esc_url($hero_image); ?>" alt="ডায়াবেটিস সমাধান">
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="products">
    <div class="container">
        <h2 class="section-title"><?php _e('আমাদের প্রিমিয়াম ভেষজ ওষুধ', 'premium-bazar-bd'); ?></h2>
        <p class="section-subtitle"><?php _e('বৈজ্ঞানিকভাবে প্রস্তুতকৃত প্রাকৃতিক ভেষজ ওষুধ যা ডায়াবেটিস নিয়ন্ত্রণে কার্যকর', 'premium-bazar-bd'); ?></p>

        <div class="products-grid">
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC'
            );
            $products = new WP_Query($args);

            if ($products->have_posts()) :
                while ($products->have_posts()) : $products->the_post();
                    $price = get_post_meta(get_the_ID(), '_product_price', true);
                    $sale_price = get_post_meta(get_the_ID(), '_product_sale_price', true);
                    ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail(); ?>
                            <?php endif; ?>
                            <?php if ($sale_price) : ?>
                                <div class="product-badge">সেরা মূল্য</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><a href="<?php the_permalink(); ?>" class="product-link"><?php the_title(); ?></a></h3>
                            <?php the_excerpt(); ?>
                            <div class="product-benefits">
                                <span><i class="fas fa-leaf"></i> প্রাকৃতিক ভেষজ</span>
                                <span><i class="fas fa-shield-alt"></i> ক্লিনিক্যালি পরীক্ষিত</span>
                            </div>
                            <div class="product-price">
                                <?php if ($sale_price) : ?>
                                    <span class="original-price">৳<?php echo number_format($price); ?></span>
                                    <span class="current-price">৳<?php echo number_format($sale_price); ?></span>
                                <?php else : ?>
                                    <span class="current-price">৳<?php echo number_format($price); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="product-actions">
                                <button class="view-details-btn" onclick="window.location.href='<?php the_permalink(); ?>'">
                                    <i class="fas fa-eye"></i> বিস্তারিত দেখুন
                                </button>
                                <button class="add-to-cart" onclick="addToCart('<?php echo esc_js(get_the_title()); ?>', <?php echo esc_js($sale_price ? $sale_price : $price); ?>, '<?php echo esc_js(get_the_post_thumbnail_url()); ?>')">
                                    <i class="fas fa-cart-plus"></i> কার্টে যোগ করুন
                                </button>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2><?php echo get_theme_mod('about_title', 'কেন প্রিমিয়াম বাজার বিডি বেছে নেবেন?'); ?></h2>
                <?php echo wpautop(get_theme_mod('about_content', 'আপনি কেনো প্রিমিয়াম বাজার বিডি বেছে নেবেন?')); ?>

                <div class="about-features">
                    <?php
                    $features = array(
                        array(
                            'icon' => 'certificate',
                            'title' => 'প্রত্যয়িত মান',
                            'description' => 'সকল ওষুধ সরকারি অনুমোদিত এবং নিরাপত্তার জন্য পরীক্ষিত'
                        ),
                        array(
                            'icon' => 'truck',
                            'title' => 'দ্রুত ডেলিভারি',
                            'description' => 'সারা বাংলাদেশে দ্রুত ও নিরাপদ ডেলিভারি সেবা'
                        ),
                        array(
                            'icon' => 'phone-alt',
                            'title' => '২৪/৭ সহায়তা',
                            'description' => 'আপনার সকল প্রশ্নের জন্য ২৪ ঘন্টা গ্রাহক সেবা'
                        )
                    );

                    foreach ($features as $feature) :
                    ?>
                    <div class="about-feature">
                        <i class="fas fa-<?php echo esc_attr($feature['icon']); ?>"></i>
                        <h3><?php echo esc_html($feature['title']); ?></h3>
                        <p><?php echo esc_html($feature['description']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="about-image">
                <?php
                $about_image = get_theme_mod('about_image', get_template_directory_uri() . '/assets/ChatGPT Image May 30, 2025, 12_08_37 PM.png');
                ?>
                <img src="<?php echo esc_url($about_image); ?>" alt="প্রিমিয়াম বাজার বিডি সম্পর্কে">
            </div>
        </div>
    </div>
</section>

<!-- Visual Reviews Section -->
<?php get_template_part('template-parts/content', 'reviews'); ?>

<!-- Contact Section -->
<?php get_template_part('template-parts/content', 'contact'); ?>

<!-- Shopping Cart Modal -->
<?php get_template_part('template-parts/modal', 'cart'); ?>

<!-- Payment Options Modal -->
<?php get_template_part('template-parts/modal', 'payment'); ?>

<!-- Address Modal -->
<?php get_template_part('template-parts/modal', 'address'); ?>

<?php get_footer(); ?> 