<?php
/**
 * The template for displaying search forms
 *
 * @package Premium_Bazar_BD
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php _x('অনুসন্ধান:', 'label', 'premium-bazar-bd'); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('অনুসন্ধান করুন &hellip;', 'placeholder', 'premium-bazar-bd'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit">
        <i class="fas fa-search"></i>
        <span class="screen-reader-text"><?php echo _x('অনুসন্ধান', 'submit button', 'premium-bazar-bd'); ?></span>
    </button>
</form> 