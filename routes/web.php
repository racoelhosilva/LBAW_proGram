<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Api\ApiCommentController;
use App\Http\Controllers\Api\ApiFileController;
use App\Http\Controllers\Api\ApiGroupController;
use App\Http\Controllers\Api\ApiPostController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\GitLabController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TokenController;
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

    Route::view('/about', 'pages.about')->name('about');
    Route::view('/faqs', 'pages.faqs')->name('faqs');
    Route::view('/contactus', 'pages.contact-us')->name('contact-us');
    Route::view('/apireference', 'pages.api-reference')->name('api-reference');

    // Post
    Route::controller(PostController::class)->group(function () {
        Route::post('/post', 'store')->name('post.store')->middleware('throttle:calmdown');
        Route::get('/post/create', 'create')->name('post.create');
        Route::get('/post/{id}', 'show')->where('id', '[0-9]+')->name('post.show');
        Route::put('/post/{id}', 'update')->where('id', '[0-9]+')->name('post.update');
        Route::delete('/post/{id}', 'destroy')->where('id', '[0-9]+')->name('post.destroy');
        Route::get('/post/{id}/edit', 'edit')->where('id', '[0-9]+')->name('post.edit');
        Route::post('/group/{groupId}/post', 'store')->where('groupId', '[0-9]+')->name('group.post.store');
    });

    // User
    Route::controller(UserController::class)->group(function () {
        Route::get('/user/{id}', 'show')->where('id', '[0-9]+')->name('user.show');
        Route::put('/user/{id}', 'update')->where('id', '[0-9]+')->name('user.update');
        Route::delete('/user/{id}', 'destroy')->where('id', '[0-9]+')->name('user.destroy');
        Route::get('/user/{id}/edit', 'edit')->where('id', '[0-9]+')->name('user.edit');
        Route::get('/user/{id}/notifications', 'notifications')->where('id', '[0-9]+')->name('user.notifications');
        Route::get('/user/{id}/followers', 'followers')->where('id', '[0-9]+')->name('user.followers');
        Route::get('/user/{id}/following', 'following')->where('id', '[0-9]+')->name('user.following');
        Route::get('/user/{id}/requests', 'requests')->where('id', '[0-9]+')->name('user.requests');
        Route::get('/user/{id}/groups', 'showGroups')->where('id', '[0-9]+')->name('user.groups');
        Route::get('/user/{id}/invites', 'showInvites')->where('id', '[0-9]+')->name('user.invites');
        Route::get('/user/{id}/token', 'showTokenSettings')->where('id', '[0-9]+')->name('user.token');
    });

    // Group
    Route::controller(GroupController::class)->group(function () {
        Route::post('/group', 'store')->name('group.store')->middleware('throttle:calmdown');
        Route::get('/group/create', 'create')->name('group.create');
        Route::get('/group/{id}', 'show')->where('id', '[0-9]+')->name('group.show');
        Route::get('/group/{id}/members', 'showMembers')->where('id', '[0-9]+')->name('group.members');
        Route::put('/group/{id}', 'update')->where('id', '[0-9]+')->name('group.update');
        Route::delete('/group/{id}', 'destroy')->where('id', '[0-9]+')->name('group.destroy');
        Route::get('/group/{id}/edit', 'edit')->where('id', '[0-9]+')->name('group.edit');
        Route::get('/group/{id}/manage', 'manage')->where('id', '[0-9]+')->name('group.manage');
        Route::get('/group/{id}/members', 'showMembers')->where('id', '[0-9]+')->name('group.members');
        Route::get('/group/{id}/requests', 'showRequests')->where('id', '[0-9]+')->name('group.requests');
        Route::get('/group/{id}/invites', 'showInvites')->where('id', '[0-9]+')->name('group.invites');
        Route::post('/group/{id}/join', 'join')->where('id', '[0-9]+')->name('group.join');
        Route::post('/group/{id}/leave', 'leave')->where('id', '[0-9]+')->name('group.leave');
    });

    //Group Post
    Route::get('/group/{groupId}/post/create', 'GroupPostController@showCreatePostForm')->where('groupId', '[0-9]+')->name('group.post.create');

    // Search
    Route::get('/search', 'SearchController@index')->name('search');

    // Token
    Route::controller(TokenController::class)->group(function () {
        Route::post('/token', 'store')->name('token.store');
        Route::delete('/token/{id}', 'destroy')->where('id', '[0-9]+')->name('token.destroy');
    });
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
        Route::get('/', 'DashboardController@show')->name('admin.dashboard');

        // Admin users
        Route::get('/user', 'AdminUserController@index')->name('admin.user.index');
        Route::post('/user/{id}/ban', 'AdminUserController@banUser')->where('id', '[0-9]+')->name('admin.user.ban');
        Route::delete('/user/{id}', 'AdminUserController@deleteUser')->where('id', '[0-9]+')->name('admin.user.destroy');

        // Admin bans
        Route::get('/ban', 'AdminBanController@index')->name('admin.ban.index');
        Route::post('/ban/{id}/revoke', 'AdminBanController@irevoke')->where('id', '[0-9]+')->name('admin.ban.revoke');

        // Admin posts
        Route::get('/post', 'AdminPostController@index')->name('admin.post.index');
        Route::delete('/post/{id}', 'AdminPostController@destroy')->where('id', '[0-9]+')->name('admin.post.destroy');

        // Tags
        Route::get('/tags', 'AdminTagController@index')->name('admin.tag.index');
        Route::post('/tags', 'AdminTagController@store')->name('admin.tag.store');
        Route::delete('/tags/{id}', 'AdminTagController@destroy')->where('id', '[0-9]+')->name('admin.tag.destroy');

        // Languages
        Route::get('/technologies', 'AdminTechnologyController@index')->name('admin.technology.index');
        Route::post('/technologies', 'AdminTechnologyController@store')->name('admin.technology.store');
        Route::delete('/technologies/{id}', 'AdminTechnologyController@destroy')->where('id', '[0-9]+')->name('admin.technology.destroy');

        // Technologies
        Route::get('/languages', 'AdminLanguageController@index')->name('admin.language.index');
        Route::post('/languages', 'AdminLanguageController@store')->name('admin.language.store');
        Route::delete('/languages/{id}', 'AdminLanguageController@destroy')->where('id', '[0-9]+')->name('admin.language.destroy');
    });
});

