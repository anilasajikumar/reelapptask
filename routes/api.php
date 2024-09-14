<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReelController;




Route::post('/reels/upload', [ReelController::class, 'upload']);
Route::get('/reels/{file_id}/play', [ReelController::class, 'play']);

