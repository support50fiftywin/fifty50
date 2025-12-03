<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;

class ClaimEntryController extends Controller
{
    public function showForm()
    {
        return view('entries.claim');
    }

    public function claim(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = auth()->user();

        // Attach unclaimed POS entries
        Entry::where('user_id', null)
            ->where('merchant_id', session('merchant_id'))
            ->update(['user_id' => $user->id]);

        return redirect()->route('user.dashboard')->with('success', 'Entries Claimed Successfully');
    }
}
