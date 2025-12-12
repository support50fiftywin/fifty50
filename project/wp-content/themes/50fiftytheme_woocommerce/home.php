<?php
/* 
 * Template Name: Home Page
 * Description: Custom Homepage Template with Countdown + Stats
 */
get_header();
?>

<!-- TAILWIND -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- HERO SECTION -->
  <section id="hero" class="relative w-full h-[600px] md:h-[700px] bg-black overflow-hidden flex items-center">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
      <img
        src="https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&amp;w=2070&amp;auto=format&amp;fit=crop"
        class="w-full h-full object-cover opacity-20" alt="Supercar Prize">
    </div>

    <div class="container mx-auto px-4 relative z-10 flex flex-col items-center text-center text-white max-w-4xl">
      <span
        class="inline-block py-1 px-3 border border-white/30 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold uppercase tracking-widest mb-6">
        Current Giveaway Ends Soon
      </span>
      <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-6 leading-tight">
        WIN THIS <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">DREAM RIDE</span>
      </h1>
      <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl font-light">
        Every <span class="font-bold text-white">$1 spent</span> on custom merch gets you <span
          class="font-bold text-brand-accent">10 ENTRIES</span> to win.
        <br class="hidden md:block">Create an account today and get <span
          class="font-bold underline decoration-brand-accent">100 FREE ENTRIES</span> instantly.
      </p>

      <div class="flex flex-col md:flex-row gap-4 w-full justify-center">
        <button
          class="bg-brand-accent hover:bg-red-700 text-white px-8 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all transform hover:scale-105 shadow-lg shadow-red-900/50 rounded-full">
          Shop &amp; Enter Now
        </button>
        <button
          class="bg-white hover:bg-gray-100 text-black px-8 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all rounded-full">
          View Prizes
        </button>
      </div>
      <!-- Trust Badges Small -->
      <div class="mt-12 flex gap-6 text-gray-400 text-sm font-medium items-center">
        <div class="flex items-center gap-2">
          <i>
            <svg class="w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
              <path fill="currentColor"
                d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z">
              </path>
            </svg>
          </i> Official Giveaway
        </div>
        <div class="flex items-center gap-2">
          <i>
            <svg class="w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
              <path fill="currentColor"
                d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z">
              </path>
            </svg>
          </i>
          Secure Checkout
        </div>
      </div>
    </div>
  </section>
  
<?php
// Convert admin datetime-local format to valid MySQL format
function normalize_datetime($dt) {
    if (strpos($dt, 'T') !== false) {
        return str_replace('T', ' ', $dt) . ':00';
    }
    return $dt;
}

// Today's date in MySQL format
$today = current_time('mysql'); // 2025-01-20 14:05:00

// SAFE QUERY FOR ACTIVE SWEEPSTAKE
$args = array(
    'post_type'      => 'sweepstake',
    'posts_per_page' => 1,
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_key'       => '_wpsw_start_date',
    'meta_type'      => 'DATETIME',

    'meta_query'     => array(
        'relation' => 'AND',

        // STARTED (today or past)
        array(
            'key'     => '_wpsw_start_date',
            'value'   => $today,
            'compare' => '<=',
            'type'    => 'DATETIME'
        ),

        // NOT FINISHED (today or future)
        array(
            'key'     => '_wpsw_end_date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATETIME'
        ),
    )
);

$sw_query = new WP_Query($args);

// DEFAULT VALUES
$sw_title = "Upcoming Giveaway";
$sw_desc  = "Stay tuned!";
$end_date = "";
$has_sweepstake = false;

// DEBUG OUTPUT
// echo "<pre style='color:red'>";
// echo "FOUND POSTS: " . $sw_query->found_posts . "\n";
// echo "</pre>";

if ($sw_query->have_posts()) {
    $sw_query->the_post();

    $has_sweepstake = true;
    $sw_title = get_the_title();
    $sw_desc  = get_the_excerpt();

    // normalize end date for JS countdown
    $raw_end = get_post_meta(get_the_ID(), '_wpsw_end_date', true);
    $end_date = normalize_datetime($raw_end);
}

wp_reset_postdata();
?>

