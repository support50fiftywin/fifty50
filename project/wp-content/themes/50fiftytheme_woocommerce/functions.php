<?php
// Woo support
add_action('after_setup_theme', function(){ add_theme_support('woocommerce');});




function fifty_footer_customizer($wp_customize) {

    // Footer Section
    $wp_customize->add_section('fifty_footer_section', [
        'title' => 'Footer Settings',
        'priority' => 40,
    ]);

    // Copyright
    $wp_customize->add_setting('fifty_footer_copyright', [
        'default' => '© 2024 50fifty.win. All rights reserved.',
    ]);

    $wp_customize->add_control('fifty_footer_copyright_control', [
        'label' => 'Footer Copyright Text',
        'section' => 'fifty_footer_section',
        'settings' => 'fifty_footer_copyright',
        'type' => 'text',
    ]);

    // Social Media
    $socials = ['facebook', 'twitter', 'instagram', 'youtube'];
    foreach ($socials as $social) {
        $wp_customize->add_setting("fifty_social_{$social}", [
            'default' => '#',
        ]);

        $wp_customize->add_control("fifty_social_{$social}_control", [
            'label' => ucfirst($social) . " URL",
            'section' => 'fifty_footer_section',
            'type' => 'url',
        ]);
    }
}
add_action('customize_register', 'fifty_footer_customizer');
function fifty_register_footer_menus() {
    register_nav_menu('footer_quick_links', 'Footer - Quick Links');
    register_nav_menu('footer_partners', 'Footer - Partners');
    register_nav_menu('footer_support', 'Footer - Support');
}
add_action('init', 'fifty_register_footer_menus');

function fifty_register_menus() {
    register_nav_menu('header-menu', 'Header Menu');
}
add_action('init', 'fifty_register_menus');
function fifty_customizer_settings($wp_customize) {

    // Top Bar Text
    $wp_customize->add_section('fifty_topbar_section', [
        'title' => 'Top Bar Settings',
        'priority' => 30,
    ]);

    $wp_customize->add_setting('fifty_topbar_text', [
        'default' => '10 Entries for every $1 spent | Sign up now for 100 FREE ENTRIES',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('fifty_topbar_text_control', [
        'label' => 'Top Bar Text',
        'section' => 'fifty_topbar_section',
        'settings' => 'fifty_topbar_text',
        'type' => 'text',
    ]);


    // Button Links Section
    $wp_customize->add_section('fifty_buttons_section', [
        'title' => 'Header Buttons Links',
        'priority' => 31,
    ]);

    $wp_customize->add_setting('fifty_podcasters_link', [
        'default' => '#',
    ]);

    $wp_customize->add_control('fifty_podcasters_link_control', [
        'label' => 'For Podcasters URL',
        'section' => 'fifty_buttons_section',
        'settings' => 'fifty_podcasters_link',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('fifty_merchants_link', [
        'default' => '#',
    ]);

    $wp_customize->add_control('fifty_merchants_link_control', [
        'label' => 'For Merchants URL',
        'section' => 'fifty_buttons_section',
        'settings' => 'fifty_merchants_link',
        'type' => 'url',
    ]);

}
add_action('customize_register', 'fifty_customizer_settings');


// Register Testimonials Custom Post Type
function sweepstake_register_testimonials_cpt() {

    $labels = array(
        'name'               => 'Testimonials',
        'singular_name'      => 'Testimonial',
        'menu_name'          => 'Testimonials',
        'name_admin_bar'     => 'Testimonial',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Testimonial',
        'new_item'           => 'New Testimonial',
        'edit_item'          => 'Edit Testimonial',
        'view_item'          => 'View Testimonial',
        'all_items'          => 'All Testimonials',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-star-filled',
        'supports'           => array('title', 'editor', 'thumbnail'),
        'has_archive'        => false,
        'rewrite'            => array('slug' => 'testimonials'),
        'show_in_rest'       => true,
    );

    register_post_type('testimonial', $args);
}
add_action('init', 'sweepstake_register_testimonials_cpt');

// Add Meta Boxes
function testimonial_add_custom_meta_box() {
    add_meta_box(
        'testimonial_meta',
        'Testimonial Details',
        'testimonial_meta_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'testimonial_add_custom_meta_box');

function testimonial_meta_callback($post) {

    $rating = get_post_meta($post->ID, 'rating', true);
    $customer_name = get_post_meta($post->ID, 'customer_name', true);
    $label = get_post_meta($post->ID, 'label', true);

    ?>
    <p>
        <label><strong>Star Rating (1–5):</strong></label><br>
        <input type="number" name="rating" min="1" max="5" value="<?php echo esc_attr($rating); ?>">
    </p>

    <p>
        <label><strong>Customer Name:</strong></label><br>
        <input type="text" name="customer_name" value="<?php echo esc_attr($customer_name); ?>" style="width:100%;">
    </p>

    <p>
        <label><strong>Label (Winner – Month Year / Podcaster Partner):</strong></label><br>
        <input type="text" name="label" value="<?php echo esc_attr($label); ?>" style="width:100%;">
    </p>
    <?php
}

// Save Meta Fields
function testimonial_save_meta_fields($post_id) {

    if (isset($_POST['rating'])) {
        update_post_meta($post_id, 'rating', sanitize_text_field($_POST['rating']));
    }

    if (isset($_POST['customer_name'])) {
        update_post_meta($post_id, 'customer_name', sanitize_text_field($_POST['customer_name']));
    }

    if (isset($_POST['label'])) {
        update_post_meta($post_id, 'label', sanitize_text_field($_POST['label']));
    }

}
add_action('save_post', 'testimonial_save_meta_fields');



