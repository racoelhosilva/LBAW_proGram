<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBanController;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminTagController;
use App\Http\Controllers\Admin\AdminTechnologyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\ApiCommentController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\GitLabController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
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

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'show')->name('register');
    Route::post('/register', 'register');
});

Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirect')->name('google.auth');
    Route::get('auth/google/call-back', 'callbackGoogle')->name('google.callback');
});

Route::controller(GitHubController::class)->group(function () {
    Route::get('auth/github', 'redirect')->name('github.auth');
    Route::get('auth/github/call-back', 'callbackGitHub')->name('github.callback');
});

Route::controller(GitLabController::class)->group(function () {
    Route::get('auth/gitlab', 'redirect')->name('gitlab.auth');
    Route::get('auth/gitlab/call-back', 'callbackGitLab')->name('gitlab.callback');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'show')->name('forgot-password');
    Route::post('/forgot-password', 'forgotPassword');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
});

Route::post('/sendemail', [MailController::class, 'send']);

Route::middleware(['deny.banned', 'deny.deleted'])->group(function () {
    // Home
    Route::get('/', [HomeController::class, 'show'])->name('home');

    Route::get('/about', function () {
        return view('pages.about');
    })->name('about');

    Route::get('faqs', function () {
        return view('pages.faqs');
    })->name('faqs');

    Route::get('/contactus', function () {
        return view('pages.contactus');
    })->name('contactus');

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
        Route::put('/user/{id}', 'update')->where('id', '[0-9]+')->name('user.update');
        Route::get('/user/{id}/edit', 'edit')->where('id', '[0-9]+')->name('user.edit');
        Route::get('/user/{id}/notifications', 'notifications')->where('id', '[0-9]+')->name('user.notifications');
        Route::get('/user/{id}/followers', 'followers')->where('id', '[0-9]+')->name('user.followers');
        Route::get('/user/{id}/following', 'following')->where('id', '[0-9]+')->name('user.following');
        Route::get('/user/{id}/requests', 'requests')->where('id', '[0-9]+')->name('user.requests');
        Route::delete('/user/{id}', 'destroy')->where('id', '[0-9]+')->name('user.destroy');
    });

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});

// Admin
Route::prefix('admin')->group(function () {
    // Admin authentication
    Route::controller(AdminAuthController::class)->group(function () {
        Route::get('/login', 'show')->name('admin.login');
        Route::post('/login', 'authenticate');
        Route::post('/logout', 'logout')->name('admin.logout');
    });

    Route::middleware('auth.admin')->group(function () {
        // Admin dashboard
        Route::get('/', [DashboardController::class, 'show'])->name('admin.dashboard');

        // Admin users
        Route::get('/user', [AdminUserController::class, 'index'])->name('admin.user.index');
        Route::post('/user/{id}/ban', [AdminUserController::class, 'banUser'])->where('id', '[0-9]+')->name('admin.user.ban');
        Route::delete('/user/{id}', [AdminUserController::class, 'deleteUser'])->where('id', '[0-9]+')->name('admin.user.destroy');

        // Admin bans
        Route::get('/ban', [AdminBanController::class, 'index'])->name('admin.ban.index');
        Route::post('/ban/{id}/revoke', [AdminBanController::class, 'revoke'])->where('id', '[0-9]+')->name('admin.ban.revoke');

        // Admin posts
        Route::get('/post', [AdminPostController::class, 'index'])->name('admin.post.index');

        // Tags
        Route::get('/tags', [AdminTagController::class, 'index'])->name('admin.tag.index');
        Route::post('/tags', [AdminTagController::class, 'store'])->name('admin.tag.store');
        Route::delete('/tags/{id}', [AdminTagController::class, 'destroy'])->where('id', '[0-9]+')->name('admin.tag.destroy');

        // Languages
        Route::get('/technologies', [AdminTechnologyController::class, 'index'])->name('admin.technology.index');
        Route::post('/technologies', [AdminTechnologyController::class, 'store'])->name('admin.technology.store');
        Route::delete('/technologies/{id}', [AdminTechnologyController::class, 'destroy'])->where('id', '[0-9]+')->name('admin.technology.destroy');

        // Technologies
        Route::get('/languages', [AdminLanguageController::class, 'index'])->name('admin.language.index');
        Route::post('/languages', [AdminLanguageController::class, 'store'])->name('admin.language.store');
        Route::delete('/languages/{id}', [AdminLanguageController::class, 'destroy'])->where('id', '[0-9]+')->name('admin.language.destroy');
    });
});

