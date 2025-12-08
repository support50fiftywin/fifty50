@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">
    <head>
        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="base-url" content="{{ url()->to('/') }}">
        <meta name="currency" content="{{ core()->getCurrentCurrency()->toJson() }}">

        @stack('meta')

        <link
            rel="icon"
            sizes="16x16"
            href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}"
        />

        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

        <link rel="stylesheet" href="{{ asset('dist/style.css') }}">
        <link rel="stylesheet" href="{{ asset('dist/custom.css') }}">
         <!--script src="{{ asset('src/jquery-3.7.1.min.js') }}"></script-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" />

        @stack('styles')

        <style>
            {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        </style>

        {!! view_render_event('bagisto.shop.layout.head.after') !!}
    </head>

    <body class="bg-white text-brand-black antialiased">
        {!! view_render_event('bagisto.shop.layout.body.before') !!}

        <div id="app">
            <x-shop::flash-group />
            <x-shop::modal.confirm />

            <div id="top-bar" class="bg-brand-accent text-white py-2 text-center text-xs md:text-sm font-bold tracking-wider uppercase flex align-middle justify-center" style="background-color: #e11d48;">
                <i class="mr-2 inline-block">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width: 1.25rem; height: 1.25rem; fill: currentColor;">
                        <path fill="currentColor" d="M159.3 5.4c7.8-7.3 19.9-7.2 27.7 .1c27.6 25.9 53.5 53.8 77.7 84c11-14.4 23.5-30.1 37-42.9c7.9-7.4 20.1-7.4 28 .1c34.6 33 63.9 76.6 84.5 118c20.3 40.8 33.8 82.5 33.8 111.9C448 404.2 348.2 512 224 512C98.4 512 0 404.1 0 276.5c0-38.4 17.8-85.3 45.4-131.7C73.3 97.7 112.7 48.6 159.3 5.4zM225.7 416c25.3 0 47.7-7 68.8-21c42.1-29.4 53.4-88.2 28.1-134.4c-4.5-9-16-9.6-22.5-2l-25.2 29.3c-6.6 7.6-18.5 7.4-24.7-.5c-16.5-21-46-58.5-62.8-79.8c-6.3-8-18.3-8.1-24.7-.1c-33.8 42.5-50.8 69.3-50.8 99.4C112 375.4 162.6 416 225.7 416z"></path>
                    </svg>
                </i>
                10 Entries for every $1 spent | Sign up now for 100 FREE ENTRIES
            </div>

            @if ($hasHeader)
                <header id="header" class="sticky top-0 z-50 bg-white/95 border-b border-gray-100">
                    <div class="container mx-auto px-4 md:px-6 h-20 flex items-center justify-between gap-5">
                        <a href="{{ route('shop.home.index') }}" class="flex items-center gap-2 group">
                            <div class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                                50</div>
                            <span class="text-2xl font-bold font-display tracking-tighter">50FIFTY<span class="text-brand-accent" style="color: #e11d48;">.WIN</span></span>
                        </a>

                        <div class="flex items-center gap-3 xl:flex-1">
                            <div id="NAV-OFFSET" class="header-nav xl:contents">
                                <nav class="flex flex-col xl:flex-row xl:items-center gap-3 xl:gap-8 font-medium text-sm uppercase tracking-wide flex-1 justify-center">
                                    <a href="#" class="hover:text-brand-accent transition-colors">Shop Merch</a>
                                    <a href="#" class="hover:text-brand-accent transition-colors">Past Winners</a>
                                    <a href="#" class="hover:text-brand-accent transition-colors">How it Works</a>
                                </nav>
                                <div class="flex flex-col xl:flex-row gap-2 mt-5 mt-xl-0">
                                    <button class="px-4 py-2 text-xs font-medium border border-black rounded-btn hover:bg-black hover:text-white transition-colors rounded-full uppercase">
                                        For Podcasters
                                    </button>
                                </div>
                            </div>

                            <div class="h-6 w-px bg-gray-300 mx-1 hidden xl:block"></div>
                            
                           @auth('customer')
							<a href="{{ route('shop.customers.account.index') }}" class="text-sm font-medium hover:text-brand-accent block uppercase">
								Account
							</a>
							@else
							<a href="{{ route('shop.customer.session.index') }}" class="text-sm font-medium hover:text-brand-accent block uppercase">
							Login
							</a>
							@endauth

                            <a
								href="{{ route('shop.checkout.cart.index') }}"
								class="relative p-2 hover:text-brand-accent transition-colors"
							>
								<i class="text-lg">
									<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor">
										<path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"></path>
									</svg>
								</i>

								<span id="cart-count"
									class="absolute top-0 right-0 bg-black text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center"
								>
									{{ cart()->getCart() ? cart()->getCart()->items_count : 0 }}
								</span>
							</a>

                        </div>
                    </div>
                </header>
            @endif

            {!! view_render_event('bagisto.shop.layout.content.before') !!}

            <main id="main">
                {{ $slot }}
            </main>

            {!! view_render_event('bagisto.shop.layout.content.after') !!}

            @if ($hasFooter)
                <footer id="footer" class="bg-brand-dark text-white py-16" style="background-color: #111;">
                    <div class="container mx-auto px-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-10 h-10 bg-white text-black rounded-full flex items-center justify-center font-bold text-lg">
                                        50
                                    </div>
                                    <span class="text-2xl font-bold font-display">50FIFTY<span class="text-brand-accent" style="color: #e11d48;">.WIN</span></span>
                                </div>
                                <p class="text-gray-400 text-sm">Shop custom merch, earn entries, and win incredible prizes.</p>
                            </div>
                            <div>
                                <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">Quick Links</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Shop All</a></li>
                                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">How It Works</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-bold mb-4 text-sm uppercase tracking-wider">Support</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact Us</a></li>
                                    <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Terms &amp; Conditions</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-gray-500 text-sm">© {{ date('Y') }} 50fifty.win. All rights reserved.</p>
                        </div>
                    </div>
                </footer>
            @endif
        </div>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}

        @stack('scripts')

        {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}
        <script>
            window.addEventListener("load", function (event) {
                app.mount("#app");
            });
        </script>
        {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}

        <script type="text/javascript">
            {!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}
        </script>

        <script>
            $(document).ready(function () {
                $(document).on('click', '.js--toggle', function (e) {
                    e.preventDefault();
                    $($(this).attr('href')).toggleClass('is-visible');
                    $(this).toggleClass('is-active');
                    $('html').toggleClass('is--toggle');
                });
            });
        </script>

    </body>
</html>