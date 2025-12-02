<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Sweepstake;

class StripeController extends Controller
{
    public function checkout($merchantId)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = auth()->user();
        $sweepstake = Sweepstake::where('status', 'active')->firstOrFail();

        // Entry package selected by user
        $package = request()->get('package'); // bronze,silver,gold,diamond

        $priceId = match($package) {
            'bronze' => env('STRIPE_PRICE_BRONZE'),
            'silver' => env('STRIPE_PRICE_SILVER'),
            'gold' => env('STRIPE_PRICE_GOLD'),
            'diamond' => env('STRIPE_PRICE_DIAMOND'),
        };

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'customer_email' => $user->email,
            'line_items' => [[ 'price' => $priceId, 'quantity' => 1 ]],
            'success_url' => url('/payment/success'),
            'cancel_url'  => url('/payment/cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'merchant_id' => $merchantId,
                'sweepstake_id' => $sweepstake->id,
                'package' => $package
            ],
        ]);

        return redirect($session->url);
    }
}
