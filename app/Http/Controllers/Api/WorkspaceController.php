<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    // Get all workspaces (Owner or Member)
    public function index()
    {
        $user = Auth::user();
        $workspaces = Workspace::where('owner_id', $user->id)
            ->orWhereJsonContains('member', $user->id)
            ->get();

        return response()->json($workspaces, 200);
    }

    // Create a new workspace
    public function store(Request $request)
    {
        $request->validate([
            'workspace' => 'required|string|max:255',
            'colour' => 'nullable|string'
        ]);

        $user = Auth::user();

        $workspace = Workspace::create([
            'workspace' => $request->workspace,
            'owner_id' => $user->id, // Owner workspace
            'username' => $user->username,
            'colour' => $request->colour,
            'member' => json_encode([$user->id]) // Owner langsung jadi member
        ]);

        return response()->json([
            'message' => 'Workspace berhasil dibuat!',
            'workspace' => $workspace
        ], 201);
    }

    // Show a specific workspace
    public function show($id)
    {
        $workspace = Workspace::findOrFail($id);

        if (!$this->hasAccess($workspace)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($workspace, 200);
    }

    // Update workspace
    public function update(Request $request, $id)
    {
        $workspace = Workspace::findOrFail($id);

        if (!$this->isOwner($workspace)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $workspace->update($request->only(['workspace', 'colour']));

        return response()->json([
            'message' => 'Workspace berhasil diperbarui!',
            'workspace' => $workspace
        ], 200);
    }

    // Delete workspace
    public function destroy($id)
    {
        $workspace = Workspace::findOrFail($id);

        if (!$this->isOwner($workspace)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $workspace->delete();

        return response()->json(['message' => 'Workspace berhasil dihapus!'], 200);
    }

    // Invite member to workspace
    public function inviteMember(Request $request, $workspace_id)
    {
        $workspace = Workspace::findOrFail($workspace_id);

        if (Auth::id() !== $workspace->owner_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'username' => 'required|exists:users,username'
        ]);

        // Cari user berdasarkan username
        $userToInvite = User::where('username', $request->username)->firstOrFail();

        $members = json_decode($workspace->member, true) ?? [];

        // Tambahkan ID user ke member list jika belum ada
        if (!in_array($userToInvite->id, $members)) {
            $members[] = $userToInvite->id;
            $workspace->member = json_encode($members);
            $workspace->save();
        }

        return response()->json(['message' => 'Member berhasil diundang!']);
    }

    // Helper function: Check if user is owner
    private function isOwner($workspace)
    {
        return Auth::id() === $workspace->owner_id;
    }

    // Helper function: Check if user has access (owner or member)
    private function hasAccess($workspace)
    {
        $userId = Auth::id();
        $members = json_decode($workspace->member, true) ?? [];
        return $workspace->owner_id === $userId || in_array($userId, $members);
    }
}
