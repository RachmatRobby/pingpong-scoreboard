<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('home');
Route::post('/start', [GameController::class, 'startGame'])->name('start.game');
Route::post('/point/{player}', [GameController::class, 'addPoint'])->name('add.point');
Route::get('/reset', [GameController::class, 'reset'])->name('game.reset');

// Fixed routes - make sure the path patterns match exactly
Route::get('/download/{filename}', [GameController::class, 'downloadGame'])->name('download.game')->where('filename', '.*');
Route::get('/view/{filename}', [GameController::class, 'viewGameHistory'])->name('view.game')->where('filename', '.*');