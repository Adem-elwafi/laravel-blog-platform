<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FriendController;


Route::get('/', [PostController::class, 'welcome'])->name('welcome');

// Dedicated friend-management page (Facebook-style)
Route::get('/friends', [FriendController::class, 'index'])
    ->middleware(['auth'])
    ->name('friends.index');

// /dashboard is kept as a named route so auth redirects keep working,
// but it now forwards straight to the friends page.
Route::get('/dashboard', fn () => redirect()->route('friends.index'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Authenticated actions on posts
    Route::resource('posts', PostController::class)->except(['index', 'show']);

    // Comments & Likes (auth + rate limit where applicable)
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->middleware(['rate.limit'])
        ->name('posts.comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])
        ->middleware(['rate.limit'])
        ->name('posts.like');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/appearance', [ProfileController::class, 'updateAppearance'])->name('profile.appearance');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public user profile
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');

// Public posts listing and detail (registered after auth routes so /posts/create matches the create route)
Route::resource('posts', PostController::class)->only(['index', 'show']);


Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware('admin')
    ->name('admin.dashboard');


require __DIR__.'/auth.php';
