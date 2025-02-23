<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Listt;
use Illuminate\Http\Request;

class ListController extends Controller
{
    // Mengambil semua list berdasarkan board_id
    public function index($board_id)
    {
        $lists = Listt::where('board_id', $board_id)->get();

        if ($lists->isEmpty()) {
            return response()->json(['message' => 'Tidak ada list ditemukan untuk board ini.'], 404);
        }

        return response()->json($lists);
    }

    // Menyimpan list baru
    public function store(Request $request, $board_id)
{
    $request->validate([
        'card' => 'required|array',
    ]);

    $list = Listt::create([
        'board_id' => $board_id,  // Isi board_id langsung dari parameter
        'card' => $request->card,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'List created successfully',
        'data' => $list,
    ], 201);
}


    // Menghapus list berdasarkan ID
    public function destroy($id)
    {
        $list = Listt::find($id);

        if (!$list) {
            return response()->json(['message' => 'List tidak ditemukan.'], 404);
        }

        $list->delete();
        return response()->json(['message' => 'List berhasil dihapus.']);
    }
}

