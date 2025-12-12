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






