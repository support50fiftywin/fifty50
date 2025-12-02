@extends('adminlte::page')

@section('title', 'Edit Sweepstakes')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Sweepstakes</h3>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.sweepstakes.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Title (Internal)</label>
                <input type="text" name="title" class="form-control" value="{{ $item->title }}" required>
            </div>

            <div class="form-group">
                <label>Prize Title (Public Title)</label>
                <input type="text" name="prize_title" class="form-control" value="{{ $item->prize_title }}" required>
            </div>

            <div class="form-group">
                <label>Prize Image</label><br>
                <img src="{{ asset('storage/'.$item->prize_image) }}" width="140" class="mb-2" style="border-radius: 5px;">
                <input type="file" name="prize_image" class="form-control-file">
                <small>(Upload only if changing the image)</small>
            </div>

            <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ $item->start_date }}" required>
            </div>

            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ $item->end_date }}" required>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="scheduled" {{ $item->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="closed" {{ $item->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <button class="btn btn-primary mt-2">Update Sweepstakes</button>
        </form>
    </div>
</div>

@endsection
