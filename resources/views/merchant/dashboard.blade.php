@extends('adminlte::page')

@section('title', 'Merchant Dashboard')

@section('content_header')
    <h1>Welcome, {{ auth()->user()->business_name }}</h1>
@stop

@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="card bg-info">
            <div class="card-body">
                <h3>Total Entries</h3>
                <h2>{{ $totalEntries ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success">
            <div class="card-body">
                <h3>Confirmed Entries</h3>
                <h2>{{ $confirmedEntries ?? 0 }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning">
            <div class="card-body">
                <h3>Pending Entries</h3>
                <h2>{{ $pendingEntries ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- QR Code Download --}}
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">QR Code</h3>
    </div>
    <div class="card-body">
        @if(auth()->user()->qr_code)
            <img src="{{ asset('storage/qr/' . auth()->user()->qr_code) }}" width="200">
            <br><br>
            <a href="{{ asset('storage/qr/' . auth()->user()->qr_code) }}" download class="btn btn-primary">
                Download QR
            </a>
        @else
            <p>No QR found.</p>
        @endif
    </div>
</div>

@stop
