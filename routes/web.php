<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [WebController::class, 'test']);

Route::get('/', [WebController::class, 'main'])->name('index');
Route::post('/save', [WebController::class, 'saveSlot']);
Route::get('/check', [WebController::class, 'check']);
Route::post('/search', [WebController::class, 'search']);

Route::get('/admin', [WebController::class, 'admin']);
