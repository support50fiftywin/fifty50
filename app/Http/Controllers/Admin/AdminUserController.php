<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::role('User')->get();
        return view('admin.users.index', compact('users'));
    }
}
