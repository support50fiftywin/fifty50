@extends('adminlte::page')
@section('content')
<h2>{{ $merchant->business_name }}</h2>
<p>Welcome to {{ $merchant->name }}’s sweepstakes entry page.</p>

@if($merchant->qr_code)
    <img src="{{ asset('storage/qr/'.$merchant->qr_code) }}" width="250">
@endif
@endsection
