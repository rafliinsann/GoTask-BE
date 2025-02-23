<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    // Get all cards for a specific board
    public function index($board_id)
    {
        $cards = Card::where('board_id', $board_id)->get();
        return response()->json($cards, 200);
    }

    // Create a new card
    public function store(Request $request, $board_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $board = Board::findOrFail($board_id);

        // Handle file upload
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $coverPath = $file->store('uploads/covers', 'public');
        }

        // Create card
        $card = Card::create([
            'title' => $request->title,
            'description' => $request->description,
            'label' => $request->label,
            'deadline' => $request->deadline,
            'board_id' => $board_id,
            'user_id' => Auth::id(),
            'cover' => $coverPath,
        ]);

        return response()->json([
            'message' => 'Card berhasil dibuat!',
            'card' => $card
        ], 201);
    }

    // Update card
    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            if ($card->cover && Storage::exists('public/' . $card->cover)) {
                Storage::delete('public/' . $card->cover);
            }

            $file = $request->file('cover');
            $coverPath = $file->store('uploads/covers', 'public');
            $card->cover = $coverPath;
        }

        $card->update($request->only(['title', 'description', 'deadline', 'label']));

        return response()->json([
            'message' => 'Card berhasil diperbarui!',
            'card' => $card
        ], 200);
    }

    // Delete card
    public function destroy($id)
    {
        $card = Card::findOrFail($id);

        if ($card->cover && Storage::exists('public/' . $card->cover)) {
            Storage::delete('public/' . $card->cover);
        }

        $card->delete();

        return response()->json(['message' => 'Card berhasil dihapus!'], 200);
    }
}

