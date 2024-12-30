<?
namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::where('user_id', auth()->id())->get();
        return response()->json($boards);
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);

        $board = Board::create([
            'nama' => $request->nama,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'board' => $board]);
    }

    public function edit(Board $board)
    {
        return view('boards.edit', compact('board'));
    }

    public function update(Request $request, Board $board)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        $board->update(['nama' => $request->nama]);
        return redirect()->route('boards.index')->with('success', 'Board berhasil diperbarui!');
    }

    public function destroy(Board $board)
    {
        $board->delete();
        return response()->json(['success' => true]);
    }
    public function inviteMember(Request $request, Board $board)
    {
        $request->validate(['member_id' => 'required|exists:users,id']);
        $board->member_id = $request->member_id;
        $board->save();
        return redirect()->route('boards.index')->with('success', 'Anggota berhasil diundang!');
    }
}