<?php if ($has_sweepstake && !empty($end_date)) : ?>
<section class="w-full bg-brand-accent py-8">
    <div class="container mx-auto px-4">
      <div class="flex flex-col xl:flex-row items-center justify-between gap-4">

        <div class="col-left">
          <h2 class="text-white text-2xl font-bold uppercase tracking-wide">
            <?php echo esc_html($sw_title); ?>
          </h2>

          <p class="text-white text-sm mt-1 opacity-80">
            <?php echo esc_html($sw_desc); ?>
          </p>

          <p class="text-white text-sm mt-1 opacity-80">
            Ends In
          </p>
        </div>

        <!-- Timer -->
        <div id="countdown" class="flex gap-4 text-center" 
             data-end="<?php echo esc_attr($end_date); ?>">

          <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
            <span id="days" class="block text-2xl font-bold text-gray-800">--</span>
            <span class="block text-xs font-medium text-gray-600 uppercase">Days</span>
          </div>

          <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
            <span id="hours" class="block text-2xl font-bold text-gray-800">--</span>
            <span class="block text-xs font-medium text-gray-600 uppercase">Hrs</span>
          </div>

          <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
            <span id="minutes" class="block text-2xl font-bold text-gray-800">--</span>
            <span class="block text-xs font-medium text-gray-600 uppercase">Min</span>
          </div>

          <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
            <span id="seconds" class="block text-2xl font-bold text-gray-800">--</span>
            <span class="block text-xs font-medium text-gray-600 uppercase">Sec</span>
          </div>

        </div>
      </div>
    </div>
