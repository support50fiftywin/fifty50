<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CloverWebhookController;

Route::post('/clover/webhook', [CloverWebhookController::class, 'handle']);
