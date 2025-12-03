<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entry;
use App\Models\Sweepstake;

class CloverWebhookController extends Controller
{
    public function handle(Request $request)
	{
		\Log::info('Clover Webhook:', $request->all());

		$merchantId = $request->merchant_id;
		$extraAmount = $request->donation_amount;

		$merchant = User::find($merchantId);
		if (!$merchant) return response()->json(['error' => 'Merchant not found'], 404);

		$sweepstakeId = $this->activeSweepstakeId();
		if (!$sweepstakeId) {
			return response()->json(['error' => 'No active sweepstake'], 400);
		}

		$entriesCount = intval($extraAmount * 10);

		Entry::create([
			'user_id' => null,
			'merchant_id' => $merchant->id,
			'sweepstakes_id' => $sweepstakeId,
			'entries' => $entriesCount,
			'source' => 'clover',
			'confirmed' => 1,
		]);

		return response()->json(['success' => true, 'entries_added' => $entriesCount]);
	}

	private function activeSweepstakeId()
	{
		return Sweepstake::where('status', 'active')->value('id');
	}

	
}
