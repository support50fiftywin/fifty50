<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'stripe_subscription_id', 'package_name', 'amount', 'entries_awarded', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
