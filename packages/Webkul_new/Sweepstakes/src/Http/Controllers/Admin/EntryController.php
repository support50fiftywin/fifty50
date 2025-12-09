<?php

namespace Webkul\Sweepstakes\Http\Controllers\Admin;

use Webkul\Sweepstakes\Models\Entry;
use Webkul\Admin\Http\Controllers\Controller;

class EntryController extends Controller
{
    public function index()
    {
        $entries = Entry::latest()->paginate(20);

        return view('sweepstakes::admin.entries.index', compact('entries'));
    }
	
	 public function export()
    {
        // You can add CSV export logic here later
        return back()->with('success', 'Export coming soon!');
    }
}
