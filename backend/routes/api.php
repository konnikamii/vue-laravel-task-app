<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Open routes
Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/register/', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login/', [AuthController::class, 'login'])->name('auth.login');

Route::post('/contact/', [ContactController::class, 'createContact'])->name('contact.createContact');

// Secure routes
Route::middleware(['auth:api'])->group(function () {
    // User routes
    Route::post('/auth/', [UserController::class, 'auth'])->name('user.auth');
    Route::get('/user/', [UserController::class, 'getUserById'])->name('user.getUserById');
    Route::post('/users/', [UserController::class, 'getAllUsers'])->name('user.getAllUsers');
    Route::post('/change-password/', [UserController::class, 'changePassword'])->name('user.changePassword');
    // Task routes
    Route::get('/task/{task}', [TaskController::class, 'getTaskById'])->name('task.getTaskById');
    Route::post('/task/', [TaskController::class, 'createTask'])->name('task.createTask');
    Route::put('/task/{task}', [TaskController::class, 'updateTask'])->name('task.updateTask');
    Route::delete('/task/{task}', [TaskController::class, 'deleteTask'])->name('task.deleteTask');
    Route::post('/tasks/', [TaskController::class, 'getPaginatedTasks'])->name('task.getPaginatedTasks');
});

// Catch all routes
Route::match(['get', 'post', 'put', 'delete'], '{any?}', function () {
    return response()->json(['detail' => 'Route not found!'], 404);
});
