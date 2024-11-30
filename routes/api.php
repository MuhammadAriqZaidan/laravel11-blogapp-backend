<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Laravel\Sanctum\Sanctum;


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // User
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Post
    Route::get('/posts', [PostController::class, 'index']); //all post
    Route::post('/posts', [PostController::class, 'store']); //create post
    Route::get('/posts/{id}', [PostController::class, 'show']); //get single post
    Route::put('/posts/{id}', [PostController::class, 'update']); //update post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); //delete post

    // Comment
    Route::get('/posts/{id}/comments', [CommentController::class, 'index']); // all comment
    Route::post('/posts/{id}/comments', [CommentController::class, 'store']); //create comment
    Route::put('/comments/{id}', [CommentController::class, 'update']);//update comment
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);//delete comment

    // Like
    Route::post('/posts/{id}/likes', [LikeController::class, 'likeOrUnlike']); // like or dislike post

});
