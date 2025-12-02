<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LandingPreviewController extends Controller
{
    public function index()
    {
        $merchant = Auth::user();
        $landingUrl = url('/m/' . $merchant->landing_slug);

        return view('merchant.landing-preview', compact('merchant', 'landingUrl'));
    }
}
