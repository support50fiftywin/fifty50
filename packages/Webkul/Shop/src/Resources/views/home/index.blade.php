@php
    $channel = core()->getCurrentChannel();
@endphp

@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '50FIFTY.WIN - Shop Merch, Win Prizes' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? 'Every $1 spent on custom merch gets you 10 ENTRIES to win a dream prize.' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? 'giveaway, sweepstakes, win car, contest, merch, 50fifty' }}"
    />
@endPush

@push('scripts')
    <script>
        localStorage.setItem('categories', JSON.stringify(@json($categories)));
    </script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const emitter =
        window.app?.config?.globalProperties?.$emitter || null;

    window.addToCart = function (productId, type, redirectUrl) {

        // ❌ Non-simple must go to product page
        if (type !== 'simple') {
            window.location.href = redirectUrl;
            return;
        }

        axios.post('{{ route('shop.api.checkout.cart.store') }}', {
            product_id: productId,
            quantity: 1,
        })
        .then(response => {

            // ✅ Update mini cart & cart badge
            if (emitter && response.data?.data) {
                emitter.emit('update-mini-cart', response.data.data);
            }

            // ✅ Success toast
            if (emitter && response.data?.message) {
                emitter.emit('add-flash', {
                    type: 'success',
                    message: response.data.message
                });
            }

        })
        .catch(error => {

            const res = error.response?.data;

            if (emitter) {
                emitter.emit('add-flash', {
                    type: 'error',
                    message: res?.message || 'Unable to add product.'
                });
            }

            if (res?.redirect_uri) {
                window.location.href = res.redirect_uri;
            }
        });
    };

});
</script>
@endpush

<x-shop::layouts>
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? '50FIFTY.WIN - Shop Merch, Win Prizes' }}
    </x-slot>

    {{-- 1. HERO SECTION (From your HTML) --}}
    <section id="hero" class="relative w-full h-[600px] md:h-[700px] bg-black overflow-hidden flex items-center">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-20" alt="Supercar Prize">
        </div>
        
        <div class="container mx-auto px-4 relative z-10 flex flex-col items-center text-center text-white max-w-4xl">
            <span class="inline-block py-1 px-3 border border-white/30 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold uppercase tracking-widest mb-6">
                Current Giveaway Ends Soon
            </span>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-6 leading-tight">
                WIN THIS <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">DREAM RIDE</span>
            </h1>
           
			<p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl font-light">
				Every <span class="font-bold text-white">$1 spent</span> 
				on custom merch gets you <span
          class="font-bold text-brand-accent">10 ENTRIES</span>
		  to win.
          <br class="hidden md:block">Create an account today and get 
		  <span
          class="font-bold underline decoration-brand-accent">100 FREE ENTRIES</span> instantly.
		  
      </p>
         
		 <div class="flex flex-col md:flex-row gap-4 w-full justify-center">
                <a href="{{ route('shop.search.index') }}" class="bg-brand-accent hover:bg-red-700 text-white px-8 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all transform hover:scale-105 shadow-lg shadow-red-900/50 rounded-full" style="background-color: #e11d48;">
                    Shop & Enter Now
                </a>
				
				<a
          href="{{ route('shop.home.index') }}" class="bg-white hover:bg-gray-100 text-black px-8 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all rounded-full">
          View Prizesss
        </a>
            </div>
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
    {{-- 2. PRODUCTS SECTION (Dynamic) --}}
    <section id="featured-products" class="py-20 bg-white">
        <div class="container mx-auto px-4">

            <h2 class="text-3xl font-bold uppercase mb-10">
                Featured Collection
            </h2>

            @php
                $productRepository = app(\Webkul\Product\Repositories\ProductRepository::class);
                $products = $productRepository->getAll([
                    'limit' => 8,
                    'status' => 1,
                ]);
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                @foreach ($products as $product)
                    @php
                        $images = product_image()->getGalleryImages($product);
                        $image  = $images[0]['medium_image_url']
                                    ?? bagisto_asset('images/default-product-image.jpg');

                        $price = $product->getTypeInstance()->getMinimalPrice();
						$entries = (int) ($product->entries ?? 0);
                    @endphp

                    <div class="border rounded-xl overflow-hidden group bg-white">

                        {{-- IMAGE --}}
                        <div class="relative aspect-[4/5] bg-gray-100">
                            <img
                                src="{{ $image }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition"
                            />
                           @if ($entries > 0)
							<div class="absolute top-3 right-3 bg-brand-accent text-white text-xs font-bold px-2 py-1 rounded">
								{{ $entries }} ENTRIES
							</div>
							@endif
                            {{-- CTA --}}
                            @if ($product->type === 'simple')
                                <button
                                    type="button"
                                    onclick="addToCart(
                                        {{ $product->id }},
                                        '{{ $product->type }}',
                                        '{{ route('shop.product_or_category.index', $product->url_key) }}'
                                    )"
                                    class="absolute bottom-4 left-4 right-4 bg-white py-3 rounded-full font-bold opacity-0 group-hover:opacity-100 transition"
                                >
                                    Add to Cart
                                </button>
                            @else
                                <a
                                    href="{{ route('shop.product_or_category.index', $product->url_key) }}"
                                    class="absolute bottom-4 left-4 right-4 bg-white py-3 rounded-full font-bold text-center opacity-0 group-hover:opacity-100 transition"
                                >
                                    Select Options
                                </a>
                            @endif
                        </div>

                        {{-- DETAILS --}}
                        <div class="p-4">
                            <a
                                href="{{ route('shop.product_or_category.index', $product->url_key) }}"
                                class="font-bold block hover:text-rose-600"
                            >
                                {{ $product->name }}
                            </a>

                            <span class="font-semibold">
                                {{ core()->currency($price) }}
                            </span>
                        </div>
						

                    </div>
                @endforeach

            </div>
        </div>
    </section>


    {{-- 3. CALL TO ACTION SECTION --}}
    <section id="cta-section" class="relative h-[500px] flex items-center overflow-hidden mt-12">
        <div class="absolute inset-0">
          <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover" alt="Join Now">
          <div class="absolute inset-0 bg-black/70"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center text-white">
          <h2 class="text-5xl md:text-6xl font-bold mb-6 uppercase">Ready to Win?</h2>
          <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto font-light">Sign up now and get <span class="font-bold text-brand-accent" style="color: #e11d48;">100 FREE ENTRIES</span> instantly.</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.customer.session.create') }}?type=register" class="bg-brand-accent hover:bg-red-700 text-white px-10 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all transform hover:scale-105 rounded-full" style="background-color: #e11d48;">
			 Create Free Account
            </a>
          </div>
        </div>
      </section>

</x-shop::layouts>





