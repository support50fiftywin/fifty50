@extends('adminlte::page')

@section('title', 'Packages')

@section('content_header')
    <h1>Subscription Packages</h1>
@endsection

@section('content')

<a href="{{ route('admin.packages.create') }}" class="btn btn-primary mb-3">Add Package</a>

<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Price</th>
        <th>Entries</th>
        <th>Stripe Price ID</th>
        <th>Action</th>
    </tr>
    @foreach($packages as $package)
    <tr>
        <td>{{ $package->name }}</td>
        <td>${{ $package->price }}</td>
        <td>{{ $package->entries }}</td>
        <td>{{ $package->stripe_price_id }}</td>
        <td>
            <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('admin.packages.destroy', $package->id) }}" method="post" style="display:inline;">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete package?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

@endsection
