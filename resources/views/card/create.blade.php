@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Card</h2>
    <form action="{{ route('card.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="label" class="form-label">Label</label>
            <select name="label" class="form-control">
                <option value="Urgent">Urgent</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="deadline" class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control">
        </div>
        <input type="hidden" name="list_id" value="{{ request()->list_id }}">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
