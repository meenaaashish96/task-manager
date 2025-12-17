<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public login route to get token (Sanctum)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $request->user()->createToken('api_token')->plainTextToken;
    return response()->json(['token' => $token]);
});

// Protected Routes - API routes with 'api.' prefix to avoid conflicts with web routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class)->names([
        'index' => 'api.tasks.index',
        'store' => 'api.tasks.store',
        'show' => 'api.tasks.show',
        'update' => 'api.tasks.update',
        'destroy' => 'api.tasks.destroy',
    ]);
});