@extends('layouts.app')

@section('title', 'API Reference | ProGram')

@php
    $resources = [
        'Posts' => [
            ['method' => 'GET', 'endpoint' => '/api/post', 'access' => 'Public', 'description' => 'Get all posts'],
            [
                'method' => 'GET',
                'endpoint' => '/api/post/{id}',
                'access' => 'Public',
                'description' => 'Get a specific post by ID',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/post',
                'access' => 'Private',
                'description' => 'Create a new post',
            ],
            [
                'method' => 'PUT',
                'endpoint' => '/api/post/{id}',
                'access' => 'Private',
                'description' => 'Update a post by ID',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/post/{id}',
                'access' => 'Private',
                'description' => 'Delete a post by ID',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/post/{id}/like',
                'access' => 'Private',
                'description' => 'Get likes for a post',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/post/{id}/like',
                'access' => 'Private',
                'description' => 'Like a post',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/post/{id}/like',
                'access' => 'Private',
                'description' => 'Unlike a post',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/post/{id}/comment',
                'access' => 'Public',
                'description' => 'Get comments for a post',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/post/{id}/tags',
                'access' => 'Public',
                'description' => 'Get tags for a post',
            ],
        ],
        'Comments' => [
            [
                'method' => 'GET',
                'endpoint' => '/api/comment',
                'access' => 'Public',
                'description' => 'Get all comments',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/comment',
                'access' => 'Private',
                'description' => 'Create a new comment',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/comment/{id}',
                'access' => 'Public',
                'description' => 'Get a specific comment by ID',
            ],
            [
                'method' => 'PUT',
                'endpoint' => '/api/comment/{id}',
                'access' => 'Private',
                'description' => 'Update a comment by ID',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/comment/{id}',
                'access' => 'Private',
                'description' => 'Delete a comment by ID',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/comment/{id}/like',
                'access' => 'Private',
                'description' => 'Like a comment',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/comment/{id}/like',
                'access' => 'Private',
                'description' => 'Unlike a comment',
            ],
        ],
        'Groups' => [
            ['method' => 'GET', 'endpoint' => '/api/group', 'access' => 'Public', 'description' => 'Get all groups'],
            [
                'method' => 'POST',
                'endpoint' => '/api/group',
                'access' => 'Private',
                'description' => 'Create a new group',
            ],
            [
                'method' => 'PUT',
                'endpoint' => '/api/group/{id}',
                'access' => 'Private',
                'description' => 'Update a group by ID',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/group/{id}',
                'access' => 'Public',
                'description' => 'Get a specific group by ID',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/group/{id}/join',
                'access' => 'Private',
                'description' => 'Join a group',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/group/{id}/leave',
                'access' => 'Private',
                'description' => 'Leave a group',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/group/{id}/remove/{user_id}',
                'access' => 'Private',
                'description' => 'Remove a user from a group',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/group/{id}/request/{user_id}/accept',
                'access' => 'Private',
                'description' => 'Accept a group join request from a user',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/group/{id}/request/{user_id}/reject',
                'access' => 'Private',
                'description' => 'Reject a group join request from a user',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/group/{id}/invite/{user_id}',
                'access' => 'Private',
                'description' => 'Invite a user to join a group',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/group/{id}/invite/{user_id}',
                'access' => 'Private',
                'description' => 'Cancel an invite sent to a user for a group',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/group/{id}/acceptInvite',
                'access' => 'Private',
                'description' => 'Accept an invite to join a group',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/group/{id}/rejectInvite',
                'access' => 'Private',
                'description' => 'Reject an invite to join a group',
            ],
        ],
        'Users' => [
            ['method' => 'GET', 'endpoint' => '/api/user', 'access' => 'Private', 'description' => 'Get all users'],
            [
                'method' => 'GET',
                'endpoint' => '/api/user/{id}',
                'access' => 'Private',
                'description' => 'Get a specific user by ID',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/user',
                'access' => 'Private',
                'description' => 'Create a new user',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/user/{id}',
                'access' => 'Private',
                'description' => 'Delete a user',
            ],
            [
                'method' => 'PUT',
                'endpoint' => '/api/user/{id}',
                'access' => 'Private',
                'description' => 'Update a user by ID',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/user/{id}/followers',
                'access' => 'Private',
                'description' => 'List all followers of a user',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/user/{id}/following',
                'access' => 'Private',
                'description' => 'List all users the user is following',
            ],
            [
                'method' => 'GET',
                'endpoint' => '/api/user/{id}/post',
                'access' => 'Private',
                'description' => 'List all posts of a user',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/user/{id}/notifications/read',
                'access' => 'Private',
                'description' => 'Mark all notifications as read for a user',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/user/{userId}/notification/{notificationId}/read',
                'access' => 'Private',
                'description' => 'Mark a specific notification as read for a user',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/user/{id}/follow',
                'access' => 'Private',
                'description' => 'Follow a user',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/user/{id}/follow',
                'access' => 'Private',
                'description' => 'Unfollow a user',
            ],
            [
                'method' => 'DELETE',
                'endpoint' => '/api/follower/{id}',
                'access' => 'Private',
                'description' => 'Remove a follower',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/follow-request/{id}/accept',
                'access' => 'Private',
                'description' => 'Accept a follow request',
            ],
            [
                'method' => 'POST',
                'endpoint' => '/api/follow-request/{id}/reject',
                'access' => 'Private',
                'description' => 'Reject a follow request',
            ],
        ],
        'Files' => [
            [
                'method' => 'POST',
                'endpoint' => '/api/upload-file',
                'access' => 'Private',
                'description' => 'Upload a file',
            ],
        ],
    ];
