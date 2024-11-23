<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\ApiCommentController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
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
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'show')->name('home');
});

// Post
Route::controller(PostController::class)->group(function () {
    Route::get('/post/{post}', 'show');
});

// Search
Route::get('/search', [SearchController::class, 'list'])->name('search');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Admin
Route::controller(AuthController::class)->group(function () {
    Route::get('/admin/login', 'showLoginForm')->name('admin.login');
    Route::post('/admin/login', 'authenticate');
    Route::get('/admin/logout', 'logout')->name('admin.logout');
});

Route::middleware('auth.admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/admin', 'index')->name('admin.dashboard');
    });
});

// API
Route::prefix('api')->group(function () {
    Route::controller(ApiPostController::class)->group(function () {
        Route::get('/post', 'list');
        Route::get('/post/{id}', 'show');
        Route::post('/post', 'create');
        Route::delete('/post/{id}', 'delete');
        Route::put('/post/{id}', 'update');
        Route::get('/post/{id}/comment', 'listComments');
        Route::get('/post/{id}/likes', 'listLikes');
        Route::get('/post/{id}/tags', 'listTags');
        Route::get('/post/{id}/attachments', 'listAttachments');
    });

    Route::controller(ApiCommentController::class)->group(function () {
        Route::get('/comment', 'list');
        Route::get('/comment/{id}', 'show');
        Route::post('/comment', 'create');
        //Route::delete('/comment/{id}', 'delete');
        Route::put('/comment/{id}', 'update');
    });

    Route::controller(ApiUserController::class)->group(function () {
        Route::get('/user', 'list');
        Route::get('/user/{id}', 'show');
        Route::post('/user', 'create');
        //Route::delete('/user/{id}', 'delete');
        Route::put('/user/{id}', 'update');
        Route::get('/user/{id}/followers', 'listFollowers');
        Route::get('/user/{id}/following', 'listFollowing');
        Route::get('/user/{id}/post', 'listPosts');
        Route::get('/user/{id}/userstats', 'listUserStats');
        Route::get('/user/{id}/followrequests', 'listFollowRequests');
    });
});
Route::controller(UserController::class)->group(function () {
    Route::get('user/{id}', 'show');

});
