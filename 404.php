<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Premium_Bazar_BD
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('পাতাটি খুঁজে পাওয়া যায়নি', 'premium-bazar-bd'); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e('দুঃখিত, আপনি যে পাতাটি খুঁজছেন তা পাওয়া যায়নি। নিচের লিংকগুলো ব্যবহার করে দেখুন:', 'premium-bazar-bd'); ?></p>
            
            <ul>
                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('হোম পেজে ফিরে যান', 'premium-bazar-bd'); ?></a></li>
                <li><?php esc_html_e('সার্চ করে দেখুন:', 'premium-bazar-bd'); ?></li>
            </ul>

            <?php get_search_form(); ?>
        </div>
    </div>
</main>

<?php
get_footer(); 