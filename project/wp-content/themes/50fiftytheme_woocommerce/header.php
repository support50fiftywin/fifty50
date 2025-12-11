<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>

    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/dist/custom.css">

    <?php wp_head(); ?>
</head>

<body <?php body_class("bg-white text-brand-black antialiased"); ?>>

<!-- Top Bar (Dynamic) -->
<div id="top-bar" 
  class="bg-brand-accent text-white py-2 text-center text-xs md:text-sm font-bold tracking-wider uppercase flex justify-center">
  <?php echo esc_html(get_theme_mod('fifty_topbar_text')); ?>
</div>

<header id="header" class="sticky top-0 z-50 bg-white/95 border-b border-gray-100">
  <div class="container mx-auto px-4 md:px-6 h-20 flex items-center justify-between gap-5">

    <!-- Dynamic Logo -->
    <a href="<?php echo home_url(); ?>" class="flex items-center gap-2 group">
      <?php if (has_custom_logo()) : ?>
          <?php the_custom_logo(); ?>
      <?php else : ?>
          <div
            class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center font-bold text-lg">
            50
          </div>
      <?php endif; ?>

      <span class="text-2xl font-bold font-display tracking-tighter">
        <?php bloginfo('name'); ?>
      </span>
    </a>


    <div class="flex items-center gap-3 xl:flex-1">

      <!-- Dynamic Menu -->
      <nav class="flex items-center gap-6 uppercase font-medium text-sm xl:flex">
        <?php
          wp_nav_menu([
              'theme_location' => 'header-menu',
              'container' => false,
              'items_wrap' => '%3$s',
              'fallback_cb' => false
          ]);
        ?>
      </nav>

      <!-- Buttons (Dynamic) -->
      <div class="hidden xl:flex gap-2 ml-4">
        <a href="<?php echo get_theme_mod('fifty_podcasters_link'); ?>" 
           class="px-4 py-2 text-xs font-medium border border-black rounded-full hover:bg-black hover:text-white">
           For Podcasters
        </a>

        <a href="<?php echo get_theme_mod('fifty_merchants_link'); ?>" 
           class="px-4 py-2 text-xs font-medium border border-black rounded-full hover:bg-black hover:text-white">
           For Merchants
        </a>
      </div>

      <!-- Login -->
      <a href="<?php echo wp_login_url(); ?>" class="text-sm uppercase hover:text-brand-accent">Login</a>

      <!-- WooCommerce Cart -->
      <?php if (class_exists('WooCommerce')) : ?>
      <a href="<?php echo wc_get_cart_url(); ?>" class="relative p-2 hover:text-brand-accent">
          <span class="absolute top-0 right-0 bg-black text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
              <?php echo WC()->cart->get_cart_contents_count(); ?>
          </span>
          🛒
      </a>
      <?php endif; ?>

      <!-- Mobile Menu Toggle -->
      <button class="xl:hidden p-2 js--toggle">☰</button>

    </div>
  </div>
</header>

