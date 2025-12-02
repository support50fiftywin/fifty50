<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminMerchantController extends Controller
{
    public function index()
    {
        $merchants = User::role('Merchant')->get();
        return view('admin.merchants.index', compact('merchants'));
    }
}
