<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index()
    {
        return response()->json(Workspace::all());
    }

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'board' => 'required|string',
            ]);

            // Ambil user yang login
            $user = Auth::user();

            // Buat workspace baru
            $workspace = Workspace::create([
                'username' => $user->username, // Menggunakan nama user yang login
                'board' => $request->board,
                'member' => json_encode([$user->id]) // Menambahkan user sebagai member default
            ]);

            return response()->json($workspace, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

