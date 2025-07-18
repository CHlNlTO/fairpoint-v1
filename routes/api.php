<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BlogPostController;

Route::get('v1/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working!',
        'timestamp' => now(),
        'path' => request()->path()
    ]);
});

// Blog posts API routes
Route::prefix('v1')->group(function () {
    Route::get('posts', [BlogPostController::class, 'index']);
    Route::get('posts/{id}', [BlogPostController::class, 'show']);
});
