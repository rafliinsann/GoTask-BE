<?php
namespace App\Http\Controllers;

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

public function show()
{
 return response()->json(Card::all());
}

    public function create($list_id)
    {
        return view('card.create', compact('list_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'deskripsi' => 'nullable',
            'label' => 'nullable|string',
            'deadline' => 'nullable|date',
            'list_id' => 'required|exists:list,id'
        ]);

        Card::create($request->all());

        return redirect()->route('board.show', ['id' => Listt::find($request->list_id)->board_id])->with('success', 'Card berhasil ditambahkan!');
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
        ]);

        $card->update([
            'title' => $request->title,
            'deskripsi' => $request->deskripsi,
            'dates' => $request->dates,
        ]);

        return redirect()->route('card.index', $list_id)->with('success', 'Card berhasil diperbarui!');
    }

    public function destroy($list_id, Card $card)
    {
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
