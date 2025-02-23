<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    // Get all boards for a workspace
    public function index($workspace_id)
    {
        return response()->json(Board::where('workspace_id', $workspace_id)->get());
    }

    // Create a new board
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        $userId = Auth::id();

        $board = Board::create([
            'nama' => $request->nama,
            'workspace_id' => $request->workspace_id,
            'user_id' => $userId,
            'member' => json_encode([$userId]), // Default member adalah user yang login
        ]);

        return response()->json([
            'message' => 'Board berhasil dibuat!',
            'board' => $board
        ], 201);
    }

    // Add a new member to the board
    public function addMember(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $board = Board::findOrFail($id);
        $members = json_decode($board->member, true);

        if (!in_array($request->user_id, $members)) {
            $members[] = $request->user_id;
            $board->member = json_encode($members);
            $board->save();
        }

        return response()->json([
            'message' => 'Member berhasil ditambahkan!',
            'board' => $board
        ], 200);
    }

    // Delete a board
    public function destroy($id)
    {
        Board::destroy($id);
        return response()->json(['message' => 'Board berhasil dihapus!']);
    }
}

