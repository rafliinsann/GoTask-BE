<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkspaceController;
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

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::post('/card/update-position', [CardController::class, 'updatePosition'])->name('card.updatePosition');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
// Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// Route::resource('workspace', WorkspaceController::class);
// Route::resource('workspace.board', BoardController::class);
// Route::resource('board.list', ListController::class);
// Route::resource('list.card', CardController::class);
// Route::middleware('auth')->group(function () {
// });

require __DIR__.'/auth.php';
