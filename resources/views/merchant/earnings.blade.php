@extends('adminlte::page')

@section('title', 'Earnings & Referrals')

@section('content_header')
    <h1>Earnings & Referrals</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
                <p>Total Revenue</p>
            </div>
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalEntries }}</h3>
                <p>Total Entries Generated</p>
            </div>
            <div class="icon"><i class="fas fa-ticket-alt"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $referrals }}</h3>
                <p>Total Referrals</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
</div>
@stop
