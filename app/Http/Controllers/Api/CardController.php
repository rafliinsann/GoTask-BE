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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'board_id' => 'required|exists:boards,id',
        ]);

        $user = Auth::user();
        $board = Board::findOrFail($request->board_id);

        // Hanya member, owner, atau superadmin yang bisa membuat card
        $members = json_decode($board->member, true) ?? [];
        if (!in_array($user->id, $members) && $board->user_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $card = Card::create([
            'title' => $request->title,
            'description' => $request->description,
            'label' => $request->label,
            'deadline' => $request->deadline,
            'board_id' => $request->board_id,
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
        $user = Auth::user();

        // Cek role
        $members = json_decode($board->member, true) ?? [];
        if (!in_array($user->id, $members) && $board->user_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $card->update([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return response()->json([
            'message' => 'Card berhasil diperbarui!',
            'card' => $card
        ], 200);
    }

    // Delete a card
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $board = $card->board;
        $user = Auth::user();

        // Hanya owner board atau superadmin yang bisa hapus card
        if ($board->user_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $card->delete();
        return response()->json(['message' => 'Card berhasil dihapus!'], 200);
    }
}

