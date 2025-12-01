@extends('layouts.master')

@section('title', 'Admin Dashboard')
@section('header', 'Admin — Sweepstakes Management')

@section('page-content')
<div class="row">
    <div class="col-md-4">
        <x-adminlte-small-box title="Active Sweepstakes" text="12" icon="fas fa-gift" theme="primary" />
    </div>
    <div class="col-md-4">
        <x-adminlte-small-box title="Total Merchants" text="148" icon="fas fa-store" theme="info" />
    </div>
    <div class="col-md-4">
        <x-adminlte-small-box title="Entries Today" text="5,342" icon="fas fa-ticket-alt" theme="success" />
    </div>
</div>

@php
$pending = \App\Models\User::role('Merchant')->where('status', 'pending')->get();
@endphp

<h3>Pending Merchant Approvals</h3>
<table class="table table-bordered">
    <tr><th>Name</th><th>Email</th><th>Business</th><th>Action</th></tr>
@foreach($pending as $merchant)
<tr>
    <td>{{ $merchant->name }}</td>
    <td>{{ $merchant->email }}</td>
    <td>{{ $merchant->business_name }}</td>
    <td>
        <form action="{{ route('merchant.approve', $merchant->id) }}" method="POST">
            @csrf @method('PATCH')
            <button class="btn btn-success btn-sm">Approve</button>
        </form>
    </td>
</tr>
@endforeach
</table>

@endsection
