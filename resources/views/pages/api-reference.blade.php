@extends('layouts.app')

@section('title', 'API Reference | ProGram')

@php
    $endpoints = [
        ['method' => 'GET', 'endpoint' => '/api/posts', 'access' => 'Public', 'description' => 'Get all posts'],
        ['method' => 'GET', 'endpoint' => '/api/posts/{id}', 'access' => 'Public', 'description' => 'Get a specific post by ID'],
        ['method' => 'POST', 'endpoint' => '/api/posts', 'access' => 'Private', 'description' => 'Create a new post'],
        ['method' => 'PUT', 'endpoint' => '/api/posts/{id}', 'access' => 'Private', 'description' => 'Update a post by ID'],
        ['method' => 'DELETE', 'endpoint' => '/api/posts/{id}', 'access' => 'Private', 'description' => 'Delete a post by ID'],
    ];
@endphp

@section('content')
    <main id="api-reference-page" class="px-8 py-4 grid grid-cols-4 gap-6">
        <section class="card h-min col-span-4 flex flex-col gap-2">
            <h1 class="text-2xl font-bold">API Reference</h1>
            <p>
                ProGram provides a RESTful API for developers to interact with the platform programmatically. The API is secured with access tokens to access protected resources.
            </p>
            <article class="grid grid-cols-1 gap-3">
                <div class="card">
                    <h2 class="text-lg font-bold">Authentication</h2>
                    <p>
                        Public endpoints can be access by any third party. However, to get access to privileged resources, you need to pass an access token on each request
                        @auth You can generate a token in the <a href="{{ route('user.token', auth()->id()) }}" class="text-blue-600 dark:text-blue-400">user settings</a>. @endauth
                    </p>
                    <p>After receiving a token, you can use it to perform <span class="font-semibold">Bearer Authentication</span>, passing the token on the <code>Authorization</code> header.</p>
                    <div class="ql-code-block-container my-2">
                        curl -X GET "https://localhost:8001/api/post/1/like" \<br>
                        &ensp;&ensp;&ensp;&ensp;-H "Authorization: Bearer {{ '<' }}ACCESS_TOKEN{{ '>' }}"
                    </div>
                </div>
                <article class="card">
                    <h2 class="text-lg font-bold mb-2">Endpoints</h2>
                    <div class="overflow-x-auto">
                        <table>
                            <tr>
                                <th>Method</th>
                                <th>Endpoint</th>
                                <th>Access</th>
                                <th>Description</th>
                            </tr>
                            @foreach($endpoints as $endpoint)
                                <tr>
                                    <td>{{ $endpoint['method'] }}</td>
                                    <td ><code>{{ $endpoint['endpoint'] }}</code></td>
                                    <td>{{ $endpoint['access'] }}</td>
                                    <td>{{ $endpoint['description'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </article>
            </article>
        </section>
    </main>
@endsection