@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
@stop

@section('content')
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Business</th>
        <th>Status</th>
    </tr>
    @foreach ($users as $merchant)
    <tr>
        <td>{{ $merchant->name }}</td>
        <td>{{ $merchant->email }}</td>
        <td>{{ $merchant->business_name }}</td>
        <td>{{ $merchant->status }}</td>
    </tr>
    @endforeach
</table>
@stop
