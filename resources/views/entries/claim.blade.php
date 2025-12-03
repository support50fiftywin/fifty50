@extends('adminlte::page')
@section('title', 'Claim Entries')

@section('content')
<h2>Claim Your Sweepstakes Entries 🎉</h2>
<form method="POST" action="{{ route('claim.entries.submit') }}">
    @csrf
    <label>Email (required to verify entries):</label>
    <input type="email" name="email" class="form-control" required>
    <button class="btn btn-primary mt-3">Claim Entries</button>
</form>
@endsection
