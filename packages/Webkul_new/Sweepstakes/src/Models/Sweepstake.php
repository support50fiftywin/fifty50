<?php

namespace Webkul\Sweepstakes\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sweepstake extends Model
{
    use HasFactory;

    protected $table = 'sweepstakes';

    protected $fillable = [
        'title',
        'prize_title',
        'image',
        'start_date',
        'end_date',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Get all entries associated with the sweepstake.
     */
    public function entries()
    {
        return $this->hasMany(SweepstakeEntry::class);
    }
}