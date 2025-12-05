<?php

namespace Webkul\Sweepstakes\Listeners;

use Webkul\Sales\Models\Order;
use Webkul\Customer\Models\Customer;

class AddEntriesOnOrder
{
    public function handle($event)
    {
        /** @var Order $order */
        $order = $event->order;

        if (! $order->customer) {
            return;
        }

        $customer = $order->customer;

        $totalEntries = 0;

        foreach ($order->items as $item) {

            $product = $item->product;

            if (! $product || ! $product->entries) {
                continue;
            }

            $entries = $product->entries * $item->qty_ordered;

            $totalEntries += $entries;
        }

        if ($totalEntries > 0) {
            $customer->deposit($totalEntries); // Adds entries to wallet
        }
    }
}
