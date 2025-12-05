<?php

namespace Webkul\Sweepstakes\Traits;

use Webkul\Sweepstakes\Models\CustomerWallet;

trait HasEntries
{
    public function walletAccount()
    {
        return $this->hasOne(CustomerWallet::class, 'customer_id');
    }

    public function addEntries($entries)
    {
        return $this->walletAccount->deposit($entries);
    }
}
