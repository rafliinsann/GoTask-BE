<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    // Get all boards for a workspace
    public function index($workspace_id)
    {
        $boards = Board::where('workspace_id', $workspace_id)->get();
        return response()->json($boards);
    }

    // Create a new board
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        $user = Auth::user();
        $workspace = Workspace::findOrFail($request->workspace_id);

        // Cek apakah user adalah owner workspace
        if ($workspace->owner_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $board = Board::create([
            'nama' => $request->nama,
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'member' => json_encode([$user->id]), // Default member adalah user yang login
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
        $workspace = $board->workspace;
        $user = Auth::user();

        // Cek apakah user yang login adalah owner workspace atau superadmin
        if ($workspace->owner_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $members = json_decode($board->member, true) ?? [];

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
        $board = Board::findOrFail($id);
        $workspace = $board->workspace;
        $user = Auth::user();

        // Hanya owner workspace atau superadmin yang bisa menghapus
        if ($workspace->owner_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $board->delete();
        return response()->json(['message' => 'Board berhasil dihapus!']);
    }
}

