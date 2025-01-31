@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Workspace</h2>
    <a href="{{ route('workspace.create') }}" class="btn btn-primary">Tambah Workspace</a>

    <table class="table mt-3">
        <tr>
            <th>Nama Board</th>
            <th>Aksi</th>
        </tr>
        @foreach ($workspaces as $workspace)
        <tr>
            <td>{{ $workspace->board }}</td>
            <td>
                <a href="{{ route('workspace.show', $workspace->id) }}" class="btn btn-info">Detail</a>
                <a href="{{ route('workspace.edit', $workspace->id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('workspace.destroy', $workspace->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
