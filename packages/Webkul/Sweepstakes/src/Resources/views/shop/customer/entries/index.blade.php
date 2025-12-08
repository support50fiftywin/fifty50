<x-shop::layouts.account>

    <x-slot:title>
        My Entries
    </x-slot>

    <div class="max-w-5xl mx-auto">

        <!-- Wallet Summary -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-2">Total Entries</h2>
            <p class="text-3xl font-extrabold text-pink-600">
                {{ number_format($wallet->balance) }}
            </p>
        </div>

        <!-- Entry History -->
        <div class="bg-white shadow rounded-lg p-6">

            <h3 class="text-lg font-semibold mb-4">
                Entry History
            </h3>

            @forelse ($transactions as $tx)
                <div class="border-b py-4">

                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-green-600">
                                +{{ $tx->amount }} Entries
                            </p>

                            <p class="text-sm text-gray-700">
                                {{ $tx->meta['product_name'] ?? 'Product' }}
                                × {{ $tx->meta['qty'] ?? 1 }}
                            </p>

                            <p class="text-xs text-gray-500">
                                Order #{{ $tx->meta['order_id'] ?? '-' }}
                            </p>

                            <p class="text-xs text-gray-400">
                                {{ $tx->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-gray-500">
                                Balance
                            </p>
                            <p class="font-bold">
                                {{ number_format($tx->balance_after) }}
                            </p>
                        </div>
                    </div>

                </div>
            @empty
                <p class="text-gray-500 text-center">
                    No entries found yet.
                </p>
            @endforelse

            <!-- Pagination -->
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>

</x-shop::layouts.account>
