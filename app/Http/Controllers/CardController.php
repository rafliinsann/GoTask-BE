<?
namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(Board $board)
    {
        $cards = $board->cards;
        return response()->json($cards);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
        ]);

        $card = Card::create($request->all());

        return response()->json(['success' => true, 'card' => $card]);
    }

    public function update(Request $request, Card $card)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $card->update($request->all());

        return response()->json(['success' => true]);
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return response()->json(['success' => true]);
    }
    public function updateOrder(Request $request, Board $board)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $cardId) {
            Card::where('id', $cardId)->update(['order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}