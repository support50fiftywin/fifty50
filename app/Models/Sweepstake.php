<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sweepstake extends Model
{
    protected $fillable = [
        'title', 'prize_title', 'prize_image', 'start_date', 'end_date', 'winner_user_id', 'status'
    ];

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }
}
