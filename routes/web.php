<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\ApiCommentController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
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

// Home
Route::get('/', [HomeController::class, 'show'])->name('home');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');  // TODO: Change to POST
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'show')->name('register');
    Route::post('/register', 'register');
});

// Post
Route::controller(PostController::class)->group(function () {
    Route::post('/post', 'store')->name('post.store');
    Route::get('/post/create', 'create')->name('post.create');
    Route::get('/post/{id}', 'show')->where('id', '[0-9]+')->name('post.show');
    Route::put('/post/{id}', 'update')->where('id', '[0-9]+')->name('post.update');
    Route::delete('/post/{id}', 'destroy')->where('id', '[0-9]+')->name('post.destroy');
    Route::get('/post/{id}/edit', 'edit')->where('id', '[0-9]+')->name('post.edit');
});

// User
Route::controller(UserController::class)->group(function () {
    Route::get('/user/{id}', 'show')->where('id', '[0-9]+')->name('user.show');
    Route::post('/user/{id}', 'update')->where('id', '[0-9]+')->name('user.update');
    Route::get('/user/{id}/edit', 'edit')->where('id', '[0-9]+')->name('user.edit');
});

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Admin
Route::prefix('admin')->group(function () {
    // Admin authentication
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'show')->name('admin.login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('admin.logout');
    });

    // Admin routes
    Route::middleware('auth.admin')->group(function () {  // TODO: Remove middleware
        Route::get('/', [DashboardController::class, 'show'])->name('admin.dashboard');

        Route::controller(AdminUserController::class)->group(function () {  // TODO: Separate into AdminUserController and AdminBanController and AdminPostController...
            Route::get('/user/search', 'searchUser')->name('admin.user.search');
            // Ban
            Route::get('/ban', 'searchBans')->name('admin.ban.search');
            Route::post('/user/{id}/ban', 'banUser')->where('id', '[0-9]+')->name('admin.user.ban');
            Route::post('/ban/{id}/revoke', 'revokeBan')->where('id', '[0-9]+')->name('admin.ban.revoke');
            // Posts
            Route::get('/post/search', 'searchPost')->name('admin.post.search');
        });
    });
});

// API
Route::prefix('api')->group(function () {
    Route::controller(ApiPostController::class)->group(function () {
        Route::get('/post', 'index')->name('api.post.index');
        Route::post('/post', 'store')->name('api.post.store');
        Route::get('/post/{id}', 'show')->where('id', '[0-9]+')->name('api.post.show');
        Route::put('/post/{id}', 'update')->where('id', '[0-9]+')->name('api.post.update');
        Route::delete('/post/{id}', 'destroy')->where('id', '[0-9]+')->name('api.post.destroy');
        Route::get('/post/{id}/like', 'indexLikes')->where('id', '[0-9]+')->name('api.post.like.index');
        Route::post('/post/{id}/like', 'like')->where('id', '[0-9]+')->name('api.post.like');
        Route::delete('/post/{id}/like', 'unlike')->where('id', '[0-9]+')->name('api.post.unlike');
        Route::get('/post/{id}/comment', 'indexComments')->where('id', '[0-9]+')->name('api.post.comment.index');
        Route::get('/post/{id}/tags', 'indexTags')->where('id', '[0-9]+')->name('api.post.tags.index');
        Route::get('/post/{id}/attachments', 'indexAttachments')->where('id', '[0-9]+')->name('api.post.attachments.index');
    });

    Route::controller(ApiCommentController::class)->group(function () {
        Route::get('/comment', 'index')->name('api.comment.index');
        Route::post('/comment', 'store')->name('api.comment.store');
        Route::get('/comment/{id}', 'show')->where('id', '[0-9]+')->name('api.comment.show');
        Route::put('/comment/{id}', 'update')->where('id', '[0-9]+')->name('api.comment.update');
        Route::delete('/comment/{id}', 'destroy')->where('id', '[0-9]+')->name('api.comment.destroy');
        Route::post('/comment/{id}/like', 'like')->where('id', '[0-9]+')->name('api.comment.like');
        Route::delete('/comment/{id}/like', 'unlike')->where('id', '[0-9]+')->name('api.comment.unlike');
    });

    // Route::controller(ApiUserController::class)->group(function () {
    //     Route::get('/user', 'list');
    //     Route::get('/user/{id}', 'show');
    //     Route::post('/user', 'create');
    //     Route::delete('/user/{id}', 'delete');
    //     Route::put('/user/{id}', 'update');
    //     Route::get('/user/{id}/followers', 'listFollowers');
    //     Route::get('/user/{id}/following', 'listFollowing');
    //     Route::get('/user/{id}/post', 'listPosts');
    //     Route::get('/user/{id}/userstats', 'listUserStats');
    //     Route::get('/user/{id}/followrequests', 'listFollowRequests');
    // });
});

// File Upload
Route::post('/file/upload', [FileController::class, 'upload'])->name('file.upload');
