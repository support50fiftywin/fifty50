<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Sweepstakes\Http\Controllers\SweepstakesController;
use Webkul\Sweepstakes\Http\Controllers\Admin\EntryController;
use Webkul\Sweepstakes\Http\Controllers\Shopp\EntryControllerr;
use Webkul\Customer\Models\Customer;
// Route::group(['middleware' => ['web'], 'prefix' => 'sweepstakes'], function () {
    // Route::get('/', [SweepstakesController::class, 'index'])->name('sweepstakes.index');
// });
Route::get('/test-sweep', function() {
    $customer = auth()->guard('customer')->user();
    $customer->getWallet('default');
    dd($customer);
});
Route::group([
    'middleware' => ['web', 'customer'],
], function () {

    Route::get('/customer/entries', [EntryControllerr::class, 'index'])
        ->name('shop.customer.entries.index');
});

Route::get('/wallet-test', function () {

    $customer = Customer::find(4);

    // Deposit
    $customer->wallet->deposit(50);

    return [
        'wallet_balance' => $customer->wallet->balanceInt, 
        'raw' => $customer->wallet,
    ];
});
Route::get('/wallet-entries', function () {
    $customer = auth()->guard('customer')->user();
	$customer->getWallet('default');
	dd($customer);
    return view('shop::customers.entries', [
        'wallet' => $customer->getWallet('default'),
    ]);
})->middleware('customer')->name('shop.customer.entries');
Route::get('/sweepstakes', function () {
        return view('sweepstakes::index');
    })->name('admin.sweepstakes.index');
Route::group([
    'middleware' => ['admin'],
    'prefix'     => config('app.admin_url', 'admin'),
], function () {
    // --- Sweepstakes Routes (Existing) ---
    // Route::get('sweepstakes', [SweepstakesController::class, 'index'])->name('admin.sweepstakes.index');
    // Route::get('sweepstakes/create', [SweepstakesController::class, 'create'])->name('admin.sweepstakes.create');
    // Route::post('sweepstakes/store', [SweepstakesController::class, 'store'])->name('admin.sweepstakes.store');

    // --- Entries Routes (NEW) ---
    Route::prefix('sweepstakes/entries')->group(function () {
        Route::get('/', [EntryController::class, 'index'])->name('admin.sweepstakes.entries.index');
        
        // You can add more entry-related routes here (e.g., export, delete)
        // Route::post('export', [EntryController::class, 'export'])->name('admin.sweepstakes.entries.export');
    });
	
	Route::prefix('sweepstakes/settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('admin.sweepstakes.settings.index');
    });
	
	




});