@endphp


@section('content')
    <main id="api-reference-page" class="px-8 py-4 grid grid-cols-4 gap-6">
        <section class="card h-min col-span-4 flex flex-col gap-2">
            <h1 class="text-2xl font-bold">API Reference</h1>
            <p>
                ProGram provides a RESTful API for developers to interact with the platform programmatically. The API is
                secured with access tokens to access protected resources.
            </p>
            <article class="grid grid-cols-1 gap-3">
                <div class="card">
                    <h2 class="text-lg font-bold">Authentication</h2>
                    <p>
                        Public endpoints can be access by any third party. However, to get access to privileged resources,
                        you need to pass an access token on each request.
                        @auth You can generate a token in the <a href="{{ route('user.token', auth()->id()) }}"
                            class="text-blue-600 dark:text-blue-400">user settings</a>. @endauth
                    </p>
                    <p>After receiving a token, you can use it to perform <span class="font-semibold">Bearer
                            Authentication</span>, passing the token on the <code>Authorization</code> header.</p>
                    <div class="ql-code-block-container my-2">
                        curl -X GET "http://localhost:8001/api/post/1/like" \<br>
                        &ensp;&ensp;&ensp;&ensp;-H "Authorization: Bearer
                        {{ '<' }}ACCESS_TOKEN{{ '>' }}"
                    </div>
                </div>
                <article class="card">
                    <h2 class="text-lg font-bold mb-2">Endpoints</h2>
                    @foreach ($resources as $resource => $endpoints)
                        <div class="mb-4">
                            <h3 class="text-md font-semibold mb-1">{{ $resource }}</h3>
                            <div class="overflow-x-auto">
                                <table>
                                    <tr>
                                        <th class="text-left p-2 w-1/12">Method</th>
                                        <th class="text-left p-2 w-5/12">Endpoint</th>
                                        <th class="text-left p-2 w-2/12">Access</th>
                                        <th class="text-left p-2 w-4/12">Description</th>
                                    </tr>
                                    @foreach ($endpoints as $endpoint)
                                        <tr>
                                            <td>{{ $endpoint['method'] }}</td>
                                            <td><code>{{ $endpoint['endpoint'] }}</code></td>
                                            <td>{{ $endpoint['access'] }}</td>
                                            <td>{{ $endpoint['description'] }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @endforeach
                </article>

            </article>
        </section>
    </main>
@endsection
