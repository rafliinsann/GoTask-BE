<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Get all users
    public function index()
    {
        $users = User::select('id', 'name', 'username', 'division', 'class','email')->get();

        return response()->json([
            'message' => 'Daftar pengguna berhasil diambil!',
            'users' => $users
        ], 200);
    }
}
