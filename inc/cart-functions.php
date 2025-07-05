<?php
/**
 * Cart and Order related functions
 */

// Add AJAX actions
add_action('wp_ajax_submit_address', 'premium_bazar_bd_submit_address');
add_action('wp_ajax_nopriv_submit_address', 'premium_bazar_bd_submit_address');

add_action('wp_ajax_contact_form_submit', 'premium_bazar_bd_contact_form_submit');
add_action('wp_ajax_nopriv_contact_form_submit', 'premium_bazar_bd_contact_form_submit');

/**
 * Handle address submission
 */
function premium_bazar_bd_submit_address() {
    check_ajax_referer('submit_address', 'address_nonce');

    $customer_name = sanitize_text_field($_POST['customerName']);
    $customer_phone = sanitize_text_field($_POST['customerPhone']);
    $customer_email = sanitize_email($_POST['customerEmail']);
    $district = sanitize_text_field($_POST['district']);
    $upazila = sanitize_text_field($_POST['upazila']);
    $full_address = sanitize_textarea_field($_POST['fullAddress']);

    // Validate required fields
    if (empty($customer_name) || empty($customer_phone) || empty($district) || empty($upazila) || empty($full_address)) {
        wp_send_json_error('সব তথ্য পূরণ করুন');
        return;
    }

    // Create order post
    $order_data = array(
        'post_title'    => wp_strip_all_tags($customer_name),
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type'     => 'shop_order'
    );

    $order_id = wp_insert_post($order_data);

    if ($order_id) {
        // Save order meta
        update_post_meta($order_id, '_customer_name', $customer_name);
        update_post_meta($order_id, '_customer_phone', $customer_phone);
        update_post_meta($order_id, '_customer_email', $customer_email);
        update_post_meta($order_id, '_billing_district', $district);
        update_post_meta($order_id, '_billing_upazila', $upazila);
        update_post_meta($order_id, '_billing_address', $full_address);
        update_post_meta($order_id, '_order_status', 'pending');

        // Calculate delivery charge
        $delivery_charge = ($district === 'dhaka') ? 80 : 120;
        update_post_meta($order_id, '_delivery_charge', $delivery_charge);

        wp_send_json_success(array(
            'order_id' => $order_id,
            'delivery_charge' => $delivery_charge
        ));
    } else {
        wp_send_json_error('অর্ডার তৈরি করা যায়নি');
    }
}

/**
 * Handle contact form submission
 */
function premium_bazar_bd_contact_form_submit() {
    check_ajax_referer('contact_form_submit', 'contact_nonce');

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        wp_send_json_error('সব তথ্য পূরণ করুন');
        return;
    }

    // Create contact form entry
    $contact_data = array(
        'post_title'    => wp_strip_all_tags($name),
        'post_content'  => $message,
        'post_status'   => 'publish',
        'post_type'     => 'contact_form'
    );

    $contact_id = wp_insert_post($contact_data);

    if ($contact_id) {
        // Save contact meta
        update_post_meta($contact_id, '_contact_email', $email);
        update_post_meta($contact_id, '_contact_phone', $phone);

        // Send email notification
        $to = get_option('admin_email');
        $subject = 'নতুন যোগাযোগ ফরম জমা - ' . $name;
        $body = "নাম: $name\n";
        $body .= "ইমেইল: $email\n";
        $body .= "ফোন: $phone\n\n";
        $body .= "বার্তা:\n$message";
        
        wp_mail($to, $subject, $body);

        wp_send_json_success();
    } else {
        wp_send_json_error('যোগাযোগ ফরম জমা দেওয়া যায়নি');
    }
}

/**
 * Register order and contact form post types
 */
function premium_bazar_bd_register_order_types() {
    // Register Shop Order post type
    register_post_type('shop_order', array(
        'labels' => array(
            'name' => __('অর্ডার', 'premium-bazar-bd'),
            'singular_name' => __('অর্ডার', 'premium-bazar-bd'),
        ),
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => false,
        'supports' => array('title')
    ));

    // Register Contact Form post type
    register_post_type('contact_form', array(
        'labels' => array(
            'name' => __('যোগাযোগ ফরম', 'premium-bazar-bd'),
            'singular_name' => __('যোগাযোগ ফরম', 'premium-bazar-bd'),
        ),
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => false,
        'supports' => array('title', 'editor')
    ));
}
add_action('init', 'premium_bazar_bd_register_order_types');

/**
 * Add custom columns to orders admin page
 */
function premium_bazar_bd_order_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = __('অর্ডার আইডি', 'premium-bazar-bd');
            $new_columns['customer_info'] = __('গ্রাহকের তথ্য', 'premium-bazar-bd');
            $new_columns['order_details'] = __('অর্ডারের বিবরণ', 'premium-bazar-bd');
            $new_columns['order_status'] = __('অর্ডারের অবস্থা', 'premium-bazar-bd');
        } else {
            $new_columns[$key] = $value;
        }
    }
    return $new_columns;
}
add_filter('manage_shop_order_posts_columns', 'premium_bazar_bd_order_columns');

/**
 * Display custom column content
 */
function premium_bazar_bd_order_column_content($column, $post_id) {
    switch ($column) {
        case 'customer_info':
            $name = get_post_meta($post_id, '_customer_name', true);
            $phone = get_post_meta($post_id, '_customer_phone', true);
            $email = get_post_meta($post_id, '_customer_email', true);
            
            echo "<strong>$name</strong><br>";
            echo "$phone<br>";
            if ($email) {
                echo "$email<br>";
            }
            break;

        case 'order_details':
            $district = get_post_meta($post_id, '_billing_district', true);
            $upazila = get_post_meta($post_id, '_billing_upazila', true);
            $address = get_post_meta($post_id, '_billing_address', true);
            $delivery_charge = get_post_meta($post_id, '_delivery_charge', true);

            echo "<strong>ঠিকানা:</strong><br>";
            echo "$address<br>";
            echo "$upazila, $district<br>";
            echo "<strong>ডেলিভারি চার্জ:</strong> ৳$delivery_charge";
            break;

        case 'order_status':
            $status = get_post_meta($post_id, '_order_status', true);
            $statuses = array(
                'pending' => 'অপেক্ষমান',
                'processing' => 'প্রক্রিয়াধীন',
                'completed' => 'সম্পন্ন',
                'cancelled' => 'বাতিল'
            );
            echo isset($statuses[$status]) ? $statuses[$status] : $status;
            break;
    }
}
add_action('manage_shop_order_posts_custom_column', 'premium_bazar_bd_order_column_content', 10, 2); 