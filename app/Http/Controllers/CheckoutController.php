<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Package;
use App\Models\Entry;
use App\Models\Sweepstake;

class CheckoutController extends Controller
{
    public function create($merchantId, Package $package)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = auth()->user();
        $sweepstake = Sweepstake::where('status', 'active')->first();

        $session = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => $user->email,
            'line_items' => [[
                'price' => $package->stripe_price_id,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('user.dashboard') . '?success=1',
            'cancel_url' => route('user.dashboard') . '?cancel=1',
            'metadata' => [
                'user_id' => $user->id,
                'merchant_id' => $merchantId,
                'package_id' => $package->id,
                'entries' => $package->entries,
                'sweepstake_id' => $sweepstake ? $sweepstake->id : null,
            ]
        ]);

        return redirect($session->url);
    }
	public function webhook(Request $request)
{
    $payload = $request->all();

    if (($payload['type'] ?? '') === 'checkout.session.completed') {
        $data = $payload['data']['object']['metadata'];

        Entry::create([
            'user_id' => $data['user_id'],
            'merchant_id' => $data['merchant_id'],
            'sweepstake_id' => $data['sweepstake_id'],
            'entries' => $data['entries'],
            'confirmed' => 1,
        ]);
    }

    return response('webhook received', 200);
}
}