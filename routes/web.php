<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RepostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;

// Default redirect to profile
Route::get('/', function () {
    return redirect()->route('profile.index');
});

// Guest-only Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Post interaction routes (like, comment, repost)
Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('posts.like');
Route::post('/posts/{post}/comment', [CommentController::class, 'store'])->name('posts.comment');
Route::post('/posts/{post}/repost', [RepostController::class, 'store'])->name('posts.repost');


// Authenticated User Routes
Route::middleware('auth')->group(function () {

    // Dashboard and Post creation
    Route::get('/user/dashboard', [PostController::class, 'dashboard'])->name('dashboard');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/update', [PostController::class, 'update'])->name('posts.update');

    // Post hide/unhide and delete
    Route::post('/posts/{post}/toggle-hidden', [PostController::class, 'toggleHidden'])->name('posts.toggleHidden');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');

        Route::get('account-info', [ProfileController::class, 'accountInfo'])->name('profile.account-info');
        Route::post('photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
        Route::post('about-me/update', [ProfileController::class, 'updateAboutMe'])->name('profile.about_me.update');

        Route::get('form-responses', [ProfileController::class, 'formResponses'])->name('profile.form-responses');
        Route::put('form-responses/update', [ProfileController::class, 'updateFormResponses'])->name('profile.form-responses.update');

        Route::get('my-posts', [ProfileController::class, 'myPosts'])->name('profile.my-posts');
        Route::get('my-comments', [ProfileController::class, 'myComments'])->name('profile.my-comments');
        Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('hidden-posts', [ProfileController::class, 'hiddenPosts'])->name('profile.hidden-posts');
    });
});
