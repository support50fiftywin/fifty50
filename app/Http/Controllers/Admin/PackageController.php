<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required',
            'price'           => 'required|numeric',
            'entries'         => 'required|numeric',
            'stripe_price_id' => 'required',
        ]);

        Package::create($request->all());
        return redirect()->route('admin.packages.index')->with('success', 'Package added successfully');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name'            => 'required',
            'price'           => 'required|numeric',
            'entries'         => 'required|numeric',
            'stripe_price_id' => 'required',
        ]);

        $package->update($request->all());
        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted');
    }
}
