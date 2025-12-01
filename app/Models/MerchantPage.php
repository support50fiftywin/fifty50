<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantPage extends Model
{
    protected $fillable = [
        'merchant_id', 'slug', 'qr_code_url', 'referral_code', 'landing_page_views'
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}
