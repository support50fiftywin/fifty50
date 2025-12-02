@extends('adminlte::page')

@section('title', 'Edit Package')

@section('content_header')
    <h1>Edit Package</h1>
@endsection

@section('content')
<form action="{{ route('admin.packages.update', $package->id) }}" method="post">
    @csrf @method('PUT')

    <div class="form-group">
        <label>Package Name</label>
        <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
    </div>

    <div class="form-group">
        <label>Price ($)</label>
        <input type="number" name="price" class="form-control" value="{{ $package->price }}" required>
    </div>

    <div class="form-group">
        <label>Entries</label>
        <input type="number" name="entries" class="form-control" value="{{ $package->entries }}" required>
    </div>

    <div class="form-group">
        <label>Stripe Price ID</label>
        <input type="text" name="stripe_price_id" class="form-control" value="{{ $package->stripe_price_id }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
