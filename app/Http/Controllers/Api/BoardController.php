<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    // Mendapatkan semua board
    public function index()
    {
        return response()->json(Board::all(), 200);
    }


    // Mendapatkan board milik user tertentu
    public function getUserBoards()
{
    $userId = auth()->id();

    $boards = Board::where('user_id', $userId)->get();

    if ($boards->isEmpty()) {
        return response()->json(['message' => 'Tidak ada boards ditemukan.'], 404);
    }

    return response()->json($boards, 200);
}


    // Menyimpan board baru
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
    ]);

    // Ambil ID user yang sedang login
    $user = auth()->user();
    if (!$user) {
        return response()->json(['error' => 'User tidak terautentikasi'], 401);
    }

    $workspace = Workspace::where('username', $user->username)->first();

    if (!$workspace) {
        return response()->json(['message' => 'Workspace tidak ditemukan.'], 404);
    }

    // Buat board dengan user_id yang login
    $board = Board::create([
        'nama' => $request->nama,
        'user_id' => $user->id,
	'workspace_id' => $workspace->id,
	'member' => json_encode([$user->id]),
    ]);

    return response()->json($board, 201);
}


    // Menampilkan board berdasarkan ID
    public function show($id)
    {
        $board = Board::find($id);

        if (!$board) {
            return response()->json(['message' => 'Board tidak ditemukan.'], 404);
        }

        return response()->json($board, 200);
    }

    // Memperbarui board
    public function update(Request $request, $id)
    {
        $board = Board::find($id);

        if (!$board) {
            return response()->json(['message' => 'Board tidak ditemukan.'], 404);
        }

        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
        ]);

        $board->update($request->all());

        return response()->json($board, 200);
    }

    // Menghapus board
    public function destroy($id)
    {
        $board = Board::find($id);

        if (!$board) {
            return response()->json(['message' => 'Board tidak ditemukan.'], 404);
        }

        $board->delete();

        return response()->json(['message' => 'Board berhasil dihapus.'], 200);
    }
}