</section>
<?php endif; ?>


  <section id="benefits" class="py-16 bg-brand-gray border-b border-gray-200">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Benefit 1 -->
        <div
          class="bg-white p-8 rounded-2xl shadow-sm text-center group hover:-translate-y-1 transition-transform duration-300">
          <div
            class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-brand-accent transition-colors">
            <i data-fa-i2svg="">
              <svg class="svg-inline--fa fa-shirt w-8" aria-hidden="true" focusable="false" data-prefix="fas"
                data-icon="shirt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg="">
                <path fill="currentColor"
                  d="M211.8 0c7.8 0 14.3 5.7 16.7 13.2C240.8 51.9 277.1 80 320 80s79.2-28.1 91.5-66.8C413.9 5.7 420.4 0 428.2 0h12.6c22.5 0 44.2 7.9 61.5 22.3L628.5 127.4c6.6 5.5 10.7 13.5 11.4 22.1s-2.1 17.1-7.8 23.6l-56 64c-11.4 13.1-31.2 14.6-44.6 3.5L480 197.7V448c0 35.3-28.7 64-64 64H224c-35.3 0-64-28.7-64-64V197.7l-51.5 42.9c-13.3 11.1-33.1 9.6-44.6-3.5l-56-64c-5.7-6.5-8.5-15-7.8-23.6s4.8-16.6 11.4-22.1L137.7 22.3C155 7.9 176.7 0 199.2 0h12.6z">
                </path>
              </svg></i>
          </div>
          <h3 class="text-xl font-bold mb-3 uppercase">1. Shop Custom Merch</h3>
          <p class="text-gray-600 text-sm leading-relaxed">Browse our exclusive collection or customize your own gear.
            High-quality prints powered by Printful.</p>
        </div>

        <!-- Benefit 2 -->
        <div
          class="bg-white p-8 rounded-2xl shadow-sm text-center group hover:-translate-y-1 transition-transform duration-300">
          <div
            class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-brand-accent transition-colors">
            <i data-fa-i2svg=""><svg class="svg-inline--fa fa-ticket w-8" aria-hidden="true" focusable="false"
                data-prefix="fas" data-icon="ticket" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                data-fa-i2svg="">
                <path fill="currentColor"
                  d="M64 64C28.7 64 0 92.7 0 128v64c0 8.8 7.4 15.7 15.7 18.6C34.5 217.1 48 235 48 256s-13.5 38.9-32.3 45.4C7.4 304.3 0 311.2 0 320v64c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V320c0-8.8-7.4-15.7-15.7-18.6C541.5 294.9 528 277 528 256s13.5-38.9 32.3-45.4c8.3-2.9 15.7-9.8 15.7-18.6V128c0-35.3-28.7-64-64-64H64zm64 112l0 160c0 8.8 7.2 16 16 16H432c8.8 0 16-7.2 16-16V176c0-8.8-7.2-16-16-16H144c-8.8 0-16 7.2-16 16zM96 160c0-17.7 14.3-32 32-32H448c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H128c-17.7 0-32-14.3-32-32V160z">
                </path>
              </svg></i>
          </div>
          <h3 class="text-xl font-bold mb-3 uppercase">2. Earn Entries</h3>
          <p class="text-gray-600 text-sm leading-relaxed">Get <strong>10 Entries</strong> for every $1 you spend. Plus,
            earn bonus points on Temu affiliate purchases.</p>
        </div>

        <!-- Benefit 3 -->
        <div
          class="bg-white p-8 rounded-2xl shadow-sm text-center group hover:-translate-y-1 transition-transform duration-300">
          <div
            class="w-16 h-16 bg-black text-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-brand-accent transition-colors">
            <i data-fa-i2svg=""><svg class="svg-inline--fa fa-trophy w-8" aria-hidden="true" focusable="false"
                data-prefix="fas" data-icon="trophy" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                data-fa-i2svg="">
                <path fill="currentColor"
                  d="M400 0H176c-26.5 0-48.1 21.8-47.1 48.2c.2 5.3 .4 10.6 .7 15.8H24C10.7 64 0 74.7 0 88c0 92.6 33.5 157 78.5 200.7c44.3 43.1 98.3 64.8 138.1 75.8c23.4 6.5 39.4 26 39.4 45.6c0 20.9-17 37.9-37.9 37.9H192c-17.7 0-32 14.3-32 32s14.3 32 32 32H384c17.7 0 32-14.3 32-32s-14.3-32-32-32H357.9C337 448 320 431 320 410.1c0-19.6 15.9-39.2 39.4-45.6c39.9-11 93.9-32.7 138.2-75.8C542.5 245 576 180.6 576 88c0-13.3-10.7-24-24-24H446.4c.3-5.2 .5-10.4 .7-15.8C448.1 21.8 426.5 0 400 0zM48.9 112h84.4c9.1 90.1 29.2 150.3 51.9 190.6c-24.9-11-50.8-26.5-73.2-48.3c-32-31.1-58-76-63-142.3zM464.1 254.3c-22.4 21.8-48.3 37.3-73.2 48.3c22.7-40.3 42.8-100.5 51.9-190.6h84.4c-5.1 66.3-31.1 111.2-63 142.3z">
                </path>
              </svg></i>
          </div>
          <h3 class="text-xl font-bold mb-3 uppercase">3. Win Big</h3>
          <p class="text-gray-600 text-sm leading-relaxed">You are automatically entered into the draw. Watch live as we
            announce the lucky winner!</p>
        </div>

        <!-- Benefit 4 (New for Merchants) -->
        <div
          class="bg-black text-white p-8 rounded-2xl shadow-sm text-center group hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden">
          <div class="absolute inset-0 bg-brand-accent opacity-0 group-hover:opacity-10 transition-opacity"></div>
          <div
            class="w-16 h-16 border-2 border-white rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
            <i data-fa-i2svg=""><svg class="svg-inline--fa fa-store w-8" aria-hidden="true" focusable="false"
                data-prefix="fas" data-icon="store" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                data-fa-i2svg="">
                <path fill="currentColor"
                  d="M547.6 103.8L490.3 13.1C485.2 5 476.1 0 466.4 0H109.6C99.9 0 90.8 5 85.7 13.1L28.3 103.8c-29.6 46.8-3.4 111.9 51.9 119.4c4 .5 8.1 .8 12.1 .8c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.1 0 49.3-11.4 65.2-29c15.9 17.6 39.1 29 65.2 29c26.2 0 49.3-11.4 65.2-29c16 17.6 39.1 29 65.2 29c4.1 0 8.1-.3 12.1-.8c55.5-7.4 81.8-72.5 52.1-119.4zM499.7 254.9l-.1 0c-5.3 .7-10.7 1.1-16.2 1.1c-12.4 0-24.3-1.9-35.4-5.3V384H128V250.6c-11.2 3.5-23.2 5.4-35.6 5.4c-5.5 0-11-.4-16.3-1.1l-.1 0c-4.1-.6-8.1-1.3-12-2.3V384v64c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V384 252.6c-4 1-8 1.8-12.3 2.3z">
                </path>
              </svg></i>
          </div>
          <h3 class="text-xl font-bold mb-3 uppercase">Launch Your Store</h3>
          <p class="text-gray-300 text-sm leading-relaxed">Podcasters &amp; Merchants: Open a store identical to this
            one
            and sell your own custom merch.</p>
        </div>
      </div>
    </div>
  </section>
 <?php
