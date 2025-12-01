<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = [
        'user_id', 'sweepstakes_id', 'merchant_id', 'entry_source', 'payment_reference', 'confirmed'
    ];

    public function sweepstake()
    {
        return $this->belongsTo(Sweepstake::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }
}
