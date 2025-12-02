<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchantAuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Admin\SweepstakeController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\AdminMerchantController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Merchant\QrController;
use App\Http\Controllers\Merchant\LandingPreviewController;
use App\Http\Controllers\Merchant\EarningsController;

	Route::middleware(['auth', 'role:Merchant'])->prefix('merchant')->name('merchant.')->group(function () {

		// Landing Page Preview
		Route::get('/landing-preview', [LandingPreviewController::class, 'index'])
			->name('landing.preview');

		// Earnings & Referrals
		Route::get('/earnings', [EarningsController::class, 'index'])
			->name('earnings');
	});

	Route::middleware(['auth', 'role:Merchant'])->prefix('merchant')->name('merchant.')->group(function () {
		Route::get('/qr', [QrController::class, 'index'])->name('qr');
	});

	Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
		Route::get('/merchants', [AdminMerchantController::class, 'index'])->name('merchants.index');
	});
	Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
		Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
	});

	Route::get('testqr', function () {
		$landing_slug = "test-merchant";
		$url = url('/m/' . $landing_slug);

		$qr = 'qr_5.svg';

		// Create directory if not exist
		if (!is_dir(storage_path('app/public/qr'))) {
			mkdir(storage_path('app/public/qr'), 0777, true);
		}

		// Generate SVG QR code and save it
		$svg = QrCode::format('svg')
			->size(300)
			->generate($url);

		file_put_contents(storage_path('app/public/qr/' . $qr), $svg);

		return "QR created successfully!";
	});


	
	Route::get('/', function () {
		return view('welcome');
	});

	Auth::routes();

	// Auto redirect to correct dashboard
	Route::get('/dashboard', function () {
		if (auth()->user()->hasRole('Admin')) {
			return redirect()->route('admin.dashboard');
		} elseif (auth()->user()->hasRole('Merchant')) {
			return redirect()->route('merchant.dashboard');
		} else {
			return redirect()->route('user.dashboard');
		}
	})->middleware('auth')->name('dashboard');

	// Admin
	Route::middleware(['auth', 'role:Admin'])
		->get('/admin-dashboard', [AdminController::class, 'dashboard'])
		->name('admin.dashboard');

	// Merchant
	Route::middleware(['auth', 'role:Merchant'])
		->get('/merchant-dashboard', [MerchantController::class, 'dashboard'])
		->name('merchant.dashboard');

	// User
	Route::middleware(['auth', 'role:User'])
		->get('/user-dashboard', [UserController::class, 'dashboard'])
		->name('user.dashboard');
		
	Route::get('/merchant-register', function() {
		return view('auth.merchant-register');
	})->name('merchant.register');

	Route::post('/merchant-register', [MerchantAuthController::class, 'register'])->name('merchant.register.submit');

	// Route::get('/m/{slug}', function ($slug) {
		// $merchant = \App\Models\User::where('landing_slug', $slug)->firstOrFail();
		// return view('merchant.landing', compact('merchant'));
	// });
	// routes/web.php
	Route::get('/m/{slug}', [MerchantAuthController::class, 'landing'])->name('merchant.landing');

	Route::patch('/merchant-approve/{id}', function ($id) {
		$user = \App\Models\User::findOrFail($id);
		$user->status = 'approved';
		$user->save();
		return back()->with('success', 'Merchant Approved!');
	})->middleware(['auth','role:Admin'])->name('merchant.approve');

	Route::middleware(['auth','role:Merchant'])
		->get('/merchant-dashboard', [MerchantDashboardController::class, 'index'])
		->name('merchant.dashboard');

	Route::middleware(['auth','role:Admin'])->group(function () {
		Route::get('/admin/sweepstakes', [SweepstakeController::class, 'index'])->name('admin.sweepstakes.index');
		Route::get('/admin/sweepstakes/create', [SweepstakeController::class, 'create'])->name('admin.sweepstakes.create');
		Route::post('/admin/sweepstakes/store', [SweepstakeController::class, 'store'])->name('admin.sweepstakes.store');
		Route::get('/admin/sweepstakes/{id}/edit', [SweepstakesController::class, 'edit'])->name('admin.sweepstakes.edit');
		Route::post('/admin/sweepstakes/{id}/update', [SweepstakesController::class, 'update'])->name('admin.sweepstakes.update');
		Route::get('/admin/sweepstakes/{id}/close', [SweepstakesController::class, 'close'])->name('admin.sweepstakes.close');
	});

	Route::middleware(['auth', 'role:User'])->get('/checkout/{merchant}', [StripeController::class, 'checkout'])->name('stripe.checkout');
	Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
	
	Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('packages', PackageController::class);
	});
	
	Route::middleware(['auth'])->get('/checkout/{merchant}/{package}', [CheckoutController::class, 'create'])->name('checkout');
	Route::post('/stripe/webhook', [CheckoutController::class, 'webhook'])->name('stripe.webhook');

