<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    // Get all cards for a specific board
    public function index($board_id)
    {
        $board = Board::findOrFail($board_id);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah user adalah member, owner, atau superadmin
        $members = json_decode($board->member, true) ?? [];
        if (!in_array($user->id, $members) && $board->user_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cards = Card::where('board_id', $board_id)->get();

        return response()->json([
            'board' => $board,
            'cards' => $cards
        ], 200);
    }

    // Create a new card
    public function store(Request $request, $board_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'colour' => 'nullable|string'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $board = Board::findOrFail($board_id);

        // Cek apakah user adalah member, owner, atau superadmin
        $members = json_decode($board->member, true) ?? [];
        if (!in_array($user->id, $members) && $board->user_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $card = Card::create([
            'title' => $request->title,
            'cover' => $request->cover,
            'description' => $request->description,
            'label' => $request->label,
            'deadline' => $request->deadline,
            'colour' => $request->colour,
            'board_id' => $board_id,
        ]);


        return response()->json([
            'message' => 'Card berhasil dibuat!',
            'card' => $card
        ], 201);
    }

    // Update a card
    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);
        $board = $card->board;
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek role
        $members = json_decode($board->member, true) ?? [];
        if (!in_array($user->id, $members) && $board->user_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'colour' => 'nullable|string'
        ]);



        $card->update([
            'title' => $request->title,
            'cover' => $request->cover,
            'description' => $request->description,
            'label' => $request->label,
            'deadline' => $request->deadline,
            'colour' => $request->colour,
        ]);

        return response()->json([
            'message' => 'Card berhasil diperbarui!',
            'card' => $card
        ], 200);
    }

    // Move a card to another board
public function moveCard(Request $request, $id)
{
    $request->validate([
        'new_board_id' => 'required|exists:boards,id',
    ]);

    $card = Card::findOrFail($id);
    $currentBoard = $card->board;
    $newBoard = Board::findOrFail($request->new_board_id);

    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Cek apakah user punya akses ke board asal dan tujuan
    $currentMembers = json_decode($currentBoard->member, true) ?? [];
    $newMembers = json_decode($newBoard->member, true) ?? [];

    if (
        (!in_array($user->id, $currentMembers) && $currentBoard->user_id !== $user->id && !$user->isSuperAdmin()) ||
        (!in_array($user->id, $newMembers) && $newBoard->user_id !== $user->id && !$user->isSuperAdmin())
    ) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Update board_id pada card
    $card->update([
        'board_id' => $newBoard->id,
    ]);

    return response()->json([
        'message' => 'Card berhasil dipindahkan!',
        'card' => $card
    ], 200);
}


    // Delete a card
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $board = $card->board;
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya owner board atau superadmin yang bisa hapus card
        if ($board->user_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $card->delete();
        return response()->json(['message' => 'Card berhasil dihapus!'], 200);
    }
}
