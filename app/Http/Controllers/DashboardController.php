<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard');
    }
//     public function updateCard(Request $request)
// {
//     $request->validate([
//         'card_id' => 'required|integer',
//         'status' => 'required|string',
//     ]);

//     $card = Card::find($request->card_id);
//     if ($card) {
//         $card->status = $request->status;
//         $card->save();
//         return response()->json(['success' => true]);
//     }

//     return response()->json(['success' => false], 404);
// }
// public function createBoard(Request $request)
// {
//     $request->validate(['name' => 'required|string|max:255']);

//     $board = new Board();
//     $board->name = $request->name;
//     $board->user_id = auth()->id();
//     $board->save();

//     return response()->json(['success' => true, 'board' => $board]);
// }
// public function createCard(Request $request)
// {
//     $request->validate([
//         'title' => 'required|string|max:255',
//         'board_id' => 'required|integer',
//     ]);

//     $card = new Card();
//     $card->title = $request->title;
//     $card->board_id = $request->board_id;
//     $card->status = 'Project Resources'; // Status default
//     $card->save();

//     return response()->json(['success' => true, 'card' => $card]);
// }
}