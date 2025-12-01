<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;

class UserController extends Controller
{
    public function dashboard()
    {
        $entries = Entry::where('user_id', auth()->id())
                        ->with('sweepstake')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('user.dashboard', compact('entries'));
    }
}
