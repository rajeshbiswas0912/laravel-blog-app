<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect()->route('posts.index');
})->name('homepage');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Social Authentication Routes
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
    ->name('social.redirect');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback');

// Posts Routes (Public viewing, authenticated for CRUD)
Route::get('posts', [PostController::class, 'index'])->name('posts.index');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Home/Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Posts CRUD - create route must come before {post} route
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });

    // Comments Routes
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])
        ->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
});

// Posts show route - must come after create route to avoid route conflicts
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Admin Routes - Grouped with prefix and middleware
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Admin Posts Management (with route model binding)
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [AdminPostController::class, 'index'])->name('index');
        Route::get('/{post}', [AdminPostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [AdminPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [AdminPostController::class, 'update'])->name('update');
        Route::delete('/{post}', [AdminPostController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [AdminPostController::class, 'restore'])->name('restore');
    });

    // Admin Users Management (with route model binding)
    Route::resource('users', AdminUserController::class);
});
