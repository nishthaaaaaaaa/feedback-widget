<?php

use App\Http\Controllers\FeedbackControllerAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/feedback', [FeedbackControllerAPI::class, 'add']);
Route::get('/showFeedback', [FeedbackControllerAPI::class, 'show']);
