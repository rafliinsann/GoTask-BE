<?php
namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    // Menampilkan daftar board dalam workspace tertentu
    public function index($workspace_id)
    {
        $workspace = Workspace::findOrFail($workspace_id);
        $boards = Board::where('workspace_id', $workspace_id)->get();
        return view('board.index', compact('workspace', 'boards'));
    }

    // Menampilkan form tambah board
    public function create($workspace_id)
    {
        return view('board.create', compact('workspace_id'));
    }

    // Menyimpan board baru
    public function store(Request $request, $workspace_id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Board::create([
            'workspace_id' => $workspace_id,
            'nama' => $request->nama,
        ]);

        return redirect()->route('board.index', $workspace_id)->with('success', 'Board berhasil ditambahkan!');
    }

    // Menampilkan detail board
    public function show($workspace_id, Board $board)
    {
        return view('board.show', compact('workspace_id', 'board'));
    }

    // Menampilkan form edit board
    public function edit($workspace_id, Board $board)
    {
        return view('board.edit', compact('workspace_id', 'board'));
    }

    // Menyimpan perubahan board
    public function update(Request $request, $workspace_id, Board $board)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $board->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('board.index', $workspace_id)->with('success', 'Board berhasil diperbarui!');
    }

    // Menghapus board
    public function destroy($workspace_id, Board $board)
    {
        $board->delete();
        return redirect()->route('board.index', $workspace_id)->with('success', 'Board berhasil dihapus!');
    }
}
