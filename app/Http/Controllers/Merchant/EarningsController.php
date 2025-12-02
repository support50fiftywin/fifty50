<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Entry;
use App\Models\Subscription;

class EarningsController extends Controller
{
    public function index()
    {
        $merchant = Auth::user();

        $totalRevenue = Entry::where('merchant_id', $merchant->id)->sum('amount');
        $totalEntries = Entry::where('merchant_id', $merchant->id)->sum('entries');
        $referrals = Entry::where('merchant_id', $merchant->id)->distinct('user_id')->count('user_id');

        return view('merchant.earnings', compact(
            'totalRevenue',
            'totalEntries',
            'referrals'
        ));
    }
}
