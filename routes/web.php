<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/update-card', [DashboardController::class, 'updateCard'])->name('update.card');
Route::post('/create-board', [DashboardController::class, 'createBoard'])->name('create.board');
Route::post('/create-card', [DashboardController::class, 'createCard'])->name('create.card');
Route::get('boards/{board}/edit', [BoardController::class, 'edit'])->name('boards.edit');
Route::put('boards/{board}', [BoardController::class, 'update'])->name('boards.update');

Route::post('boards/{board}/invite', [BoardController::class, 'inviteMember'])->name('boards.invite');

require __DIR__.'/auth.php';
