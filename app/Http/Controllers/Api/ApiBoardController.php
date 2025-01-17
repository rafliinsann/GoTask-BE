<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ApiBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Board::all(), HttpFoundationResponse::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validator = $request->validate([
                'nama' => 'required|string|max:155',
                'card' => 'nullable',
                'member' => 'nullable',
            ]);

            $board = Board::create($validator);
            return response()->json($board, 201);
        } catch (\Exception $e) {
            return response()->json(['error', $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $id)
    {
        $board = Board::find($id);
        if(!$board){
            return response()->json(['message', 'Board not found']);
        }
        return response()->json($board, HttpFoundationResponse::HTTP_OK);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $id)
    {
        try{
            $board = Board::find($id);

        if(!$board){
            return response()->json(['message', 'Board not found']);
        }
        $validator = $request->validate([
            'nama' => 'required|string|max:155',
            'card' => 'nullable',
            'member' => 'nullable',
        ]);

        $board->update($validator);
        return response()->json($board, 201);
    } catch (\Exception $e) {
        return response()->json(['error', $e->getMessage()], 500);
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $id)
    {
        try{
            $board = Board::find($id);
        if(!$board){
            return response()->json(['message', 'Board not found']);
        }
        $board->delete();

        return response()->json([
            'message' => 'Board deleted successfully',
            201
        ]);
    } catch (\Exception $e) {
        return response()->json(['error', $e->getMessage()], 500);
    }
    }
}
