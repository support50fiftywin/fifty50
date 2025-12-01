<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MerchantAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'business_name' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'business_name' => $request->business_name,
            'landing_slug' => str_replace(' ', '-', strtolower($request->business_name)),
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('Merchant');

        // Generate merchant QR code
        $url = url('/m/' . $user->landing_slug);
        $qr = 'qr_'.$user->id.'.png';
        \QrCode::size(300)->generate($url, storage_path('app/public/qr/'.$qr));

        $user->qr_code = $qr;
        $user->save();

        auth()->login($user);

        return redirect()->route('merchant.dashboard');
    }
	
	public function showRegisterForm()
    {
        return view('auth.merchant-register');
    }
	
	public function landing($slug)
    {
        $merchant = User::where('landing_slug', $slug)->firstOrFail();
        return view('merchant.landing', compact('merchant'));
    }
}
