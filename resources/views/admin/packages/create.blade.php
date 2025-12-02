@extends('adminlte::page')

@section('title', 'Add Package')

@section('content_header')
    <h1>Create Package</h1>
@endsection

@section('content')
<form action="{{ route('admin.packages.store') }}" method="post">
    @csrf

    <div class="form-group">
        <label>Package Name</label>
        <input type="text" name="name" class="form-control" required placeholder="Bronze / Silver / Gold / Diamond">
    </div>

    <div class="form-group">
        <label>Price ($)</label>
        <input type="number" name="price" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Entries</label>
        <input type="number" name="entries" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Stripe Price ID</label>
        <input type="text" name="stripe_price_id" class="form-control" required placeholder="price_xxx">
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection
