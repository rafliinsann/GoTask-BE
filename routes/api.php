<?php
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WorkspaceController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Group route dengan middleware auth
Route::middleware('auth:sanctum')->group(function () {
    // User Get All
    Route::get('/users', [UserController::class, 'index']);

    // Fitur DnD
    Route::put('/cards/{id}/move', [CardController::class, 'moveCard']);


    // 🔹 Workspace Routes
    Route::get('/workspaces', [WorkspaceController::class, 'index']);
    Route::post('/workspaces', [WorkspaceController::class, 'store']);
    Route::get('/workspaces/{id}', [WorkspaceController::class, 'show']);
    Route::put('/workspaces/{id}', [WorkspaceController::class, 'update']);
    Route::delete('/workspaces/{id}', [WorkspaceController::class, 'destroy']);
    Route::post('/workspaces/{id}/invite', [WorkspaceController::class, 'inviteMember']);

    // 🔹 Board Routes
    Route::get('/boards/workspaces/{workspace_id}', [BoardController::class, 'index']);
    Route::post('/boards', [BoardController::class, 'store']);
    Route::put('/boards/{id}', [BoardController::class, 'update']);
    Route::post('/boards/{id}/add-member', [BoardController::class, 'addMember']);
    Route::delete('/boards/{id}', [BoardController::class, 'destroy']);

    // 🔹 Card Routes
    Route::get('/cards/boards/{board_id}', [CardController::class, 'index']);
    Route::post('/cards/boards/{board_id}', [CardController::class, 'store']);
    Route::put('/cards/{id}', [CardController::class, 'update']);
    Route::delete('/cards/{id}', [CardController::class, 'destroy']);
});

