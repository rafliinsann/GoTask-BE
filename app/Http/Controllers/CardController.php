<?
namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CardController extends Controller
{
    public function index()
    {
        return response()->json(Card::all(), Response::HTTP_OK);
    }

    public function show(Card $id){
        $card = Card::find($id);
        if(!$card){
            return response()->json(['message', 'Board not found']);
        }
        return response()->json($card, Response::HTTP_OK);

    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'assign' => 'nullable',
            'label' => 'nullable',
            'deskripsi' => 'nullable',
            'board_id' => 'required|exists:boards,id',
        ]);

        $card = Card::create($validated);

        return response()->json($card, 201);
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'label' => 'nullable',
            'deskripsi' => 'nullable',
            'board_id' => 'required|exists:boards,id',
        ]);
        $card->update($request->all());

        return response()->json(['success' => true]);
    }

    public function destroy(Card $card)
    {
        $card->delete();
        return response()->json(['success' => true]);
    }
}
