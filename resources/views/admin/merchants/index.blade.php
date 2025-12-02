@extends('adminlte::page')

@section('title', 'Merchants')

@section('content_header')
    <h1>Merchants</h1>
@stop

@section('content')
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Business</th>
        <th>Status</th>
    </tr>
    @foreach ($merchants as $merchant)
    <tr>
        <td>{{ $merchant->name }}</td>
        <td>{{ $merchant->email }}</td>
        <td>{{ $merchant->business_name }}</td>
        <td>{{ $merchant->status }}</td>
    </tr>
    @endforeach
</table>
@stop
