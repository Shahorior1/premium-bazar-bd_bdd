<?php
/**
 * The main template file
 *
 * @package Premium_Bazar_BD
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;
                        ?>
                    </header>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) :
                            the_content();
                        else :
                            the_excerpt();
                            ?>
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php _e('আরও পড়ুন', 'premium-bazar-bd'); ?> →
                            </a>
                            <?php
                        endif;
                        ?>
                    </div>
                </article>
                <?php
            endwhile;

            // Previous/next page navigation
            the_posts_pagination(array(
                'prev_text' => __('পূর্ববর্তী', 'premium-bazar-bd'),
                'next_text' => __('পরবর্তী', 'premium-bazar-bd'),
            ));

        else :
            ?>
            <div class="no-results">
                <h1><?php _e('কোন তথ্য পাওয়া যায়নি', 'premium-bazar-bd'); ?></h1>
                <p><?php _e('দুঃখিত, আপনার অনুসন্ধানকৃত তথ্য পাওয়া যায়নি। অনুগ্রহ করে অন্য কিছু অনুসন্ধান করুন।', 'premium-bazar-bd'); ?></p>
                <?php get_search_form(); ?>
            </div>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer(); 