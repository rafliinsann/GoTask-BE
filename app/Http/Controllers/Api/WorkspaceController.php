<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Exception;
use Illuminate\Http\Request;

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
