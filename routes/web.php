<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Models\Post;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $posts = Post::with(['user', 'comments', 'likes', 'comments.replies', 'likes.user'])->get();;
    return view('dashboard', compact('posts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::patch('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/replies', [CommentController::class, 'storeReply'])->name('comments.reply');
    Route::get('/comments/{id}', [CommentController::class, 'show'])->name('comments.index');
    Route::post('/likes/toggle', [LikeController::class, 'toggleLike'])->name('likes.toggle');
    Route::post('/posts/show/{id}', [PostController::class, 'show'])->name('posts.show');
});

require __DIR__ . '/auth.php';
