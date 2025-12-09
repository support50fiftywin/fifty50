@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>
<html lang="en" dir="{{ core()->getCurrentLocale()->direction }}">

<head>
    {!! view_render_event('bagisto.shop.layout.head.before') !!}

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Project' }}</title>

	@push('styles')
	<link rel="stylesheet" href="{{ asset('dist/style.css') }}">
	<link rel="stylesheet" href="{{ asset('dist/custom.css') }}">
	@endpush


    <!-- jQuery -->
    <script src="{{ asset('src/jquery-3.7.1.min.js') }}"></script>

    <!-- Bagisto Required -->
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="currency" content="{{ core()->getCurrentCurrency()->toJson() }}">

    <link rel="icon" sizes="16x16"
          href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}" />

    @stack('meta')

    <!-- Keep Vite for Bagisto JS + Vue -->
    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])

    @stack('styles')

    {!! view_render_event('bagisto.shop.layout.head.after') !!}
</head>

<body>

    {!! view_render_event('bagisto.shop.layout.body.before') !!}

    <div id="app">

        <!-- Flash Msg -->
        <x-shop::flash-group />

        <!-- Confirm Modal -->
        <x-shop::modal.confirm />

        <!-- HEADER -->
        @if ($hasHeader)
            @include('shop::custom.header')
        @endif

        <!-- Cookie -->
        @if(
            core()->getConfigData('general.gdpr.settings.enabled')
            && core()->getConfigData('general.gdpr.cookie.enabled')
        )
            <x-shop::layouts.cookie />
        @endif

        {!! view_render_event('bagisto.shop.layout.content.before') !!}

        <!-- Your HTML Page Content -->
        @include('shop::custom.homepage')

        <!-- Bagisto Dynamic Slot -->
        <main id="main">
            {{ $slot }}
        </main>

        {!! view_render_event('bagisto.shop.layout.content.after') !!}

        @if ($hasFeature)
            <x-shop::layouts.services />
        @endif

        @if ($hasFooter)
            <x-shop::layouts.footer />
        @endif

    </div>

    {!! view_render_event('bagisto.shop.layout.body.after') !!}

    @stack('scripts')

    <!-- Vue Mount -->
    <script>
        window.addEventListener("load", function () {
            app.mount("#app");
        });
    </script>

</body>
</html>
