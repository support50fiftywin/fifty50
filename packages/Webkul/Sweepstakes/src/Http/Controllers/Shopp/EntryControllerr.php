<?php

namespace Webkul\Sweepstakes\Http\Controllers\Shopp;

use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Customer\Models\Customer;

class EntryControllerr extends Controller
{
    public function index()
    {
        $customer = auth()->guard('customer')->user();

        if (! $customer) {
            abort(403);
        }

        $wallet = $customer->getWallet('default');

        $transactions = $wallet->transactions()
            ->latest()
            ->paginate(10);

        return view('sweepstakes::shop.customer.entries.index', [
            'wallet'       => $wallet,
            'transactions' => $transactions,
        ]);
    }
}
