<?php
/**
 * The template for displaying search results pages
 *
 * @package Premium_Bazar_BD
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('অনুসন্ধান ফলাফল: %s', 'premium-bazar-bd'),
                        '<span>' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            </header>

            <?php
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
                    </header>

                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php
            endwhile;

            the_posts_pagination(array(
                'prev_text' => __('পূর্ববর্তী', 'premium-bazar-bd'),
                'next_text' => __('পরবর্তী', 'premium-bazar-bd'),
            ));

        else :
            ?>
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('কোন ফলাফল পাওয়া যায়নি', 'premium-bazar-bd'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php esc_html_e('দুঃখিত, আপনার অনুসন্ধানকৃত তথ্য পাওয়া যায়নি। অনুগ্রহ করে অন্য কিছু অনুসন্ধান করুন।', 'premium-bazar-bd'); ?></p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer(); 