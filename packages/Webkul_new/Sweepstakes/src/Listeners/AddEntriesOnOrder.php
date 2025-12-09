<?php

namespace Webkul\Sweepstakes\Listeners;

use Webkul\Sales\Models\Order;
use Webkul\Customer\Models\Customer;

class AddEntriesOnOrder
{
    /**
     * Handle checkout.order.save.after event
     */
    public function handle(Order $order): void
    {
		
        // ✅ Only customer orders
        if (! $order->customer_id) {
            return;
        }

        /** @var Customer $customer */
        $customer = Customer::find($order->customer_id);

        if (! $customer) {
            return;
        }
//dd('hello');
        // ✅ Get (or auto-create) wallet
        //$wallet = $customer->getWallet('default');
//dd($wallet);
        foreach ($order->items as $item) {

            $product = $item->product;

            if (! $product) {
                continue;
            }

            // ✅ Entries set by admin on product
            $entriesPerUnit = (int) ($product->entries ?? 0);

            if ($entriesPerUnit <= 0) {
                continue;
            }

            $totalEntries = $entriesPerUnit * (int) $item->qty_ordered;

            // ✅ Deposit entries
            $wallet->deposit(
                $totalEntries,
                [
                    'type'         => 'order_entries',
                    'order_id'     => $order->id,
                    'order_item'   => $item->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'qty'          => $item->qty_ordered,
                    'entries_each' => $entriesPerUnit,
                    'source'       => 'purchase',
                ]
            );
        }
    }
}
