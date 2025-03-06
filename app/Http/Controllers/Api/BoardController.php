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
        $user = Auth::user();
        $workspace = Workspace::findOrFail($workspace_id);

        // Cek apakah user adalah owner atau member workspace
        if (!$this->hasAccessToWorkspace($workspace, $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Ambil semua board di dalam workspace
        $boards = Board::where('workspace_id', $workspace_id)->get();

        return response()->json($boards);
    }

    // Create a new board
    public function store(Request $request, $workspace_id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $workspace = Workspace::findOrFail($workspace_id);
        $user = Auth::user();

        // Cek apakah user adalah owner workspace
        if ($workspace->owner_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $board = Board::create([
            'nama' => $request->nama,
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Board berhasil dibuat!',
            'board' => $board
        ], 201);
    }

    // Update board
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'sometimes|required|string|max:255',
        ]);

        $board = Board::findOrFail($id);
        $workspace = $board->workspace;
        $user = Auth::user();

        // Hanya owner workspace yang bisa mengupdate board
        if ($workspace->owner_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($request->has('nama')) {
            $board->nama = $request->nama;
        }

        $board->save();

        return response()->json([
            'message' => 'Board berhasil diperbarui!',
            'board' => $board
        ], 200);
    }

    // Delete a board
    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $workspace = $board->workspace;
        $user = Auth::user();

        // Hanya owner workspace yang bisa menghapus board
        if ($workspace->owner_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $board->delete();
        return response()->json(['message' => 'Board berhasil dihapus!']);
    }

    // Helper function: Cek akses ke workspace
    private function hasAccessToWorkspace($workspace, $userId)
    {
        // Pastikan hanya string yang di-decode, jika sudah array langsung gunakan
        $members = is_string($workspace->member) ? json_decode($workspace->member, true) ?? [] : $workspace->member;

        return $workspace->owner_id === $userId || in_array($userId, $members);
    }


}
