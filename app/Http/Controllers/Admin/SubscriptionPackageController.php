<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use App\Models\SubscriptionPackage;

class SubscriptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'entries' => 'required|integer',
    ]);

    Stripe::setApiKey(env('STRIPE_SECRET'));

    // Create Stripe product
    $product = Product::create([
        'name' => $request->name . ' Package',
    ]);

    // Create Stripe price
    $price = Price::create([
        'unit_amount' => $request->price * 100,
        'currency' => 'usd',
        'product' => $product->id,
    ]);

    SubscriptionPackage::create([
        'name' => $request->name,
        'price' => $request->price,
        'entries' => $request->entries,
        'stripe_price_id' => $price->id,
    ]);

    return back()->with('success', 'Package created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
