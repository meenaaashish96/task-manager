<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// All authenticated & verified user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard shows the tasks list
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Task CRUD routes (no separate create/show pages, form is on dashboard)
    Route::resource('tasks', TaskController::class)->except(['create', 'show']);
});

require __DIR__.'/auth.php';
