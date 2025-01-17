<?php

use App\Http\Controllers\Api\ApiBoardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\Api\CardController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    Route::get('/test', function() {
        return response()->json(['message' => 'Aman Bosku!']);
    });
    Route::apiResource('/boards', ApiBoardController::class);
    Route::apiResource('/cards', CardController::class);

    // Route::resource('boards.cards', CardController::class);
    // Route::put('boards/{board}/cards/order', [CardController::class, 'updateOrder']);

    // Route::get('/boards/{board}/cards', [CardController::class, 'index']); // Menampilkan semua card dalam board tertentu
    // Route::post('/boards/{board}/cards', [CardController::class, 'store']);
    // Route::get('/cards/{card}', [CardController::class, 'show']); // Menampilkan card tertentu
    // Route::put('/cards/{card}', [CardController::class, 'update']); // Memperbarui card tertentu
    // Route::delete('/cards/{card}', [CardController::class, 'destroy']); // Menghapus card tertentu

