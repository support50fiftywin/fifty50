<?php

namespace Webkul\Sweepstakes\Http\Controllers\Admin;

use Illuminate\Routing\Controller;

class EntryController extends Controller
{
    /**
     * Display a listing of all entries (Entry Viewer).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // For now, this just returns a blank view to confirm the route works.
        // You will later replace this with a DataGrid view.
        return view('sweepstakes::admin.entries.index');
    }
}