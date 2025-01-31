@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Workspace</h2>

    <form action="{{ route('workspace.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="board" class="form-label">Nama Board</label>
            <input type="text" class="form-control" id="board" name="board" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
