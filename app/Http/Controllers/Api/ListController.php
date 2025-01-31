<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listt;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function index($board_id)
    {
        return response()->json(Listt::where('board_id', $board_id)->get());
    }

    public function store(Request $request)
    {
        $request->validate(['board_id' => 'required', 'card' => 'required']);
        $list = Listt::create($request->all());
        return response()->json($list, 201);
    }

    public function destroy($id)
    {
        Listt::destroy($id);
        return response()->json(['message' => 'List deleted']);
    }
}