// Query 8 featured products
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'tax_query' => array(
        array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
        ),
    ),
);

$featured_products = new WP_Query($args);
?>

<section id="featured-products" class="py-20 bg-white">
    <div class="container mx-auto px-4">

        <!-- Section Header -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div>
                <h2 class="text-2xl xl:text-4xl font-bold mb-2 uppercase">Featured Collection</h2>
                <p class="text-gray-500">Premium gear that gets you closer to the prize.</p>
            </div>

            <a href="<?php echo wc_get_page_permalink('shop'); ?>"
               class="hidden md:inline-flex items-center font-bold border-b-2 border-black pb-1 hover:text-brand-accent hover:border-brand-accent transition-colors">
               View All Products
               <i class="ml-2">
                   <svg class="w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                       <path fill="currentColor"
                        d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224H32c-18 0-32 14-32 32s14 32 32 32h306.8L233.3 393.4c-12.4 12.4-12.4 33 0 45.4s32.8 12.4 45.3 0l160-160z"/>
                   </svg>
               </i>
            </a>
        </div>

        <!-- Dynamic WooCommerce Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <?php if ($featured_products->have_posts()) : ?>
                <?php while ($featured_products->have_posts()) : $featured_products->the_post(); 
                    global $product;

                    // Custom ENTRIES meta field
                    $entries = get_post_meta(get_the_ID(), 'entries', true);
                    if (!$entries) $entries = rand(200, 1200); // fallback
                ?>
                
                <div class="group">
                    <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/5] mb-4">

                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <img src="<?php echo get_the_post_thumbnail_url(); ?>"
                                     class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                     alt="<?php the_title(); ?>">
                            <?php endif; ?>
                        </a>

                        <div class="absolute top-3 right-3 bg-brand-accent text-white text-xs font-bold px-2 py-1 rounded">
                            <?php echo $entries; ?> ENTRIES
                        </div>

                        <?php echo apply_filters('woocommerce_loop_add_to_cart_link',
                            sprintf(
                                '<a href="%s" data-quantity="1" class="absolute bottom-4 left-4 right-4 bg-white text-black font-bold py-3 rounded-btn opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all shadow-lg rounded-full text-center" %s>%s</a>',
                                esc_url($product->add_to_cart_url()),
                                wc_implode_html_attributes(array(
                                    'data-product_id'  => $product->get_id(),
                                    'data-product_sku' => $product->get_sku(),
                                    'rel'               => 'nofollow'
                                )),
                                esc_html__('Add to Cart', 'woocommerce')
                            ),
                        $product); ?>
                    </div>

                    <h3 class="font-bold text-lg mb-1 group-hover:text-brand-accent transition-colors uppercase">
                        <?php the_title(); ?>
                    </h3>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-medium"><?php echo $product->get_price_html(); ?></span>

                        <div class="text-yellow-400 text-xs flex">
                            <?php 
                            echo wc_get_rating_html($product->get_average_rating());
                            ?>
                        </div>
                    </div>
                </div>

                <?php endwhile; wp_reset_postdata(); ?>
            <?php else: ?>

                <p class="text-gray-500">No featured products found.</p>

            <?php endif; ?>

        </div>

    </div>
</section>

  <?php
// ---- SETTINGS ----
// Get active sweepstake: start_date <= now AND end_date >= now

$today = current_time('mysql');

