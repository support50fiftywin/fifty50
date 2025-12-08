<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="orders" />
        @endSection
    @endif

    <div class="mx-4">
        <x-shop::layouts.account.navigation />
    </div>
	<div class="card mb-6">
		<div class="card-header">
			<h3 class="font-semibold text-lg">My Sweepstakes Entries</h3>
		</div>

		<div class="card-body">
			<p class="text-3xl font-bold text-pink-600">
				{{ auth()->user()->balance }}
			</p>
			<p class="text-sm text-gray-500 mt-1">
				Total Active Entries
			</p>
		</div>
	</div>
    <span class="mb-5 mt-2 w-full border-t border-zinc-300"></span>

    <!--Customers logout-->
    @auth('customer')
        <div class="mx-4">
            <div class="mx-auto w-[400px] rounded-lg border border-navyBlue py-2.5 text-center max-sm:w-full max-sm:py-1.5">
                <x-shop::form
                    method="DELETE"
                    action="{{ route('shop.customer.session.destroy') }}"
                    id="customerLogout"
                />

                <a
                    class="flex items-center justify-center gap-1.5 text-base hover:bg-gray-100"
                    href="{{ route('shop.customer.session.destroy') }}"
                    onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                >
                    @lang('shop::app.components.layouts.header.desktop.bottom.logout')
                </a>
            </div>
        </div>
    @endauth

</x-shop::layouts.accounts>