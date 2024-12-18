@props(['userCount', 'bannedCount', 'postCount', 'tagCount', 'languageCount', 'technologyCount'])

@section('title')
    {{ 'Admin Dashboard | ProGram' }}
@endsection
@extends('layouts.admin')

@section('content')
    <main class="px-4">
        <h1 class="mb-4 text-2xl font-bold col-span-4">Dashboard</h1>

        <section class="grid grid-cols-4 gap-4">
            <article class="dashboard-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-user-round">
                    <circle cx="12" cy="8" r="5" />
                    <path d="M20 21a8 8 0 0 0-16 0" />
                </svg>
                <div class="w-full flex flex-col">
                    <h2 class="text-xl font-bold">Users</h2>
                    <p class="mb-4"><span class="font-semibold">User count:</span> {{ $userCount }}</p>
                    <div class="flex">
                        @include('partials.text-button', [
                            'text' => 'View Users',
                            'anchorUrl' => route('admin.user.index'),
                        ])
                    </div>
                </div>
            </article>

            <article class="dashboard-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-user-round-x">
                    <path d="M2 21a8 8 0 0 1 11.873-7" />
                    <circle cx="10" cy="8" r="5" />
                    <path d="m17 17 5 5" />
                    <path d="m22 17-5 5" />
                </svg>
                <div class="w-full flex flex-col">
                    <h2 class="text-xl font-bold">Bans</h2>
                    <p class="mb-4"><span class="font-semibold">Banned users:</span> {{ $bannedCount }}</p>
                    <div class="flex">
                        @include('partials.text-button', [
                            'text' => 'View Bans',
                            'anchorUrl' => route('admin.ban.index'),
                        ])
                    </div>
                </div>
            </article>

            <article class="dashboard-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-message-circle">
                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z" />
                </svg>
                <div class="w-full flex flex-col">
                    <h2 class="text-xl font-bold">Posts</h2>
                    <p class="mb-4"><span class="font-semibold">Post count:</span> {{ $postCount }}</p>
                    <div class="flex">
                        @include('partials.text-button', [
                            'text' => 'View Posts',
                            'anchorUrl' => route('admin.post.index'),
                        ])
                    </div>
                </div>
            </article>

            <article class="dashboard-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-hash">
                    <line x1="4" x2="20" y1="9" y2="9" />
                    <line x1="4" x2="20" y1="15" y2="15" />
                    <line x1="10" x2="8" y1="3" y2="21" />
                    <line x1="16" x2="14" y1="3" y2="21" />
                </svg>
                <div class="w-full flex flex-col">
                    <h2 class="text-xl font-bold">Tags</h2>
                    <p class="mb-4"><span class="font-semibold">Tags count:</span> {{ $tagCount }}</p>
                    <div class="flex">
                        @include('partials.text-button', [
                            'text' => 'View Tags',
                            'anchorUrl' => route('admin.tag.index'),
                        ])
                    </div>
                </div>
            </article>
            <article class="dashboard-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-code">
                    <polyline points="16 18 22 12 16 6" />
                    <polyline points="8 6 2 12 8 18" />
                </svg>
                <div class="w-full flex flex-col">
                    <h2 class="text-xl font-bold">Languages</h2>
                    <p class="mb-4"><span class="font-semibold">Languages count:</span> {{ $languageCount }}</p>
                    <div class="flex">
                        @include('partials.text-button', [
                            'text' => 'View Languages',
                            'anchorUrl' => route('admin.post.index'),
                        ])
                    </div>
                </div>
            </article>
            <article class="dashboard-card">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" class="lucide lucide-layers">
                    <path
                        d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z" />
                    <path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12" />
                    <path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17" />
                </svg>
                <div class="w-full flex flex-col">
                    <h2 class="text-xl font-bold">Technologies</h2>
                    <p class="mb-4"><span class="font-semibold">Technologies count:</span> {{ $technologyCount }}</p>
                    <div class="flex">
                        @include('partials.text-button', [
                            'text' => 'View Technologies',
                            'anchorUrl' => route('admin.post.index'),
                        ])
                    </div>
                </div>
            </article>
        </section>
    </main>
@endsection
