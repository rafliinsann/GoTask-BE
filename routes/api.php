<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WorkspaceController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\CardController;

// Group route dengan middleware auth
Route::middleware('auth:sanctum')->group(function () {
    // 🔹 Workspace Routes
    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::get('/workspaces/{id}', [WorkspaceController::class, 'show']);
    Route::put('/workspaces/{id}', [WorkspaceController::class, 'update']);
    Route::delete('/workspaces/{id}', [WorkspaceController::class, 'destroy']);
    Route::post('/workspaces/{id}/invite', [WorkspaceController::class, 'inviteMember']);

    // 🔹 Board Routes
    Route::get('/workspaces/{workspace_id}/boards', [BoardController::class, 'index']);
    Route::post('/boards', [BoardController::class, 'store']);
    Route::post('/boards/{id}/add-member', [BoardController::class, 'addMember']);
    Route::delete('/boards/{id}', [BoardController::class, 'destroy']);

    // 🔹 Card Routes
    Route::get('/boards/{board_id}/cards', [CardController::class, 'index']);
    Route::post('/cards', [CardController::class, 'store']);
    Route::put('/cards/{id}', [CardController::class, 'update']);
    Route::delete('/cards/{id}', [CardController::class, 'destroy']);
});

