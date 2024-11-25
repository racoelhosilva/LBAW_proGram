<?php

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
    Route::get('/post/create', 'create')->name('post.create');
    Route::get('/post/{post}', 'show')->where('post', '[0-9]+')->name('post.show');
    Route::post('/post', 'store')->name('post.store');
    Route::get('/post/{post}/edit', 'edit')->where('post', '[0-9]+')->name('post.edit');
    Route::put('/post/{post}', 'update')->where('post', '[0-9]+')->name('post.update');
    Route::delete('/post/{post}', 'destroy')->where('post', '[0-9]+')->name('post.destroy');
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

// API
Route::prefix('api')->group(function () {
    Route::controller(ApiPostController::class)->group(function () {
        Route::get('/post', 'list');
        Route::get('/post/{id}', 'show');
        Route::post('/post', 'create');
        Route::delete('/post/{id}', 'delete');
        Route::put('/post/{id}', 'update');
        Route::get('/post/{id}/comment', 'listComments');
        Route::get('/post/{id}/like', 'listLikes');
        Route::post('/post/{id}/like', 'like')->where('id', '[0-9]+')->name('api.post.like');
        Route::delete('/post/{id}/like', 'dislike')->where('id', '[0-9]+')->name('api.post.dislike');
        Route::get('/post/{id}/tags', 'listTags');
        Route::get('/post/{id}/attachments', 'listAttachments');
    });

    Route::controller(ApiCommentController::class)->group(function () {
        Route::get('/comment', 'list');
        Route::get('/comment/{id}', 'show');
        Route::post('/comment', 'create');
        //Route::delete('/comment/{id}', 'delete');
        Route::post('/comment/{id}/like', 'like')->where('id', '[0-9]+')->name('api.comment.like');
        Route::delete('/comment/{id}/like', 'dislike')->where('id', '[0-9]+')->name('api.comment.dislike');
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
