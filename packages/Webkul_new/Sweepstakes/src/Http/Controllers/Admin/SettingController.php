<?php

namespace Webkul\Sweepstakes\Http\Controllers\Admin;

use Webkul\Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('sweepstakes::admin.settings');
    }

    public function save(Request $request)
    {
        // You can store settings in DB later

        session()->flash('success', 'Settings saved!');
        return back();
    }
}
