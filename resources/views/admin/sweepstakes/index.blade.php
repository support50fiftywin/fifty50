@extends('adminlte::page')

@section('title', 'Sweepstakes')

@section('content')
<a href="{{ route('admin.sweepstakes.create') }}" class="btn btn-primary mb-3">Add New Sweepstakes</a>

<table class="table table-bordered">
    <tr>
        <th>Image</th>
        <th>Title</th>
        <th>Prize</th>
        <th>Status</th>
        <th>Start</th>
        <th>End</th>
    </tr>

    @foreach($sweepstakes as $item)
    <tr>
        <td><img src="{{ asset('storage/'.$item->image) }}" width="80"></td>
        <td>{{ $item->title }}</td>
        <td>{{ $item->prize }}</td>
        <td>{{ $item->status }}</td>
        <td>{{ $item->start_date }}</td>
        <td>{{ $item->end_date }}</td>
    </tr>
    @endforeach
</table>
@endsection
