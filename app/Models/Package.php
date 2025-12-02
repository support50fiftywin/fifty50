<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',            // Bronze, Silver, Gold, Diamond
        'price',           // 10, 25, 50, 100
        'entries',         // 50, 200, 450, 1000
        'stripe_price_id', // price_xxx from Stripe
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
