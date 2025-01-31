<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index($list_id)
    {
        return response()->json(Card::where('list_id', $list_id)->get());
    }

    public function store(Request $request)
    {
        $request->validate(['list_id' => 'required', 'title' => 'required']);
        $card = Card::create($request->all());
        return response()->json($card, 201);
    }

    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);
        $card->update($request->all());
        return response()->json($card);
    }

    public function destroy($id)
    {
        Card::destroy($id);
        return response()->json(['message' => 'Card deleted']);
    }
}
