<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        return response()->json(Board::all());
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
