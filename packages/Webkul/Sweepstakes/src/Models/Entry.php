<?php

namespace Webkul\Sweepstakes\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $table = 'sweepstake_entries';

    protected $fillable = [
        'sweepstake_id',
        'customer_id',
        'merchant_id',
        'entries',
        'source',
        'payment_reference',
        'confirmed'
    ];
}
