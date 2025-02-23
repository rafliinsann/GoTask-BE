<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    // Get all workspaces
    public function index()
    {
        return response()->json(Workspace::all(), 200);
    }

    // Create a new workspace
    public function store(Request $request)
    {
        $request->validate([
            'workspace' => 'required|string|max:255',
        ]);

	$user = Auth::user();

        $workspace = Workspace::create([
            'username' => $user->username, // Menggunakan nama user yang login
            'workspace' => $request->workspace,
            'user_id' => auth()->id(), // Mengatur user_id sesuai yang login
	    'member' => json_encode([$user->id])
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
        return response()->json($workspace, 200);
    }

    // Update workspace
    public function update(Request $request, $id)
    {
        $workspace = Workspace::findOrFail($id);
        $workspace->update($request->all());

        return response()->json([
            'message' => 'Workspace berhasil diperbarui!',
            'workspace' => $workspace
        ], 200);
    }

    // Delete workspace
    public function destroy($id)
    {
        Workspace::destroy($id);
        return response()->json(['message' => 'Workspace berhasil dihapus!'], 200);
    }

public function inviteMember(Request $request, $workspace_id)
{
    $workspace = Workspace::findOrFail($workspace_id);

    if (auth()->id() !== $workspace->owner_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $members = $workspace->members ?? [];
    if (!in_array($request->user_id, $members)) {
        $members[] = $request->user_id;
        $workspace->members = $members;
        $workspace->save();
    }

    return response()->json(['message' => 'Member berhasil diundang!']);
}

}

