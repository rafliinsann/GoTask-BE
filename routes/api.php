<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    Route::resource('boards', BoardController::class);
    Route::resource('boards.cards', CardController::class);
    Route::put('boards/{board}/cards/order', [CardController::class, 'updateOrder']);

    Route::get('/boards', [BoardController::class, 'index']); // Menampilkan semua board
    Route::post('/boards', [BoardController::class, 'store']); // Menambahkan board baru
    Route::get('/boards/{board}', [BoardController::class, 'show']); // Menampilkan board tertentu
    Route::put('/boards/{board}', [BoardController::class, 'update']); // Memperbarui board tertentu
    Route::delete('/boards/{board}', [BoardController::class, 'destroy']); // Menghapus board tertentu

    // Rute untuk Card
    Route::get('/boards/{board}/cards', [CardController::class, 'index']); // Menampilkan semua card dalam board tertentu
    Route::post('/boards/{board}/cards', [CardController::class, 'store']); // Menambahkan card baru ke board tertentu
    Route::get('/cards/{card}', [CardController::class, 'show']); // Menampilkan card tertentu
    Route::put('/cards/{card}', [CardController::class, 'update']); // Memperbarui card tertentu
    Route::delete('/cards/{card}', [CardController::class, 'destroy']); // Menghapus card tertentu
    return $request->user();
});
