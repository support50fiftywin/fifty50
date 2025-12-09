<?php

namespace Webkul\Sweepstakes\Models;

use Bavix\Wallet\Models\Wallet;
use Webkul\Customer\Models\Customer;

class CustomerWallet extends Wallet
{
    protected $table = 'customer_wallets';

    public function holder()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
