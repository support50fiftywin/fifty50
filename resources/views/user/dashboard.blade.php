@extends('layouts.master')

@section('title', 'My Entries')
@section('header', 'User — Your Sweepstakes Entries')

@section('page-content')
<table class="table table-striped">
    <thead>
        <tr>
            <th>Sweepstakes</th>
            <th>Entries</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($entries as $entry)
        <tr>
            <td>{{ $entry->sweepstake->title }}</td>
            <td>{{ $entry->count }}</td>
            <td>{{ $entry->confirmed ? 'Confirmed' : 'Pending' }}</td>
            <td>{{ $entry->created_at->format('M d, Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
