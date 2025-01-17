<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Card::all(), Response::HTTP_OK);
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
    {        $validated = $request->validate([
        'title' => 'required|string|max:255',
        'assign' => 'nullable',
        'label' => 'nullable',
        'deskripsi' => 'nullable',
        'board_id' => 'required|exists:boards,id',
    ]);

    $card = Card::create($validated);

    return response()->json($card, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $id)
    {
        $card = Card::find($id);
        if(!$card){
            return response()->json(['message', 'Board not found']);
        }
        return response()->json($card, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        $request->validate([
        'title' => 'required|string|max:255',
        'label' => 'nullable',
        'deskripsi' => 'nullable',
        'board_id' => 'required|exists:boards,id',
    ]);
    $card->update($request->all());

    return response()->json(['success' => true]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return response()->json(['success' => true]);
    }
}