// API
Route::prefix('api')->middleware('api.token')->group(function () {
    // Post
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
    });

    // Comment
    Route::controller(ApiCommentController::class)->group(function () {
        Route::get('/comment', 'index')->name('api.comment.index');
        Route::post('/comment', 'store')->name('api.comment.store');
        Route::get('/comment/{id}', 'show')->where('id', '[0-9]+')->name('api.comment.show');
        Route::put('/comment/{id}', 'update')->where('id', '[0-9]+')->name('api.comment.update');
        Route::delete('/comment/{id}', 'destroy')->where('id', '[0-9]+')->name('api.comment.destroy');
        Route::post('/comment/{id}/like', 'like')->where('id', '[0-9]+')->name('api.comment.like');
        Route::delete('/comment/{id}/like', 'unlike')->where('id', '[0-9]+')->name('api.comment.unlike');
    });

    // Group

    Route::controller(ApiGroupController::class)->group(function () {
        Route::get('/group', 'index')->name('api.group.index');
        Route::post('/group', 'store')->name('api.group.store');
        Route::put('/group/{id}', 'update')->where('id', '[0-9]+')->name('api.group.update');
        Route::get('/group/{id}', 'show')->where('id', '[0-9]+')->name('api.group.show');
        Route::post('/group/{id}/join', 'join')->where('id', '[0-9]+')->name('api.group.join');
        Route::delete('/group/{id}/leave', 'leave')->where('id', '[0-9]+')->name('api.group.leave');
        Route::delete('/group/{id}/remove/{user_id}', 'remove')->where(['id' => '[0-9]+', 'user_id' => '[0-9]+'])->name('api.group.remove');
        Route::post('/group/{id}/request/{user_id}/accept', 'acceptRequest')->where(['id' => '[0-9]+', 'user_id' => '[0-9]+'])->name('api.group.request.accept');
        Route::delete('/group/{id}/request/{user_id}/reject', 'rejectRequest')->where(['id' => '[0-9]+', 'user_id' => '[0-9]+'])->name('api.group.request.reject');
        Route::post('/group/{id}/invite/{user_id}', 'invite')->where(['id' => '[0-9]+', 'user_id' => '[0-9]+'])->name('api.group.invite');
        Route::delete('/group/{id}/invite/{user_id}', 'uninvite')->where(['id' => '[0-9]+', 'user_id' => '[0-9]+'])->name('api.group.uninvite');
        Route::post('/group/{id}/acceptinvite', 'acceptInvite')->where('id', '[0-9]+')->name('api.group.invite.accept');
        Route::delete('/group/{id}/rejectinvite', 'rejectInvite')->where('id', '[0-9]+')->name('api.group.invite.reject');
    });

    //User
    Route::controller(ApiUserController::class)->group(function () {
        Route::get('/user', 'index')->name('api.user.index');
        Route::get('/user/{id}', 'show')->where('id', '[0-9]+')->name('api.user.show');
        Route::post('/user', 'create')->where('id', '[0-9]+')->name('api.user.create');
        Route::delete('/user/{id}', 'delete')->where('id', '[0-9]+')->name('api.user.delete');
        Route::put('/user/{id}', 'update')->where('id', '[0-9]+')->name('api.user.update');
        Route::get('/user/{id}/followers', 'listFollowers')->where('id', '[0-9]+')->name('api.user.followers');
        Route::get('/user/{id}/following', 'listFollowing')->where('id', '[0-9]+')->name('api.user.following');
        Route::get('/user/{id}/post', 'listPosts')->where('id', '[0-9]+')->name('api.user.post');
        Route::post('/user/{id}/notifications/read', 'readAllNotifications')->where('id', '[0-9]+')->name('api.user.notifications.read');
        Route::post('/user/{userId}/notification/{notificationId}/read', 'readNotification')->where(['userId' => '[0-9]+', 'notificationId' => '[0-9]+'])->name('api.user.notification.read');
        Route::post('/user/{id}/follow', 'follow')->where('id', '[0-9]+')->name('api.user.follow');
        Route::delete('/user/{id}/follow', 'unfollow')->where('id', '[0-9]+')->name('api.user.unfollow');
        Route::delete('/follower/{id}', 'removeFollower')->where('id', '[0-9]+')->name('api.follower.remove');
        Route::post('/follow-request/{id}/accept', 'accept')->where('id', '[0-9]+')->name('api.follow-request.accept');
        Route::post('/follow-request/{id}/reject', 'reject')->where('id', '[0-9]+')->name('api.follow-request.reject');
    });

    Route::controller(ApiFileController::class)->group(function () {
        Route::post('/upload-file', 'uploadFile')->name('api.upload.file');
        Route::delete('/delete-file', 'deleteFile')->name('api.delete.file');
    });
});
