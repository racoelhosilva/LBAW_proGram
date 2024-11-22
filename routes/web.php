<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PostController;
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

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});

// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});

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

Route::controller(PostController::class)->group(function () {
    Route::get('/api/post', 'list');
    Route::get('/api/post/{id}', 'show');
    Route::post('/api/post', 'create');
    Route::delete('/api/post/{id}', 'delete');
    Route::put('/api/post/{id}', 'update');
    Route::get('/api/post/{id}/comment', 'listComments');
    Route::get('/api/post/{id}/likes', 'listLikes');
    Route::get('/api/post/{id}/tags', 'listTags');
    Route::get('/api/post/{id}/attachments', 'listAttachments');
});

Route::controller(CommentController::class)->group(function () {
    Route::get('/api/comment', 'list');
    Route::get('/api/comment/{id}', 'show');
    Route::post('/api/comment', 'create');
    //Route::delete('/api/comment/{id}', 'delete');
    Route::put('/api/comment/{id}', 'update');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/api/user', 'list');
    Route::get('/api/user/{id}', 'show');
    Route::post('/api/user', 'create');
    //Route::delete('/api/user/{id}', 'delete');
    Route::put('/api/user/{id}', 'update');
    Route::get('/api/user/{id}/followers', 'listFollowers');
    Route::get('/api/user/{id}/following', 'listFollowing');
    Route::get('/api/user/{id}/post', 'listPosts');
    Route::get('/api/user/{id}/userstats', 'listUserStats');
    Route::get('/api/user/{id}/followrequests', 'listFollowRequests');
});
