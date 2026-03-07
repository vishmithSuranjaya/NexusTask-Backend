<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\TaskController;

Route::middleware('auth:sanctum')->group(function () {
    // Fetch only trashed tasks
    Route::get('/tasks/trash', [TaskController::class, 'trash']);
    // Restore a specific task
    Route::put('/tasks/{id}/restore', [TaskController::class, 'restore']);
    // Registers GET /tasks, POST /tasks, DELETE /tasks/{id}, etc.
    Route::apiResource('tasks', TaskController::class);
});

// This route is for the registration
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Issue a token for the React app
    return response()->json([
        'token' => $user->createToken('auth_token')->plainTextToken,
        'user' => $user
    ], 201);
});

// this is route is for login process
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    // Check if user exists and password is correct
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'The provided credentials are incorrect.'
        ], 401);
    }

    // Create a new token for this session
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
});