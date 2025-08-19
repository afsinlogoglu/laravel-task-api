<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

// API Routes
Route::prefix('api')->group(function () {
    // Authentication routes (public)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // User routes
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Team routes
        Route::post('/teams', [TeamController::class, 'store']);
        Route::get('/teams', [TeamController::class, 'index']);
        Route::post('/teams/{id}/members', [TeamController::class, 'addMember'])->middleware('team.member');
        Route::delete('/teams/{id}/members/{userId}', [TeamController::class, 'removeMember'])->middleware('team.member');
        
        // Task routes
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::put('/tasks/{id}', [TaskController::class, 'update'])->middleware('team.member');
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->middleware('team.member');
        Route::post('/tasks/{id}/files', [TaskController::class, 'uploadFile'])->middleware('team.member');
    });
});
