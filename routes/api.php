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

// Workspace Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::delete('/workspaces/{id}', [WorkspaceController::class, 'destroy']);

    // Board Routes 
    Route::get('/workspaces/{workspace_id}/boards', [BoardController::class, 'index']);
    Route::post('/workspaces/{workspace_id}/boards', [BoardController::class, 'store']);
    Route::delete('/boards/{id}', [BoardController::class, 'destroy']);

    // Card Routes
    Route::get('/boards/{board_id}/cards', [CardController::class, 'index']);
    Route::post('/boards/{board_id}/cards', [CardController::class, 'store']);
    Route::put('/cards/{id}', [CardController::class, 'update']);
    Route::delete('/cards/{id}', [CardController::class, 'destroy']);
});
