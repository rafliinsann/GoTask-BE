<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Listt;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index($list_id)
    {
        $list = Listt::findOrFail($list_id);
        $cards = Card::where('list_id', $list_id)->get();
        return view('card.index', compact('list', 'cards'));
    }

<<<<<<< HEAD
    public function create($list_id)
    {
        return view('card.create', compact('list_id'));
    }

	public function show(){
		return response()->json(Card::all());
	}

=======
    public function show()
    {
        return response()->json(Card::all());
    }
>>>>>>> temp-fix
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'deskripsi' => 'nullable',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'list_id' => 'required|exists:list,id'
        ]);

        // Handle upload gambar cover
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/covers'), $filename);
            $coverPath = 'uploads/covers/' . $filename;
        }

        // Simpan data card
        Card::create([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'label' => $request->label,
            'deadline' => $request->deadline,
            'list_id' => $request->list_id,
            'cover' => $coverPath,
        ]);

        return redirect()->route('board.show', ['id' => Listt::find($request->list_id)->board_id])
            ->with('success', 'Card berhasil ditambahkan!');
    }

    public function edit($list_id, Card $card)
    {
        return view('card.edit', compact('list_id', 'card'));
    }

    public function update(Request $request, $list_id, Card $card)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'dates' => 'nullable|date',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle upload cover baru
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/covers'), $filename);
            $coverPath = 'uploads/covers/' . $filename;

            // Hapus cover lama jika ada
            if ($card->cover && file_exists(public_path($card->cover))) {
                unlink(public_path($card->cover));
            }

            $card->cover = $coverPath;
        }

        $card->update([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'dates' => $request->dates,
            'cover' => $card->cover, // Update cover jika diubah
        ]);

        return redirect()->route('card.index', $list_id)->with('success', 'Card berhasil diperbarui!');
    }

    public function destroy($list_id, Card $card)
    {
        // Hapus cover dari storage
        if ($card->cover && file_exists(public_path($card->cover))) {
            unlink(public_path($card->cover));
        }

        $card->delete();
        return redirect()->route('card.index', $list_id)->with('success', 'Card berhasil dihapus!');
    }

    public function updatePosition(Request $request)
    {
        $cards = $request->cards; // Data dari AJAX

        foreach ($cards as $position => $id) {
            Card::where('id', $id)->update(['list_id' => $request->list_id, 'order' => $position]);
        }

        return response()->json(['success' => true]);
    }
}

