<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('guest');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::post('/feedback-add', [FeedbackController::class, 'add'])->name('feedback.add');
Route::post('/feedback-change', [FeedbackController::class, 'change'])->name('feedback.change');
Route::get('/feedback-filter', [FeedbackController::class, 'filter'])->name('feedback.filter');
Route::get('/feedback-download', [FeedbackController::class, 'download'])->name('feedback.download');
