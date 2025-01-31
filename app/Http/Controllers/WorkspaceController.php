<?php
namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = Workspace::where('username', auth()->user()->username)->get();
        return view('workspace.index', compact('workspaces'));
    }

    public function create()
    {
        return view('workspace.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'board' => 'required|string|max:255',
        ]);

        Workspace::create([
            'username' => auth()->user()->username,
            'board' => $request->board,
            'member' => json_encode([]), // Default kosong
        ]);

        return redirect()->route('workspace.index')->with('success', 'Workspace berhasil dibuat!');
    }

    public function show(Workspace $workspace)
    {
        return view('workspace.show', compact('workspace'));
    }

    public function edit(Workspace $workspace)
    {
        return view('workspace.edit', compact('workspace'));
    }

    public function update(Request $request, Workspace $workspace)
    {
        $request->validate([
            'board' => 'required|string|max:255',
        ]);

        $workspace->update([
            'board' => $request->board,
        ]);

        return redirect()->route('workspace.index')->with('success', 'Workspace berhasil diperbarui!');
    }

    public function destroy(Workspace $workspace)
    {
        $workspace->delete();
        return redirect()->route('workspace.index')->with('success', 'Workspace berhasil dihapus!');
    }
}
