@extends('layouts.master')

@section('title', '50Fifty Sweepstakes')
@section('header', 'Win Huge Prizes')

@section('page-content')
<div class="container">
    <div class="text-center py-5">
        <h2>Win Dream Cars & Cash — Every Week</h2>
        <p>Earn entries through local merchants, subscriptions or merch purchases.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg mt-3">
            Get Started
        </a>
    </div>
</div>
@endsection
