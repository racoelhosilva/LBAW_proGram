<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CommentController;
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
Route::view('/', 'home')->name('home');
Route::redirect('/', '/login');

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
    Route::get('/api/posts', 'list');
    Route::get('/api/posts/{id}', 'show');
    Route::post('/api/posts', 'create');
    //Route::delete('/api/posts/{id}', 'delete');
    Route::put('/api/posts/{id}', 'update');
    Route::get('/api/posts/{id}/comments', 'listComments');
    Route::get('/api/posts/{id}/likes', 'listLikes');
    Route::get('/api/posts/{id}/tags', 'listTags');
    Route::get('/api/posts/{id}/attachments', 'listAttachments');
});

Route::controller(CommentController::class)->group(function () {
    Route::get('/api/comments', 'list');
    Route::get('/api/comments/{id}', 'show');
    Route::post('/api/comments', 'create');
    //Route::delete('/api/comments/{id}', 'delete');
    Route::put('/api/comments/{id}', 'update');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/api/users', 'list');
    Route::get('/api/users/{id}', 'show');
    Route::post('/api/users', 'create');
    //Route::delete('/api/users/{id}', 'delete');
    Route::put('/api/users/{id}', 'update');
    Route::get('/api/users/{id}/followers', 'listFollowers');
    Route::get('/api/users/{id}/following', 'listFollowing');
    Route::get('/api/users/{id}/posts', 'listPosts');
    Route::get('/api/users/{id}/userstats', 'listUserStats');
    Route::get('/api/users/{id}/followrequests', 'listFollowRequests');
});
