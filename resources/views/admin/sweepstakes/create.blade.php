@extends('adminlte::page')

@section('title', 'Create Sweepstakes')

@section('content')
<form action="{{ route('admin.sweepstakes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <x-adminlte-input name="title" label="Sweepstakes Title" required />
    <x-adminlte-input name="prize_title" label="Prize Title" required />
    <x-adminlte-input type="date" name="start_date" label="Start Date" required />
    <x-adminlte-input type="date" name="end_date" label="End Date" required />

    <x-adminlte-input-file name="prize_image" label="Prize Image" />

    <x-adminlte-button theme="dark" type="submit" label="Create Sweepstakes" />
</form>
@endsection

