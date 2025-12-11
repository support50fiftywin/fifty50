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


function sweepstakes_register_cpt() {

    $labels = array(
        'name'          => 'Sweepstakes',
        'singular_name' => 'Sweepstake',
        'menu_name'     => 'Sweepstakes',
        'add_new_item'  => 'Add New Sweepstake',
        'edit_item'     => 'Edit Sweepstake',
        'view_item'     => 'View Sweepstake'
    );

    $args = array(
        'label'           => 'Sweepstakes',
        'labels'          => $labels,
        'public'          => true,
        'menu_icon'       => 'dashicons-awards',
        'supports'        => array('title', 'editor', 'thumbnail'),
        
        // VERY IMPORTANT → fixes your 404 issue
        'rewrite'         => array(
            'slug'       => 'sweepstakes',
            'with_front' => false
        ),

        'has_archive'     => true,
        'show_ui'         => true,
        'show_in_menu'    => true,
    );

    register_post_type('sweepstake', $args);
}
add_action('init', 'sweepstakes_register_cpt');


/* -------------------------------------------
   ADD METABOX
-------------------------------------------- */
function sweepstake_add_metabox() {
    add_meta_box(
        'sweepstake_meta_box',
        'Sweepstake Details',
        'sweepstake_meta_box_html',
        'sweepstake'
    );
}
add_action('add_meta_boxes', 'sweepstake_add_metabox');


function sweepstake_meta_box_html($post) {

    $price      = get_post_meta($post->ID, 'price', true);
    $start_date = get_post_meta($post->ID, 'start_date', true);
    $end_date   = get_post_meta($post->ID, 'end_date', true);
    $status     = get_post_meta($post->ID, 'status', true);
    $merchant   = get_post_meta($post->ID, 'merchant_id', true);

    wp_nonce_field('sweepstake_save', 'sweepstake_nonce');
    ?>

    <p><label><strong>Price ($):</strong></label><br>
        <input type="number" step="0.01" name="price" value="<?php echo esc_attr($price); ?>">
    </p>

    <p><label><strong>Start Date:</strong></label><br>
        <input type="datetime-local" name="start_date" value="<?php echo esc_attr($start_date); ?>">
    </p>

    <p><label><strong>End Date:</strong></label><br>
        <input type="datetime-local" name="end_date" value="<?php echo esc_attr($end_date); ?>">
    </p>

    <p><label><strong>Status:</strong></label><br>
        <select name="status">
            <option value="scheduled" <?php selected($status, 'scheduled'); ?>>Scheduled</option>
            <option value="active"     <?php selected($status, 'active'); ?>>Active</option>
            <option value="closed"     <?php selected($status, 'closed'); ?>>Closed</option>
        </select>
    </p>

    <p><label><strong>Merchant ID:</strong></label><br>
        <input type="text" value="<?php echo esc_attr($merchant); ?>" disabled>
        <small>Auto-assigned if current user is merchant</small>
    </p>

<?php }
/* -------------------------------------------
   SAVE META FIELDS
-------------------------------------------- */
function sweepstake_save_data($post_id) {

    if (!isset($_POST['sweepstake_nonce']) ||
        !wp_verify_nonce($_POST['sweepstake_nonce'], 'sweepstake_save')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, 'price', sanitize_text_field($_POST['price']));
    update_post_meta($post_id, 'start_date', sanitize_text_field($_POST['start_date']));
    update_post_meta($post_id, 'end_date', sanitize_text_field($_POST['end_date']));
    update_post_meta($post_id, 'status', sanitize_text_field($_POST['status']));

    // Auto assign merchant ID
    if (current_user_can('merchant')) {
        update_post_meta($post_id, 'merchant_id', get_current_user_id());
    }
}
add_action('save_post', 'sweepstake_save_data');
/* -------------------------------------------
   ADMIN COLUMNS
-------------------------------------------- */
add_filter('manage_sweepstake_posts_columns', function($cols) {
    $cols['price'] = 'Price';
    $cols['status'] = 'Status';
    $cols['start_date'] = 'Start Date';
    $cols['end_date'] = 'End Date';
    return $cols;
});


add_action('manage_sweepstake_posts_custom_column', function($col, $id) {
    switch ($col) {
        case 'price': echo '$' . get_post_meta($id, 'price', true); break;
        case 'status': echo ucfirst(get_post_meta($id, 'status', true)); break;
        case 'start_date': echo get_post_meta($id, 'start_date', true); break;
        case 'end_date': echo get_post_meta($id, 'end_date', true); break;
    }
}, 10, 2);