$args = [
    'post_type'      => 'sweepstake',
    'posts_per_page' => 1,
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_key'       => '_wpsw_start_date',
    'meta_type'      => 'DATETIME',

    'meta_query'     => [
        'relation' => 'AND',
        [
            'key'     => '_wpsw_start_date',
            'value'   => $today,
            'compare' => '<=',
            'type'    => 'DATETIME'
        ],
        [
            'key'     => '_wpsw_end_date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'DATETIME'
        ]
    ]
];

$sw_query = new WP_Query($args);

// DEFAULTS
$show_section = false;
$sw_title     = "Win Big!";
$sw_desc      = "Enter now for your chance to win!";
$end_date     = "";
$bg_image     = "https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=2070&auto=format&fit=crop";

// If sweepstake found
if ($sw_query->have_posts()) {
    $sw_query->the_post();

    $show_section = true;
    $sw_title = get_the_title();
    $sw_desc  = get_the_excerpt();

    // End date
    $raw = get_post_meta(get_the_ID(), '_wpsw_end_date', true);
    $end_date = $raw ? $raw . " 23:59:59" : "";

    // Featured image becomes background
    if (has_post_thumbnail()) {
        $bg_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
}
wp_reset_postdata();

if ($show_section && !empty($end_date)) :
?>

<section class="relative bg-black w-full bg-cover bg-center bg-no-repeat py-16"
    style="background-image: url('<?php echo esc_url($bg_image); ?>');">
    
    <div class="bg-black/70 absolute inset-0"></div>

    <div class="relative max-w-5xl mx-auto px-4 text-center text-white">

      <!-- Heading -->
      <h2 class="text-3xl md:text-4xl font-extrabold"><?php echo esc_html($sw_title); ?></h2>

      <p class="mt-2 text-sm md:text-base opacity-90">
        <?php echo esc_html($sw_desc); ?>
      </p>

      <!-- Timer -->
      <div id="sweep-timer" 
           class="flex justify-center gap-4 mt-8"
           data-end="<?php echo esc_attr($end_date); ?>">

        <!-- Days -->
        <div class="bg-white text-black rounded-md px-6 py-4 shadow-md text-center">
          <span id="t-days" class="text-3xl font-bold">--</span>
          <p class="text-xs font-semibold uppercase mt-1">Days</p>
        </div>

        <!-- Hours -->
        <div class="bg-white text-black rounded-md px-6 py-4 shadow-md text-center">
          <span id="t-hours" class="text-3xl font-bold">--</span>
          <p class="text-xs font-semibold uppercase mt-1">Hours</p>
        </div>

        <!-- Minutes -->
        <div class="bg-white text-black rounded-md px-6 py-4 shadow-md text-center">
          <span id="t-min" class="text-3xl font-bold">--</span>
          <p class="text-xs font-semibold uppercase mt-1">Minutes</p>
        </div>

        <!-- Seconds -->
        <div class="bg-white text-black rounded-md px-6 py-4 shadow-md text-center">
          <span id="t-sec" class="text-3xl font-bold">--</span>
          <p class="text-xs font-semibold uppercase mt-1">Seconds</p>
        </div>

      </div>

      <!-- Button -->
      <button
        class="mt-10 bg-red-600 hover:bg-red-700 text-white font-medium text-sm md:text-base px-8 py-3 rounded shadow-md transition">
        Get Entries
      </button>

    </div>
</section>

<!-- Countdown Script -->
<script>
const timerBox = document.getElementById("sweep-timer");
if (timerBox) {
    const end = new Date(timerBox.dataset.end).getTime();

    setInterval(() => {
        const now = new Date().getTime();
        const diff = end - now;

        if (diff <= 0) {
            document.getElementById("t-days").innerText = "0";
            document.getElementById("t-hours").innerText = "0";
            document.getElementById("t-min").innerText = "0";
            document.getElementById("t-sec").innerText = "0";
            return;
        }

        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
        const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById("t-days").innerText = d;
        document.getElementById("t-hours").innerText = h;
        document.getElementById("t-min").innerText = m;
        document.getElementById("t-sec").innerText = s;
    }, 1000);
}
</script>

<?php endif; ?>




  <section id="stats-counter" class="py-16 bg-black text-white">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center">
          <div class="text-5xl font-bold font-display mb-2">250K+</div>
          <div class="text-gray-400 text-sm uppercase tracking-wider">Total Entries</div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold font-display mb-2">$500K</div>
          <div class="text-gray-400 text-sm uppercase tracking-wider">Prize Value</div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold font-display mb-2">15K+</div>
          <div class="text-gray-400 text-sm uppercase tracking-wider">Happy Customers</div>
        </div>
        <div class="text-center">
          <div class="text-5xl font-bold font-display mb-2">47</div>
          <div class="text-gray-400 text-sm uppercase tracking-wider">Winners So Far</div>
        </div>
      </div>
    </div>
  </section>
  <section id="testimonials" class="py-20 bg-brand-gray">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12">
        <h2 class="text-2xl xl:text-4xl font-bold mb-3 uppercase">What Our Winners Say</h2>
        <p class="text-gray-600">Real stories from real people who won big</p>
      </div>
      <?php
$loop = new WP_Query(array(
    'post_type' => 'testimonial',
    'posts_per_page' => -1,
));
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

<?php while ($loop->have_posts()) : $loop->the_post(); ?>

    <?php
        $rating = get_post_meta(get_the_ID(), 'rating', true);
        $customer_name = get_post_meta(get_the_ID(), 'customer_name', true);
        $label = get_post_meta(get_the_ID(), 'label', true);
        $image = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: 'default.jpg';
    ?>

    <div class="bg-white p-8 rounded-2xl shadow-sm flex flex-col">

        <!-- Star Rating -->
        <div class="flex text-yellow-400 mb-4">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <svg class="w-4 <?php echo $i <= $rating ? '' : 'text-gray-300'; ?>" 
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                    <path fill="currentColor"
                    d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 
                    150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 
                    7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 
                    31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 
                    23.9 4.9 33.8-2.3s14.9-19.3 
                    12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 
                    7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path>
                </svg>
            <?php endfor; ?>
        </div>

        <!-- Testimonial Text -->
        <p class="text-gray-700 mb-6 leading-relaxed"><?php echo get_the_content(); ?></p>

        <div class="flex items-center gap-3 mt-auto">
            <img src="<?php echo $image; ?>" class="w-12 h-12 rounded-full object-cover" alt="">
            <div>
                <div class="font-bold"><?php echo $customer_name; ?></div>
                <div class="text-sm text-gray-500"><?php echo $label; ?></div>
            </div>
        </div>

    </div>

<?php endwhile; wp_reset_query(); ?>

</div>

    </div>
  </section>
  <section id="cta-section" class="relative h-[500px] flex items-center overflow-hidden">
    <div class="absolute inset-0">
      <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&amp;w=2070&amp;auto=format&amp;fit=crop"
        class="w-full h-full object-cover" alt="Join Now">
      <div class="absolute inset-0 bg-black/70"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center text-white">
      <h2 class="text-5xl md:text-6xl font-bold mb-6 uppercase">Ready to Win?</h2>
      <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto font-light">Sign up now and get <span
          class="font-bold text-brand-accent">100 FREE ENTRIES</span> instantly. Every purchase multiplies your chances.
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <button
          class="bg-brand-accent hover:bg-red-700 text-white px-10 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all transform hover:scale-105 rounded-full">
          Create Free Account
        </button>
        <button
          class="bg-white hover:bg-gray-100 text-black px-10 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all rounded-full">
          Browse Store
        </button>
      </div>
    </div>
  </section>


<!-- WORDPRESS PAGE CONTENT -->


<!-- COUNTDOWN SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {

  const countdown = document.getElementById("countdown");
  if (!countdown) return;

  const endDate = new Date(countdown.dataset.end).getTime();

  const timer = setInterval(function () {
    const now = new Date().getTime();
    const diff = endDate - now;

    if (diff <= 0) {
      clearInterval(timer);
      countdown.innerHTML = "<p class='text-white text-xl font-bold'>Giveaway Ended</p>";
      return;
    }

    document.getElementById("days").innerHTML = Math.floor(diff / (1000 * 60 * 60 * 24));
    document.getElementById("hours").innerHTML = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    document.getElementById("minutes").innerHTML = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    document.getElementById("seconds").innerHTML = Math.floor((diff % (1000 * 60)) / 1000);
  }, 1000);

});
</script>


<?php get_footer(); ?>
