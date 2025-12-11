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
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

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
    Route::get('posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::get('posts/{post}', [AdminPostController::class, 'show'])->name('posts.show');
    Route::get('posts/{post}/edit', [AdminPostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [AdminPostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
    Route::post('posts/{id}/restore', [AdminPostController::class, 'restore'])->name('posts.restore');

    // Admin Users Management (with route model binding)
    Route::resource('users', AdminUserController::class);
});
