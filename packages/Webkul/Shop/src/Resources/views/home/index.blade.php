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
                Every <span class="font-bold text-white">$1 spent</span> on custom merch gets you <span class="font-bold text-brand-accent" style="color: #e11d48;">10 ENTRIES</span> to win.
            </p>
            <div class="flex flex-col md:flex-row gap-4 w-full justify-center">
                <a href="#featured-products" class="bg-brand-accent hover:bg-red-700 text-white px-8 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all transform hover:scale-105 shadow-lg shadow-red-900/50 rounded-full" style="background-color: #e11d48;">
                    Shop & Enter Now
                </a>
            </div>
        </div>
    </section>

    {{-- 2. PRODUCTS SECTION (Dynamic) --}}
    <section id="featured-products" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-2xl xl:text-4xl font-bold mb-2 uppercase">Featured Collection</h2>
                    <p class="text-gray-500">Premium gear that gets you closer to the prize.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                @php
                    // Fetch latest 8 products using Bagisto's Repository
                    $productRepository = app('Webkul\Product\Repositories\ProductRepository');
                    $products = $productRepository->getAll(['limit' => 8, 'new' => 1, 'status' => 1]);
                @endphp

                @foreach ($products as $product)
                    @php
                        // Get Product Image
                        $images = product_image()->getGalleryImages($product);
                        $mainImage = $images[0]['medium_image_url'] ?? bagisto_asset('images/default-product-image.jpg');
                        
                        // Calculate Entries (Price * 10)
                        $price = $product->getTypeInstance()->getMinimalPrice();
                        $entries = (int)$price * 10;
                    @endphp

                    <div class="group">
                        <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/5] mb-4">
                            <img 
                                src="{{ $mainImage }}" 
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" 
                                alt="{{ $product->name }}"
                            >
                            
                            <div class="absolute top-3 right-3 bg-brand-accent text-white text-xs font-bold px-2 py-1 rounded" style="background-color: #e11d48;">
                                {{ $entries }} ENTRIES
                            </div>

                            <form action="" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                <input type="hidden" name="quantity" value="1">
                                
                                <button 
                                    type="submit" 
                                    class="absolute bottom-4 left-4 right-4 bg-white text-black font-bold py-3 rounded-btn opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all shadow-lg rounded-full cursor-pointer"
                                    @if ($product->type == 'configurable') disabled @endif 
                                >
                                    @if ($product->type == 'configurable')
                                        Select Options
                                    @else
                                        Add to Cart
                                    @endif
                                </button>
                            </form>
                        </div>

                        <h3 class="font-bold text-lg mb-1 group-hover:text-brand-accent transition-colors uppercase">
                            <a href="">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-900 font-medium">
                                {{ core()->currency($price) }}
                            </span>
                            
                            <div class="flex text-yellow-400 text-xs">
                                <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                                <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"></path></svg>
                            </div>
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
            <a href="#" class="bg-brand-accent hover:bg-red-700 text-white px-10 py-4 rounded-btn font-bold text-lg uppercase tracking-wider transition-all transform hover:scale-105 rounded-full" style="background-color: #e11d48;">
              Create Free Account
            </a>
          </div>
        </div>
      </section>

</x-shop::layouts>

@push('scripts')
    <script>
        localStorage.setItem('categories', JSON.stringify(@json($categories ?? [])));
    </script>
@endpush