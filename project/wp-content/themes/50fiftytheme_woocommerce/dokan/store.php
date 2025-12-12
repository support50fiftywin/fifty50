<?php
/**
 * dokan store override template
 * Place this file in: wp-content/themes/yourtheme/dokan/store.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// --- Get vendor ID from query var (Dokan uses author base) ---
$store_user_id = (int) get_query_var( 'author' );

if ( ! $store_user_id ) {
    echo '<div class="container mx-auto px-4 py-12"><h2>Store not found</h2></div>';
    get_footer();
    return;
}

$store_user = get_userdata( $store_user_id );
if ( ! $store_user ) {
    echo '<div class="container mx-auto px-4 py-12"><h2>Store not found</h2></div>';
    get_footer();
    return;
}

// --- Dokan helpers (safe checks) ---
$store_info = array();
$store_name = $store_user->display_name;
$store_url  = '#';
$store_logo  = '';
$store_banner = '';
$store_description = '';

if ( function_exists( 'dokan_get_store_info' ) ) {
    $store_info = dokan_get_store_info( $store_user_id );
    if ( ! empty( $store_info['store_name'] ) ) {
        $store_name = $store_info['store_name'];
    }

    if ( function_exists( 'dokan_get_store_url' ) ) {
        $store_url = dokan_get_store_url( $store_user_id );
    }

    if ( ! empty( $store_info['gravatar'] ) ) {
        $store_logo = esc_url( $store_info['gravatar'] );
    } elseif ( function_exists( 'get_avatar_url' ) ) {
        $store_logo = esc_url( get_avatar_url( $store_user_id, array( 'size' => 200 ) ) );
    }

    if ( ! empty( $store_info['banner'] ) ) {
        $store_banner = esc_url( $store_info['banner'] );
    }

    if ( ! empty( $store_info['store_description'] ) ) {
        $store_description = wp_kses_post( wpautop( $store_info['store_description'] ) );
    }
}

if ( empty( $store_description ) ) {
    $store_description = wp_kses_post( wpautop( get_user_meta( $store_user_id, 'description', true ) ) );
}

// ---------------------
// Find merchant sweepstake (scheduled or active) — SAFE QUERY
// ---------------------
$sweep_title = '';
$sweep_desc  = '';
$sweep_end_date = '';

$sweep_args = array(
    'post_type'      => 'sweepstake',
    'posts_per_page' => 1,
    'meta_query'     => array(
        array(
            'key'     => 'merchant_id',
            'value'   => $store_user_id,
            'compare' => '=',
        ),
        array(
            'key'     => 'status',
            'value'   => array( 'scheduled', 'active' ),
            'compare' => 'IN',
        ),
    ),
    'orderby'        => 'meta_value',
    'meta_key'       => 'start_date',
    'order'          => 'ASC',
);

// wrap WP_Query in try-safe logic
$sweep_query = new WP_Query( $sweep_args );
if ( $sweep_query->have_posts() ) {
    $sweep_query->the_post();
    $sweep_title    = get_the_title() ?: '';
    $sweep_desc     = get_the_excerpt() ?: '';
    $sweep_end_meta = get_post_meta( get_the_ID(), 'end_date', true );

    // If end_date exists, convert to ISO 8601 for JS. If it's in 'YYYY-MM-DDTHH:MM' format,
    // strtotime should handle it. If conversion fails leave blank.
    if ( ! empty( $sweep_end_meta ) ) {
        $ts = strtotime( $sweep_end_meta );
        if ( $ts ) {
            $sweep_end_date = date( 'c', $ts ); // ISO 8601 e.g. 2025-12-11T13:00:00+05:30
        } else {
            // try to trust raw value as ISO; otherwise leave blank
            $sweep_end_date = esc_attr( $sweep_end_meta );
        }
    }

    wp_reset_postdata();
}

// ---------------------
// Vendor products query (author = vendor id)
// ---------------------
$products_args = array(
    'post_type'      => 'product',
    'posts_per_page' => 12,
    'post_status'    => 'publish',
    'author'         => $store_user_id,
);

$products_query = new WP_Query( $products_args );

// --- Begin output ---
?>
<div id="dokan-override-store-template">

    <section id="hero" class="relative w-full h-[600px] md:h-[700px] bg-black overflow-hidden flex items-center">
        <div class="absolute inset-0 z-0">
            <img
                src="<?php echo esc_url( $store_banner ?: 'https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=2070&auto=format&fit=crop' ); ?>"
                class="w-full h-full object-cover opacity-20" alt="<?php echo esc_attr( $store_name ); ?> Banner">
        </div>

        <div class="container mx-auto px-4 relative z-10 flex flex-col items-center text-center text-white max-w-4xl">
            <?php if ( $store_logo ) : ?>
                <img src="<?php echo esc_url( $store_logo ); ?>" alt="<?php echo esc_attr( $store_name ); ?> Logo" class="w-24 h-24 rounded-full border-4 border-white mb-4 shadow-lg">
            <?php endif; ?>

            <span
                class="inline-flex items-center gap-3 py-2 px-5 border border-white/30 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold uppercase tracking-widest mb-6">
                <i><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M9 17C9 17 16 18 19 21H20C20.5523 21 21 20.5523 21 20V13.937C21.8626 13.715 22.5 12.9319 22.5 12C22.5 11.0681 21.8626 10.285 21 10.063V4C21 3.44772 20.5523 3 20 3H19C16 6 9 7 9 7H5C3.89543 7 3 7.89543 3 9V15C3 16.1046 3.89543 17 5 17H6L7 22H9V17ZM11 8.6612C11.6833 8.5146 12.5275 8.31193 13.4393 8.04373C15.1175 7.55014 17.25 6.77262 19 5.57458V18.4254C17.25 17.2274 15.1175 16.4499 13.4393 15.9563C12.5275 15.6881 11.6833 15.4854 11 15.3388V8.6612ZM5 9H9V15H5V9Z"
                        fill="white" />
                    </svg>
                </i>
                <?php echo esc_html( $store_name ); ?>'s Exclusive Sweepstakes!
            </span>

            <h1 class="text-3xl md:text-4xl lg:text-7xl font-bold mb-6 leading-tight">
                <?php echo esc_html( $sweep_title ); ?>
            </h1>

            <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl font-light">
                <?php echo esc_html( $sweep_desc ); ?>
            </p>

            <div class="flex flex-col md:flex-row gap-4 w-full justify-center items-center">
                <a href="<?php echo esc_url( $store_url ); ?>"
                    class="bg-brand-accent hover:bg-red-700 text-white px-8 py-3 rounded-btn font-semibold text-md tracking-wider transition-all rounded-full flex gap-2 items-center">
                    <?php if ( $sweep_id ) : ?>
                        Enter To Win (See Products)
                    <?php else : ?>
                        Visit Store Products
                    <?php endif; ?>
                </a>

                <button
                    class="bg-white hover:bg-gray-100 text-black px-8 py-3 rounded-btn font-bold text-md tracking-wider transition-all rounded-full flex gap-2 items-center">
                    <i>
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9 1.5C8.40326 1.5 7.83097 1.73705 7.40901 2.15901C6.98705 2.58097 6.75 3.15326 6.75 3.75V9C6.75 9.59674 6.98705 10.169 7.40901 10.591C7.83097 11.0129 8.40326 11.25 9 11.25C9.59674 11.25 10.169 11.0129 10.591 10.591C11.0129 10.169 11.25 9.59674 11.25 9V3.75C11.25 3.15326 11.0129 2.58097 10.591 2.15901C10.169 1.73705 9.59674 1.5 9 1.5Z"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M14.25 7.5V9C14.25 10.3924 13.6969 11.7277 12.7123 12.7123C11.7277 13.6969 10.3924 14.25 9 14.25C7.60761 14.25 6.27226 13.6969 5.28769 12.7123C4.30312 11.7277 3.75 10.3924 3.75 9V7.5"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9 14.25V16.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </i>
                    For Podcasters
                </button>
            </div>

            <div class="mt-12 flex flex-col md:flex-row gap-6 text-sm items-center">
                <div class="flex items-center gap-2">
                    <i></i>
                    <span><b>15,000+</b>Entries This Month</span>
                </div>
                <div class="flex items-center gap-2">
                    <i></i>
                    <span><b>500+</b> Active Merchants</span>
                </div>
                <div class="flex items-center gap-2">
                    <i></i>
                    <span><b>$250K+</b> in Prizes Won</span>
                </div>
            </div>
        </div>
    </section>

    <?php if ( $sweep_end_date ) : ?>
        <section class="w-full bg-brand-accent py-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-col xl:flex-row items-center justify-between gap-4">

                    <div class="col-left text-center xl:text-left">
                        <h2 class="text-white text-2xl font-bold uppercase tracking-wide">
                            <?php echo esc_html( $sweep_title ); ?>
                        </h2>
                        <p class="text-white text-sm mt-1 opacity-80">
                            Ends In
                        </p>
                    </div>

                    <div id="countdown-timer" class="flex gap-4 text-center">
                        <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
                            <span class="block text-2xl font-bold text-gray-800" id="timer-days">00</span>
                            <span class="block text-xs font-medium text-gray-600 uppercase">Days</span>
                        </div>
                        <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
                            <span class="block text-2xl font-bold text-gray-800" id="timer-hours">00</span>
                            <span class="block text-xs font-medium text-gray-600 uppercase">Hrs</span>
                        </div>
                        <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
                            <span class="block text-2xl font-bold text-gray-800" id="timer-minutes">00</span>
                            <span class="block text-xs font-medium text-gray-600 uppercase">Min</span>
                        </div>
                        <div class="bg-white rounded-xl px-4 py-3 shadow-md text-center">
                            <span class="block text-2xl font-bold text-gray-800" id="timer-seconds">00</span>
                            <span class="block text-xs font-medium text-gray-600 uppercase">Sec</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                var endDateISO = '<?php echo esc_js( $sweep_end_date ); ?>';
                if (!endDateISO) return;

                var countdownDate = new Date(endDateISO).getTime();
                var timerElement = document.getElementById('countdown-timer');

                // Update the count down every 1 second
                var x = setInterval(function() {

                    var now = new Date().getTime();
                    var distance = countdownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Output the result in elements with matching IDs
                    document.getElementById("timer-days").innerHTML = (days < 10 ? '0' : '') + days;
                    document.getElementById("timer-hours").innerHTML = (hours < 10 ? '0' : '') + hours;
                    document.getElementById("timer-minutes").innerHTML = (minutes < 10 ? '0' : '') + minutes;
                    document.getElementById("timer-seconds").innerHTML = (seconds < 10 ? '0' : '') + seconds;

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        timerElement.innerHTML = '<span class="text-white text-xl font-bold">GIVEAWAY ENDED</span>';
                    }
                }, 1000);
            });
        </script>
    <?php endif; ?>

    <section class="bg-white py-16 text-slate-900">
        <div class="container mx-auto px-4">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-bold text-slate-900 md:text-4xl uppercase">
                    <?php echo esc_html( $store_name ); ?>'s Products
                </h2>
                <p class="mt-3 text-md text-slate-500 md:text-xl">
                    Shop now and automatically receive entries into our latest sweepstakes!
                </p>
            </div>

            <?php if ( $products_query->have_posts() ) : ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php while ( $products_query->have_posts() ) : $products_query->the_post(); ?>
                        <?php global $product; // Set up product data ?>
                        <div class="product-card border rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <a href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
                                <?php else : ?>
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                                <?php endif; ?>
                            </a>

                            <div class="p-4 text-center">
                                <h3 class="text-lg font-semibold mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-brand-accent transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <div class="text-xl font-bold text-brand-accent mb-3">
                                    <?php echo $product->get_price_html(); ?>
                                </div>

                                <?php
                                    // Get the Add to Cart URL
                                    $add_to_cart_url = $product->add_to_cart_url();
                                ?>
                                <a href="<?php echo esc_url( $add_to_cart_url ); ?>"
                                    data-quantity="1"
                                    data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
                                    class="button add_to_cart_button product_type_<?php echo esc_attr( $product->get_type() ); ?> w-full bg-slate-900 text-white py-2 rounded-full font-semibold hover:bg-slate-700 transition-colors">
                                    Buy & Get Entries
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="text-center text-lg text-slate-500">
                    <?php echo esc_html( $store_name ); ?> does not have any products yet.
                </p>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>

    <section class="bg-white py-16 text-slate-900">
        <div class="mx-auto px-4 xl:container">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-bold text-slate-900 md:text-4xl uppercase">How 50Fifty Works</h2>
                <p class="mt-3 text-md text-slate-500 md:text-xl">
                    Three ways to participate in the 50Fifty ecosystem. Choose your role and start winning together.
                </p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="rounded-3xl overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.06)] border">
                    <div class="bg-[#0A63FF] text-white p-8 h-[260px] flex flex-col">
                        <div class="text-4xl mb-4">
                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M31.25 43.75V33.3333C31.25 32.7808 31.0305 32.2509 30.6398 31.8602C30.2491 31.4695 29.7192 31.25 29.1667 31.25H20.8333C20.2808 31.25 19.7509 31.4695 19.3602 31.8602C18.9695 32.2509 18.75 32.7808 18.75 33.3333V43.75"
                                    stroke="#FDFDFD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M37.0292 21.4791C36.5949 21.0634 36.0169 20.8313 35.4156 20.8313C34.8144 20.8313 34.2364 21.0634 33.8021 21.4791C32.8334 22.4031 31.546 22.9186 30.2073 22.9186C28.8686 22.9186 27.5812 22.4031 26.6125 21.4791C26.1783 21.064 25.6007 20.8323 25 20.8323C24.3993 20.8323 23.8217 21.064 23.3875 21.4791C22.4187 22.4037 21.1309 22.9196 19.7917 22.9196C18.4524 22.9196 17.1647 22.4037 16.1958 21.4791C15.7615 21.0634 15.1835 20.8313 14.5823 20.8313C13.9811 20.8313 13.4031 21.0634 12.9688 21.4791C12.033 22.3721 10.7985 22.8849 9.50547 22.9178C8.21246 22.9506 6.95347 22.5012 5.97356 21.657C4.99364 20.8128 4.36291 19.6341 4.20414 18.3505C4.04536 17.0668 4.3699 15.77 5.11459 14.7125L11.1333 5.99579C11.5152 5.43227 12.0294 4.97091 12.6308 4.65203C13.2322 4.33316 13.9026 4.1665 14.5833 4.16663H35.4167C36.0954 4.16637 36.7639 4.33192 37.3641 4.64889C37.9643 4.96587 38.478 5.42466 38.8604 5.98538L44.8917 14.7187C45.6365 15.7771 45.9607 17.0749 45.8009 18.3592C45.6411 19.6435 45.0089 20.8223 44.0274 21.666C43.046 22.5096 41.7856 22.9576 40.4918 22.9227C39.1981 22.8878 37.9637 22.3724 37.0292 21.477"
                                    stroke="#FDFDFD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M8.33334 22.8125V39.5833C8.33334 40.6884 8.77232 41.7482 9.55372 42.5296C10.3351 43.311 11.3949 43.75 12.5 43.75H37.5C38.6051 43.75 39.6649 43.311 40.4463 42.5296C41.2277 41.7482 41.6667 40.6884 41.6667 39.5833V22.8125"
                                    stroke="#FDFDFD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2">For Merchants</h3>
                        <p class="text-md">Sell custom merchandise with entries included. Get 15% commission on every sale.</p>
                    </div>
                    <div class="bg-white p-8 flex flex-col">
                        <ul class="space-y-4 text-md text-gray-800">
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> Custom logos & taglines</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> POS integration with Clover</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> QR codes & downloadable flyers</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> Unique merchant page (e.g., 50fifty.win/justinospizza)</li>
                        </ul>
                        <button class="mt-8 bg-[#0A63FF] text-white w-full py-3 rounded-full font-semibold">Become a Merchant</button>
                    </div>
                </div>

                <div class="rounded-3xl overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.06)] border">
                    <div class="bg-[#A92CF6] text-white p-8 h-[260px] flex flex-col">
                        <div class="text-4xl mb-4">
                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M33.8479 16.1687C36.1875 18.5123 37.5015 21.6885 37.5015 25C37.5015 28.3114 36.1875 31.4876 33.8479 33.8312"
                                    stroke="#FFF6F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M39.7396 10.2771C43.6414 14.1834 45.833 19.4788 45.833 25C45.833 30.5212 43.6414 35.8166 39.7396 39.7229"
                                    stroke="#FFF6F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M10.2604 39.7229C6.35858 35.8166 4.16693 30.5212 4.16693 25C4.16693 19.4788 6.35858 14.1834 10.2604 10.2771"
                                    stroke="#FFF6F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M16.1521 33.8312C13.8125 31.4876 12.4985 28.3114 12.4985 25C12.4985 21.6885 13.8125 18.5123 16.1521 16.1687"
                                    stroke="#FFF6F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M25 29.1667C27.3012 29.1667 29.1666 27.3012 29.1666 25C29.1666 22.6989 27.3012 20.8334 25 20.8334C22.6988 20.8334 20.8333 22.6989 20.8333 25C20.8333 27.3012 22.6988 29.1667 25 29.1667Z"
                                    stroke="#FFF6F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2">For Influencers & Podcasters</h3>
                        <p class="text-md">Reward your audience with entries. Grow engagement and build loyalty.</p>
                    </div>
                    <div class="bg-white p-8 flex flex-col">
                        <ul class="space-y-4 text-md text-gray-800">
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> 5000 entries for just $49</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> Give 100 entries per subscriber</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> Custom branded page (e.g., 50fifty.win/theRizPodcast)</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> QR codes for easy sharing</li>
                        </ul>
                        <button class="mt-8 bg-[#A92CF6] text-white w-full py-3 rounded-full font-semibold">Start Rewarding Fans</button>
                    </div>
                </div>

                <div class="rounded-3xl overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.06)] border">
                    <div class="bg-[#FF35A1] text-white p-8 h-[260px] flex flex-col">
                        <div class="text-4xl mb-4">
                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M41.6667 16.6666H8.33333C7.18274 16.6666 6.25 17.5994 6.25 18.75V22.9166C6.25 24.0672 7.18274 25 8.33333 25H41.6667C42.8173 25 43.75 24.0672 43.75 22.9166V18.75C43.75 17.5994 42.8173 16.6666 41.6667 16.6666Z"
                                    stroke="#FFF5F5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M25 16.6666V43.75" stroke="#FFF5F5" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M39.5834 25V39.5833C39.5834 40.6884 39.1444 41.7482 38.363 42.5296C37.5816 43.311 36.5218 43.75 35.4167 43.75H14.5834C13.4783 43.75 12.4185 43.311 11.6371 42.5296C10.8557 41.7482 10.4167 40.6884 10.4167 39.5833V25"
                                    stroke="#FFF5F5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M15.625 16.6667C14.2437 16.6667 12.9189 16.118 11.9422 15.1412C10.9654 14.1645 10.4167 12.8397 10.4167 11.4584C10.4167 10.0771 10.9654 8.75229 11.9422 7.77554C12.9189 6.79879 14.2437 6.25006 15.625 6.25006C17.6348 6.21504 19.6042 7.19018 21.2766 9.04831C22.9489 10.9064 24.2464 13.5613 25 16.6667C25.7536 13.5613 27.0512 10.9064 28.7235 9.04831C30.3958 7.19018 32.3653 6.21504 34.375 6.25006C35.7564 6.25006 37.0811 6.79879 38.0579 7.77554C39.0346 8.75229 39.5834 10.0771 39.5834 11.4584C39.5834 12.8397 39.0346 14.1645 38.0579 15.1412C37.0811 16.118 35.7564 16.6667 34.375 16.6667"
                                    stroke="#FFF5F5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold mb-2">For Customers</h3>
                        <p class="text-md">Shop, enter, and win! Every purchase gives you entries to monthly drawings.</p>
                    </div>
                    <div class="bg-white p-8 flex flex-col">
                        <ul class="space-y-4 text-md text-gray-800">
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> Automatic entry with every purchase</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> Text confirmation for all entries</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> Personal login to track entries</li>
                            <li class="flex gap-3 items-start"><span class="text-green-500 text-lg leading-none">●</span> 12 monthly updates with special offers</li>
                        </ul>
                        <button class="mt-8 bg-[#FF35A1] text-white w-full py-3 rounded-full font-semibold">Enter to Win</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 px-4">
        <div class="container mx-auto px-3">
            <div class="p-10 text-center text-white bg-gradient-to-r from-fuchsia-600 via-pink-500 to-orange-400 rounded-2xl">
                <div class="py-10">
                    <h2 class="text-2xl font-bold md:text-5xl pb-4">Get Your Custom Landing Page</h2>
                    <p class="mt-3 text-lg text-fuchsia-50 md:text-xl max-w-[800px] mx-auto">
                        Every merchant and influencer gets a unique, branded page with custom logos,
                        taglines, and designs. Set up in minutes, start earning immediately.
                    </p>
                    <div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row sm:justify-center">
                        <button
                            class="rounded-full bg-white px-10 py-4 text-lg font-semibold uppercase tracking-wide text-fuchsia-700 hover:bg-fuchsia-50">
                            Create Your Page Now
                        </button>
                        <button
                            class="rounded-full border border-white/60 px-10 py-4 text-lg font-semibold uppercase tracking-wide text-white hover:bg-white/10">
                            Contact us
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 text-slate-900">
        <div class="container mx-auto px-4">
            <div class="flex flex-col gap-10 xl:flex-row xl:items-center pb-20">
                <div class="xl:w-1/2">
                    <div class="overflow-hidden rounded-2xl shadow-lg">
                        <img
                            src="https://images.pexels.com/photos/2156816/pexels-photo-2156816.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Storefront" class="h-full w-full object-cover" />
                    </div>
                </div>

                <div class="xl:w-1/2">
                    <h2 class="text-2xl font-semibold md:text-4xl pb-3">Why Choose 50Fifty</h2>
                    <p class="mt-3 text-md text-slate-500 md:text-lg">
                        A revolutionary platform that connects merchants, influencers, and customers in a win-win-win ecosystem.
                    </p>
                    <div class="mt-8 grid gap-6 md:grid-cols-2">
                        <div class="flex gap-3">
                            <div class="mt-1 text-lg">🛍️</div>
                            <div>
                                <h3 class="text-lg font-semibold">Shop &amp; Win</h3>
                                <p class="text-md text-slate-500">
                                    Every purchase from participating merchants comes with entries.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-1 text-lg">📲</div>
                            <div>
                                <h3 class="text-lg font-semibold">Stay Connected</h3>
                                <p class="text-md text-slate-500">
                                    Receive 12 text messages or emails per year with exclusive offers.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-1 text-lg">📺</div>
                            <div>
                                <h3 class="text-lg font-semibold">Live Drawings</h3>
                                <p class="text-md text-slate-500">
                                    Watch live drawings on our website and social channels.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-1 text-lg">🚀</div>
                            <div>
                                <h3 class="text-lg font-semibold">Grow Your Business</h3>
                                <p class="text-md text-slate-500">
                                    Increase foot traffic, boost order size, and build loyalty.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-1 text-lg">⚡</div>
                            <div>
                                <h3 class="text-lg font-semibold">Easy Entry</h3>
                                <p class="text-md text-slate-500">
                                    Enter via POS, receipts, or QR codes in just seconds.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="mt-1 text-lg">
                                ✅
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">Win Big</h3>
                                <p class="text-md text-slate-500">
                                    Monthly cash prizes and grand prize drawings throughout the year.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<section class="vendor-sweepstakes-section">

    <!-- Vendor Sweepstake List -->
    <div class="vendor-list">
        <?php echo do_shortcode('[sweepstake_vendor_list]'); ?>
    </div>

    <!-- Vendor Sweepstake Create Form -->
    <div class="vendor-form mt-6">
        <?php echo do_shortcode('[sweepstake_vendor_form]'); ?>
    </div>

    <!-- Sweepstake Winners -->
    <div class="vendor-winners mt-6">
        <?php echo do_shortcode('[sweepstake_winners]'); ?>
    </div>

    <!-- Specific Sweepstake Winners -->
    <div class="vendor-winners-specific mt-6">
        <?php echo do_shortcode('[sweepstake_winners sweepstake_id="123"]'); ?>
    </div>

</section>



</div>

<?php
get_footer();
// The end of the file.