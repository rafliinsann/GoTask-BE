<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\ListController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\WorkspaceController;


Route::middleware([])->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'API works without middleware!']);
    });
});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);

    Route::get('/boards', [BoardController::class, 'index']);
    Route::post('/boards', [BoardController::class, 'store']);
    Route::get('/boards/{id}', [BoardController::class, 'show']);
    Route::put('/boards/{id}', [BoardController::class, 'update']);
    Route::delete('/boards/{id}', [BoardController::class, 'destroy']);

    Route::get('/lists/{board_id}', [ListController::class, 'index']);
    // Route::post('/lists', [ListController::class, 'store']);
    Route::post('/boards/{board_id}/lists', [ListController::class, 'store']); //menambahkan list sesuai board yang dibuat tadi
    Route::delete('/lists/{id}', [ListController::class, 'destroy']);
  Route::prefix('cards')->group(function () {
    // Get all cards dari list spesifik
    Route::get('/list/{list_id}', [CardController::class, 'index']);

    // Get all cards (global)
    Route::get('/', [CardController::class, 'show']);

    // Create a new card
    Route::post('/', [CardController::class, 'store']);

    // Update a specific card
    Route::put('/{id}', [CardController::class, 'update']);

    // Delete a specific card
    Route::delete('/{id}', [CardController::class, 'destroy']);

    // Update position/order of cards
    Route::post('/update-position', [CardController::class, 'updatePosition']);
});
    //Route::get('/cards/{list_id}', [CardController::class, 'index']);
    // Route::get('/cards', [CardController::class, 'show']);
    //Route::post('/cards', [CardController::class, 'store']);
   // Route::put('/cards/{id}', [CardController::class, 'update']);
   // Route::delete('/cards/{id}', [CardController::class, 'destroy']);
});
