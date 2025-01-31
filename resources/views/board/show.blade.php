@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Board: {{ $board->nama }}</h2>

    <div class="row">
        @foreach ($board->lists as $list)
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $list->card }}</h5>

                    <!-- Tambahkan ID untuk sortable -->
                    <ul class="sortable list-group" data-list-id="{{ $list->id }}">
                        @foreach ($list->cards as $card)
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $card->id }}">
                            <div>
                                <strong>{{ $card->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $card->deskripsi }}</small>
                                <br>
                                @if($card->label)
                                    <span class="badge bg-warning">{{ $card->label }}</span>
                                @endif
                                @if($card->deadline)
                                    <span class="badge bg-danger">Deadline: {{ $card->deadline }}</span>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Script Drag & Drop -->
<script>
$(document).ready(function() {
    $(".sortable").sortable({
        connectWith: ".sortable",
        update: function(event, ui) {
            let list_id = $(this).data("list-id");
            let cards = $(this).sortable("toArray", { attribute: "data-id" });

            $.ajax({
                url: "{{ route('card.updatePosition') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    list_id: list_id,
                    cards: cards
                },
                success: function(response) {
                    console.log("Posisi diperbarui", response);
                }
            });
        }
    }).disableSelection();
});
</script>
@endsection
