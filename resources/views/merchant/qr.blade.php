@extends('adminlte::page')

@section('title', 'My QR Code')

@section('content_header')
    <h1>My QR Code</h1>
@stop

@section('content')
<div class="text-center p-4">
    <p><strong>Your Landing Page QR Code</strong></p>

    @if($merchant->qr_code)
        <img src="{{ asset('storage/qr/' . $merchant->qr_code) }}" width="260">
        <br><br>
        <a href="{{ asset('storage/qr/' . $merchant->qr_code) }}" download class="btn btn-primary">
            Download QR Code
        </a>
        <br><br>
        <p>Landing URL:</p>
        <code>{{ url('/m/' . $merchant->landing_slug) }}</code>
    @else
        <p class="text-danger">QR not generated yet</p>
    @endif
</div>
@stop
