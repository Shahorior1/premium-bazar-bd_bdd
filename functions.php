<?php
/**
 * Premium Bazar BD Theme functions and definitions
 */

if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}

/**
 * Set up theme defaults and registers support for various WordPress features.
 */
function premium_bazar_bd_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');

    // Register nav menus
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'premium-bazar-bd'),
            'footer' => esc_html__('Footer Menu', 'premium-bazar-bd'),
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Bengali language
    load_theme_textdomain('premium-bazar-bd', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'premium_bazar_bd_setup');

/**
 * Enqueue scripts and styles.
 */
function premium_bazar_bd_scripts() {
    // Styles
    wp_enqueue_style('premium-bazar-bd-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

    // Scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('premium-bazar-bd-cart', get_template_directory_uri() . '/assets/js/cart.js', array('jquery'), _S_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('premium-bazar-bd-cart', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('premium_bazar_bd_nonce')
    ));

    // Pass theme options to JavaScript
    $options = get_option('premium_bazar_bd_options');
    wp_localize_script('premium-bazar-bd-cart', 'premiumBazarBD', array(
        'bkashNumber' => isset($options['bkash_number']) ? $options['bkash_number'] : '01340-860039'
    ));
}
add_action('wp_enqueue_scripts', 'premium_bazar_bd_scripts');

/**
 * Include additional functions
 */
require get_template_directory() . '/inc/cart-functions.php';

/**
 * Register widget area.
 */
function premium_bazar_bd_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'premium-bazar-bd'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'premium-bazar-bd'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'premium_bazar_bd_widgets_init');

/**
 * Custom post type for Products
 */
function premium_bazar_bd_register_post_types() {
    register_post_type('product',
        array(
            'labels' => array(
                'name' => __('Products'),
                'singular_name' => __('Product')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-cart',
            'rewrite' => array('slug' => 'products')
        )
    );
}
add_action('init', 'premium_bazar_bd_register_post_types');

/**
 * Custom taxonomies for Products
 */
function premium_bazar_bd_register_taxonomies() {
    register_taxonomy('product_category', 'product',
        array(
            'labels' => array(
                'name' => __('Product Categories'),
                'singular_name' => __('Product Category')
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'rewrite' => array('slug' => 'product-category')
        )
    );
}
add_action('init', 'premium_bazar_bd_register_taxonomies');

/**
 * Add custom meta boxes for products
 */
function premium_bazar_bd_add_meta_boxes() {
    add_meta_box(
        'product_price',
        __('Product Price', 'premium-bazar-bd'),
        'premium_bazar_bd_price_meta_box',
        'product',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'premium_bazar_bd_add_meta_boxes');

function premium_bazar_bd_price_meta_box($post) {
    $price = get_post_meta($post->ID, '_product_price', true);
    $sale_price = get_post_meta($post->ID, '_product_sale_price', true);
    ?>
    <p>
        <label for="product_price"><?php _e('Regular Price:', 'premium-bazar-bd'); ?></label>
        <input type="number" id="product_price" name="product_price" value="<?php echo esc_attr($price); ?>">
    </p>
    <p>
        <label for="product_sale_price"><?php _e('Sale Price:', 'premium-bazar-bd'); ?></label>
        <input type="number" id="product_sale_price" name="product_sale_price" value="<?php echo esc_attr($sale_price); ?>">
    </p>
    <?php
}

/**
 * Save product meta
 */
function premium_bazar_bd_save_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }
    
    $fields = [
        'product_price',
        'product_sale_price',
    ];
    
    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'premium_bazar_bd_save_meta');

/**
 * Add theme options page
 */
function premium_bazar_bd_add_theme_options() {
    add_menu_page(
        __('Theme Options', 'premium-bazar-bd'),
        __('Theme Options', 'premium-bazar-bd'),
        'manage_options',
        'premium-bazar-bd-options',
        'premium_bazar_bd_theme_options_page',
        'dashicons-admin-generic'
    );
}
add_action('admin_menu', 'premium_bazar_bd_add_theme_options');

/**
 * Theme options page content
 */
function premium_bazar_bd_theme_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['premium_bazar_bd_options_nonce']) && wp_verify_nonce($_POST['premium_bazar_bd_options_nonce'], 'premium_bazar_bd_options')) {
        $options = array(
            'bkash_number' => sanitize_text_field($_POST['bkash_number']),
            'phone_number' => sanitize_text_field($_POST['phone_number']),
            'email' => sanitize_email($_POST['email']),
            'address' => sanitize_text_field($_POST['address'])
        );
        update_option('premium_bazar_bd_options', $options);
        echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'premium-bazar-bd') . '</p></div>';
    }

    $options = get_option('premium_bazar_bd_options');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post">
            <?php wp_nonce_field('premium_bazar_bd_options', 'premium_bazar_bd_options_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('bKash Number', 'premium-bazar-bd'); ?></th>
                    <td>
                        <input type="text" name="bkash_number" value="<?php echo esc_attr(isset($options['bkash_number']) ? $options['bkash_number'] : ''); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Phone Number', 'premium-bazar-bd'); ?></th>
                    <td>
                        <input type="text" name="phone_number" value="<?php echo esc_attr(isset($options['phone_number']) ? $options['phone_number'] : ''); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Email', 'premium-bazar-bd'); ?></th>
                    <td>
                        <input type="email" name="email" value="<?php echo esc_attr(isset($options['email']) ? $options['email'] : ''); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Address', 'premium-bazar-bd'); ?></th>
                    <td>
                        <textarea name="address" class="large-text" rows="3"><?php echo esc_textarea(isset($options['address']) ? $options['address'] : ''); ?></textarea>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(__('Save Settings', 'premium-bazar-bd')); ?>
        </form>
    </div>
    <?php
}

/**
 * Add custom image sizes
 */
add_image_size('product-thumbnail', 300, 300, true);
add_image_size('product-gallery', 800, 600, true);
add_image_size('customer-photo', 150, 150, true); 