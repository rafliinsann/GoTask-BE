<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Card;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    // Get all cards for a specific board
    public function index($board_id)
    {
        $board = Board::findOrFail($board_id);
        $workspace = $board->workspace;
        $user = Auth::user();

        // Cek apakah user memiliki akses ke workspace
        if (!$this->hasAccessToWorkspace($workspace, $user->id)) {
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
            'deskripsi' => 'nullable|string',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'colour' => 'nullable|string',
            'assign' => 'nullable|array',
            'assign.*' => 'exists:users,username'
        ]);

        $user = Auth::user();
        $board = Board::findOrFail($board_id);
        $workspace = $board->workspace;

        // Cek apakah user memiliki akses ke workspace
        if (!$this->hasAccessToWorkspace($workspace, $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Handle file upload
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $coverPath = $file->store('uploads/covers', 'public');
        }

        // Hanya bisa assign ke member workspace
        $allowedUsers = $workspace->members->pluck('username')->toArray();
        $assignUsers = [];

        if ($request->assign) {
            $assignUsers = array_intersect($request->assign, $allowedUsers);
        }

        $card = Card::create([
            'title' => $request->title,
            'cover' => $coverPath,
            'deskripsi' => $request->deskripsi,
            'label' => $request->label,
            'deadline' => $request->deadline,
            'colour' => $request->colour,
            'assign' => json_encode($assignUsers),
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
        $workspace = $board->workspace;
        $user = Auth::user();

        // Cek apakah user memiliki akses ke workspace
        if (!$this->hasAccessToWorkspace($workspace, $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'cover' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'sometimes|string',
            'label' => 'sometimes|string',
            'deadline' => 'sometimes|date',
            'colour' => 'sometimes|string',
            'assign' => 'sometimes|array',
            'assign.*' => 'exists:users,username'
        ]);

        // Update cover jika ada perubahan
        if ($request->hasFile('cover')) {
            if ($card->cover && Storage::exists('public/' . $card->cover)) {
                Storage::delete('public/' . $card->cover);
            }

            $file = $request->file('cover');
            $coverPath = $file->store('uploads/covers', 'public');
            $card->cover = $coverPath;
        }

        $card->update($request->only(['title', 'deskripsi', 'label', 'deadline', 'colour']));

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
        $newBoard = Board::findOrFail($request->new_board_id);
        $workspace = $newBoard->workspace;
        $user = Auth::user();

        // Cek apakah user memiliki akses ke workspace
        if (!$this->hasAccessToWorkspace($workspace, $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Pindahkan card ke board baru
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
        $workspace = $board->workspace;
        $user = Auth::user();

        // Hanya owner workspace yang bisa menghapus card
        if ($workspace->owner_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $card->delete();
        return response()->json(['message' => 'Card berhasil dihapus!'], 200);
    }

    // Helper function: Cek akses ke workspace
    private function hasAccessToWorkspace($workspace, $userId)
    {
        return $workspace->owner_id === $userId || $workspace->members->contains($userId);
    }
}
