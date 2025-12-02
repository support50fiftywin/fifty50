@extends('adminlte::page')

@section('title', 'Landing Page Preview')

@section('content_header')
    <h1>Landing Page Preview</h1>
@stop

@section('content')
<div class="text-center">
    <p>Your public landing page URL:</p>
    <code>{{ $landingUrl }}</code>

    <br><br>

    <a href="{{ $landingUrl }}" target="_blank" class="btn btn-primary">
        Open Landing Page
    </a>
</div>
@stop
