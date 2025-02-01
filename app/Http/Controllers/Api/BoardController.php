<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function getUserBoards(Request $request, $userId)
    {
        $username = $request->query('username');

        if (!$username) {
            return response()->json(['message' => 'Username tidak ditemukan.'], 400);
        }

        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['message' => 'User  tidak ditemukan.'], 404);
        }

        $boards = Board::where('user_id', $userId)->get(); // Mengambil boards berdasarkan user_id

        if ($boards->isEmpty()) {
            return response()->json(['message' => 'Tidak ada boards ditemukan.'], 404);
        }

        return response()->json($boards, 200); // Mengembalikan data boards
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
        $board = Board::create($request->all());
        return response()->json($board, 201);
    }

    public function show($id)
    {
        return response()->json(Board::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $board = Board::findOrFail($id);
        $board->update($request->all());
        return response()->json($board);
    }

    public function destroy($id)
    {
        Board::destroy($id);
        return response()->json(['message' => 'Board deleted']);
    }
}
