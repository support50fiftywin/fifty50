<?php

use Illuminate\Support\Facades\Route;
use Webkul\Sweepstakes\Http\Controllers\Admin\SweepstakesController;
use Webkul\Sweepstakes\Http\Controllers\Admin\EntryController;
use Webkul\Sweepstakes\Http\Controllers\Admin\SettingController;

Route::group([
    'middleware' => ['web', 'admin'],
    'prefix'     => config('app.admin_url'),
], function () {

    /**
     * Sweepstakes CRUD
     */
    Route::prefix('sweepstakes')->group(function () {

        Route::get('/', [SweepstakesController::class, 'index'])
            ->name('admin.sweepstakes.index');

        Route::get('/create', [SweepstakesController::class, 'create'])
            ->name('admin.sweepstakes.create');

        Route::post('/store', [SweepstakesController::class, 'store'])
            ->name('admin.sweepstakes.store');

        Route::get('/edit/{id}', [SweepstakesController::class, 'edit'])
            ->name('admin.sweepstakes.edit');

        Route::post('/update/{id}', [SweepstakesController::class, 'update'])
            ->name('admin.sweepstakes.update');

        Route::delete('/delete/{id}', [SweepstakesController::class, 'destroy'])
            ->name('admin.sweepstakes.delete');

        Route::get('/entries/{id}', [SweepstakesController::class, 'entries'])
            ->name('admin.sweepstakes.entries');
    });


    /**
     * Entries Section
     */
    Route::prefix('sweepstakes/entries')->group(function () {

        Route::get('/', [EntryController::class, 'index'])
            ->name('admin.sweepstakes.entries.index');

        Route::post('/export', [EntryController::class, 'export'])
            ->name('admin.sweepstakes.entries.export');
    });


    /**
     * Settings Section
     */
    Route::prefix('sweepstakes/settings')->group(function () {

        Route::get('/', [SettingController::class, 'index'])
            ->name('admin.sweepstakes.settings.index');

        Route::post('/save', [SettingController::class, 'save'])
            ->name('admin.sweepstakes.settings.save');
    });

});
