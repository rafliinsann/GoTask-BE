<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workspace;
use Exception;

class WorkspaceController extends Controller
{
    public function index()
    {
        return response()->json(Workspace::all());
    }

    public function store(Request $request)
    {
        try{        
        $request->validate([
                'username' => 'required',
                'board' => 'required',
                'member' => 'nullable'
            ]);

            $workspace = Workspace::create($request->all());
            return response()->json($workspace);
        }catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
