<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MerchantAuthController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\Admin\SweepstakeController;

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

	Route::get('/m/{slug}', function ($slug) {
		$merchant = \App\Models\User::where('landing_slug', $slug)->firstOrFail();
		return view('merchant.landing', compact('merchant'));
	});

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
	});

