@props(['user', 'posts', 'recommendedUsers', 'numRequests'])

@extends('layouts.app')

@section('title', $user->name . ' | ProGram')

@section('openGraph')
    <meta property="og:title" content="{{ $user->name }} | ProGram">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="profile">
    <meta property="profile:username" content="{{ $user->name }}">
@endsection

@php
    $canFollow = auth()->check() && auth()->user()->can('follow', $user);

    if ($canFollow) {
        if (auth()->user()->follows($user)) {
            $followClass = "following primary-btn";
        } elseif (auth()->user()->getFollowRequestStatus($user)) {
            $followClass = "pending secondary-btn";
        } else {
            $followClass = "unfollowing primary-btn";
        }
    }
@endphp

@section('content')
    <main id="profile-page" class="px-8 py-4 grid grid-cols-4 grid-rows-[auto_1fr] gap-x-6 gap-y-3 lg:gap-y-6">
        <section id="banner-section" style="background-image: url('{{ $user->getBannerImage() }}');"
            class="card h-min col-span-4 grid grid-cols-[auto_1fr_auto] gap-y-16 p-4 bg-cover">
            <div class="col-span-full">
                <h1 class="text-4xl font-bold">{{ $user->name }}</h1>
                <h2 class="text-2xl">{{ '@' . $user->handle }}</h2>
            </div>
            <img src="{{ $user->getProfilePicture() }}" alt="{{ $user->name }} Profile Picture" class="w-36 sm:w-52 h-36 sm:h-52 rounded-full object-cover">
            <div class="profile-buttons flex justify-end items-end">
                @can('update', $user)
                    @include('partials.text-button', [
                        'text' => 'Edit Profile',
                        'anchorUrl' => route('user.edit', auth()->id()),
                    ])
                @endcan
                @if ($canFollow)
                    <button aria-label="Follow Profile" class="px-4 py-3 text-center font-medium follow-profile-button {{ $followClass }}" data-user-id="{{ $user->id }}">
                        <span class="follow">Follow</span>
                        <span class="pending">Pending</span>
                        <span class="unfollow">Unfollow</span>
                    </button>
                @endif
            </div>
        </section>

        @can('viewContent', $user)
            <div id="profile-left" class="h-min col-span-4 lg:col-span-1 flex flex-col gap-3">
                <section id="user-info" class="card flex flex-col gap-3">
                    <div class="grid grid-cols-[auto_1fr_auto] items-start">
                        <h1 class="text-xl font-bold">User Info</h1>
                        <div class="col-start-3 flex">
                            @isset($user->stats->github_url)
                                @include('partials.icon-button', ['iconName' => 'github', 'label' => 'GitHub', 'type' => 'transparent', 'anchorUrl' => $user->stats->github_url])
                            @endisset
                            @isset($user->stats->gitlab_url)
                                @include('partials.icon-button', ['iconName' => 'gitlab', 'label' => 'GitLab', 'type' => 'transparent', 'anchorUrl' => $user->stats->gitlab_url])
                            @endisset
                            @isset($user->stats->linkedin_url)
                                @include('partials.icon-button', ['iconName' => 'linkedin', 'label' => 'LinkedIn', 'type' => 'transparent', 'anchorUrl' => $user->stats->linkedin_url])
                            @endisset
                        </div>
                    </div>
                    <p>{{$user->description}}</p>
                    <p><span class="font-bold">Joined at: </span>{{ \Carbon\Carbon::parse($user->register_timestamp)->format('Y-m-d') }}</p>
                    @if ($user->stats->languages->count() > 0)
                        <p><span class="font-bold">Top Languages: </span>
                            @foreach ($user->stats->languages as $language)
                                {{ $language->name . ($loop->last ? '' : ', ') }}
                            @endforeach
                        </p>
                    @endif
                    @if ($user->stats->technologies->count() > 0)
                        <p><span class="font-bold">Technologies: </span>
                            @foreach ($user->stats->technologies as $technology)
                                {{ $technology->name . ($loop->last ? '' : ', ') }}
                            @endforeach
                        </p>
                    @endif
                </section>

                <section id="top-projects" class="card flex flex-col gap-3">
                    <h1 class="text-xl font-bold">Top Projects</h1>
                    @if ($user->stats->topProjects()->count() > 0)
                        <ul class="ms-4 list-disc">
                            @foreach ($user->stats->topProjects as $project)
                                <li>
                                    <p class="font-bold">{{ $project->name }}</p>
                                    <a href="{{ $project->url }}" target="_blank">{{ $project->url }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No projects to show</p>
                    @endif
                </section>
            </div>

            <div id="profile-middle" class="h-min col-span-4 lg:col-span-2 row-start-4 lg:row-start-2 col-start-1 lg:col-start-2 flex flex-col gap-3">
                <section class="card space-y-3">
                    <h1 class="text-xl font-bold">Posts</h1>
                    @if ($posts->count() === 0)
                        <p>No posts to show</p>
                    @else
                        @foreach ($posts as $post)
                            @include('partials.post-card', ['post' => $post])
                        @endforeach
                    @endif
                </section>
            </div>

            <div id="profile-right" class="h-min col-span-4 lg:col-span-1 row-start-3 lg:row-start-2 col-start-1 lg:col-start-4 flex flex-col gap-3">
                <section id="follows" class="card flex flex-col">
                    <h1 class="mb-2 text-xl font-bold">User Connections</h1>
                    <a href="{{ route('user.followers', ['id' => $user->id]) }}" class="text-lg font-semibold block">Followers: {{ $user->num_followers }}</a>
                    <a href="{{ route('user.following', ['id' => $user->id]) }}" class="text-lg font-semibold block">Following: {{ $user->num_following }}</a>
                    @can('viewRequests', $user)
                        <a href="{{ route('user.requests', ['id' => $user->id]) }}" class="text-lg font-semibold block">Requests: {{ $numRequests }}</a>
                    @endcan
                </section>

                <section id="groups" class="card flex flex-col">
                    <h1 class="mb-2 text-xl font-bold">User Groups</h1>
                    <a href="{{ route('user.groups', $user->id) }}" class="text-lg font-semibold"><h2>Groups: {{ $user->groups->count() }}</h2></a>
                    @can('viewInvites', $user)
                        <a href="{{ route('user.invites', $user->id) }}" class="text-lg font-semibold"><h2>Invites: {{ $user->groupsInvitedTo()->count() }}</h2></a>
                    @endcan
                </section>

                @if ($recommendedUsers->count() > 0)
                    <section id="users" class="card hidden lg:flex flex-col gap-3">
                        <h1 class="text-xl font-bold">Users you might know</h1>
                        @include('partials.user-list', ['users' => $recommendedUsers, 'responsive' => true])
                    </section>
                @endif
            </div>
        @else
            <section id="private-profile" class="min-h-32 col-span-4 flex flex-col justify-center items-center">
                <h1 class="text-4xl/[3rem] font-bold">This profile is private</h1>
                <p class="font-medium">You cannot see this user profile until you follow this person</p>
            </section>
        @endcan
    </main>
@endsection
