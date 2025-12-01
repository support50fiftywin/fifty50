<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;

class MerchantDashboardController extends Controller
{
    public function index()
    {
        $merchant = auth()->user();

        $totalEntries = Entry::where('merchant_id', $merchant->id)->count();
		// dd($totalEntries);
		$confirmedEntries = Entry::where('merchant_id', $merchant->id)
			->where('confirmed', 1)
			->count();

		$pendingEntries = $totalEntries - $confirmedEntries;
		
		
        return view('merchant.dashboard', compact('totalEntries', 'confirmedEntries', 'pendingEntries'));
    }
}
