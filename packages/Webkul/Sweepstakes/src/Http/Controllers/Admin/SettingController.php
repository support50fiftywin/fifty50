<?php

namespace Webkul\Sweepstakes\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    /**
     * Display the sweepstakes settings page (e.g., prize scheduler config).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // This will later display your configuration form for the prize scheduler.
        return view('sweepstakes::admin.settings.index');
    }
}