<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QrController extends Controller
{
    public function index()
    {
        $merchant = Auth::user();
        return view('merchant.qr', compact('merchant'));
    }
}
