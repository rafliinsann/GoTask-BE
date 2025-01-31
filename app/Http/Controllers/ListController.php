<?php
namespace App\Http\Controllers;

use App\Models\Listt;
use App\Models\Board;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function index($board_id)
    {
        $board = Board::findOrFail($board_id);
        $lists = Listt::where('board_id', $board_id)->get();
        return view('list.index', compact('board', 'lists'));
    }

    public function create($board_id)
    {
        return view('list.create', compact('board_id'));
    }

    public function store(Request $request, $board_id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Listt::create([
            'board_id' => $board_id,
            'nama' => $request->nama,
        ]);

        return redirect()->route('list.index', $board_id)->with('success', 'List berhasil ditambahkan!');
    }

    public function edit($board_id, Listt $list)
    {
        return view('list.edit', compact('board_id', 'list'));
    }

    public function update(Request $request, $board_id, Listt $list)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $list->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('list.index', $board_id)->with('success', 'List berhasil diperbarui!');
    }

    public function destroy($board_id, Listt $list)
    {
        $list->delete();
        return redirect()->route('list.index', $board_id)->with('success', 'List berhasil dihapus!');
    }
}