// API
Route::prefix('api')->group(function () {
    // Post
    Route::controller(ApiPostController::class)->group(function () {
        // Route::get('/post', 'index')->name('api.post.index');
        // Route::post('/post', 'store')->name('api.post.store');
        // Route::get('/post/{id}', 'show')->where('id', '[0-9]+')->name('api.post.show');
        // Route::put('/post/{id}', 'update')->where('id', '[0-9]+')->name('api.post.update');
        // Route::delete('/post/{id}', 'destroy')->where('id', '[0-9]+')->name('api.post.destroy');
        // Route::get('/post/{id}/like', 'indexLikes')->where('id', '[0-9]+')->name('api.post.like.index');
        Route::post('/post/{id}/like', 'like')->where('id', '[0-9]+')->name('api.post.like');
        Route::delete('/post/{id}/like', 'unlike')->where('id', '[0-9]+')->name('api.post.unlike');
        // Route::get('/post/{id}/comment', 'indexComments')->where('id', '[0-9]+')->name('api.post.comment.index');
        // Route::get('/post/{id}/tags', 'indexTags')->where('id', '[0-9]+')->name('api.post.tags.index');
        // Route::get('/post/{id}/attachments', 'indexAttachments')->where('id', '[0-9]+')->name('api.post.attachments.index');
    });

    // Comment
    Route::controller(ApiCommentController::class)->group(function () {
        // Route::get('/comment', 'index')->name('api.comment.index');
        Route::post('/comment', 'store')->name('api.comment.store');
        // Route::get('/comment/{id}', 'show')->where('id', '[0-9]+')->name('api.comment.show');
        Route::patch('/comment/{id}', 'update')->where('id', '[0-9]+')->name('api.comment.update');
        Route::delete('/comment/{id}', 'destroy')->where('id', '[0-9]+')->name('api.comment.destroy');
        Route::post('/comment/{id}/like', 'like')->where('id', '[0-9]+')->name('api.comment.like');
        Route::delete('/comment/{id}/like', 'unlike')->where('id', '[0-9]+')->name('api.comment.unlike');
    });

    // User
    Route::controller(ApiUserController::class)->group(function () {
        //     Route::get('/user', 'list');
        //     Route::get('/user/{id}', 'show');
        //     Route::post('/user', 'create');
        //     Route::delete('/user/{id}', 'delete');
        //     Route::put('/user/{id}', 'update');
        //     Route::get('/user/{id}/followers', 'listFollowers');
        //     Route::get('/user/{id}/following', 'listFollowing');
        //     Route::get('/user/{id}/post', 'listPosts');
        Route::post('/user/{id}/notifications/read', 'readAllNotifications')->where('id', '[0-9]+')->name('api.user.notifications.read');
        Route::post('/user/{userId}/notification/{notificationId}/read', 'readNotification')->where('userId', '[0-9]+')->where('notificationId', '[0-9]+')->name('api.user.notification.read');
        Route::post('/user/{id}/follow', 'follow')->where('id', '[0-9]+')->name('api.user.follow');
        Route::delete('/user/{id}/follow', 'unfollow')->where('id', '[0-9]+')->name('api.user.unfollow');
        Route::delete('/follower/{id}', 'removeFollower')->where('id', '[0-9]+')->name('api.follower.remove');
        Route::post('/follow-request/{id}/accept', 'accept')->where('id', '[0-9]+')->name('api.follow-request.accept');
        Route::post('/follow-request/{id}/reject', 'reject')->where('id', '[0-9]+')->name('api.follow-request.reject');
    });
});
