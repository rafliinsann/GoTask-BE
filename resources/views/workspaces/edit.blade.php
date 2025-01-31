@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Workspace</h2>

    <form action="{{ route('workspace.update', $workspace->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="board" class="form-label">Nama Board</label>
            <input type="text" class="form-control" id="board" name="board" value="{{ $workspace->board }}" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>
@endsection
