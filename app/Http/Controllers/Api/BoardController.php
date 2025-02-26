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

        // Cek apakah user adalah member atau owner
        if (!$this->hasAccessToWorkspace($workspace, $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $workspace = Workspace::findOrFail($request->workspace_id);

        // Cek apakah user adalah owner atau superadmin
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

    // update board
    public function update(Request $request, $id)
{
    $request->validate([
        'nama' => 'sometimes|required|string|max:255',
        'members' => 'sometimes|array',
        'members.*' => 'exists:users,id',
    ]);

    $board = Board::findOrFail($id);
    $workspace = $board->workspace;
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Hanya owner workspace atau superadmin yang bisa mengupdate
    if ($workspace->owner_id !== $user->id && !$user->isSuperAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Update nama board jika ada
    if ($request->has('nama')) {
        $board->nama = $request->nama;
    }

    // Update member jika ada
    if ($request->has('members')) {
        $board->member = json_encode($request->members);
    }

    $board->save();

    return response()->json([
        'message' => 'Board berhasil diperbarui!',
        'board' => $board
    ], 200);
}

    // Add a new member to the board
    public function addMember(Request $request, $id)
{
    $request->validate([
        'username' => 'required|exists:users,username',
    ]);

    
    $board = Board::findOrFail($id);
    $workspace = $board->workspace;
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Cek apakah user yang login adalah owner workspace atau superadmin
    if ($workspace->owner_id !== $user->id && !$user->isSuperAdmin()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $userToInvite = \App\Models\User::where('username', $request->username)->firstOrFail();
    $members = json_decode($board->member, true) ?? [];

    if (!in_array($userToInvite->id, $members)) {
        $members[] = $userToInvite->id;
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
        $workspace = Workspace::findOrFail($board->workspace_id);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya owner workspace atau superadmin yang bisa menghapus
        if ($workspace->owner_id !== $user->id && !$user->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $board->delete();
        return response()->json(['message' => 'Board berhasil dihapus!']);
    }

    // Helper function: Cek akses ke workspace
    private function hasAccessToWorkspace($workspace, $userId)
    {
        $members = json_decode($workspace->members, true) ?? [];
        return $workspace->owner_id === $userId || in_array($userId, $members);
    }
}

