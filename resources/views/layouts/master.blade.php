@extends('adminlte::page')

@section('title', $title ?? 'Dashboard')

@section('content_header')
    <h1>{{ $header ?? 'Dashboard' }}</h1>
@stop

@section('content')
    @yield('page-content')
@stop
