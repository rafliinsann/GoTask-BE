<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Listt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardController extends Controller
{
    // Get all cards for a specific list
    public function index($list_id)
    {
        $list = Listt::findOrFail($list_id);
        $cards = Card::where('list_id', $list_id)->get();
        return response()->json([
            'list' => $list,
            'cards' => $cards
        ], 200);
    }

    // Get all cards
    public function show()
    {
        return response()->json(Card::all(), 200);
    }

    // Create a new card
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'list_id' => 'required|exists:list,id',
	    'board_id' => 'nullable|exists:board,id',
'dates' => 'nullable|date',
        ]);

// Cari board_id dari list yang terkait
    $list = Listt::findOrFail($request->list_id);
    $board_id = $list->board_id;

        // Handle file upload
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $coverPath = $file->store('uploads/covers', 'public');
        }

        // Save the card
        $card = Card::create([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'label' => $request->label,
            'deadline' => $request->deadline,
            'list_id' => $request->list_id,
            'cover' => $coverPath,
'board_id' => $board_id,
 'dates' => $request->dates ?? now(),
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

        $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'nullable|date',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($card->cover && Storage::exists('public/' . $card->cover)) {
                Storage::delete('public/' . $card->cover);
            }

            $file = $request->file('cover');
            $coverPath = $file->store('uploads/covers', 'public');
            $card->cover = $coverPath;
        }

        $card->update([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
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

        // Hapus cover jika ada
        if ($card->cover && Storage::exists('public/' . $card->cover)) {
            Storage::delete('public/' . $card->cover);
        }

        $card->delete();

        return response()->json(['message' => 'Card berhasil dihapus!'], 200);
    }

    // Update card position/order
    public function updatePosition(Request $request)
    {
        $request->validate([
            'cards' => 'required|array',
            'list_id' => 'required|exists:lists,id',
        ]);

        foreach ($request->cards as $position => $id) {
            Card::where('id', $id)->update([
                'list_id' => $request->list_id,
                'order' => $position
            ]);
        }

        return response()->json(['message' => 'Posisi kartu berhasil diperbarui!'], 200);
    }
}

