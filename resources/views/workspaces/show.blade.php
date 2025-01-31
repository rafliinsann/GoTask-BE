@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Workspace: {{ $workspace->board }}</h2>

    <a href="{{ route('board.create', ['workspace_id' => $workspace->id]) }}" class="btn btn-primary">Tambah Board</a>

    <h3 class="mt-4">Boards</h3>
    <div class="row">
        @foreach ($workspace->boards as $board)
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $board->nama }}</h5>
                    <a href="{{ route('board.show', $board->id) }}" class="btn btn-info">Lihat Board</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
