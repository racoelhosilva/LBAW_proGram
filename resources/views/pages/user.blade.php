@extends('layouts.app')

@section('content')
    <main id="profile-page" class="px-8 py-4 grid grid-cols-4 gap-6">
        <section id="banner-section" style="background-image: url('{{ $user->getBannerImage() }}');" class="card h-min col-span-4 grid grid-cols-[auto_1fr] bg-cover">
            <article class="m-2 col-span-2 grid grid-rows-6">
                <h1 class="text-4xl font-bold row-start-1 row-end-2">{{ $user->name }}</h1>
                <h2 class="text-2xl row-start-2 row-end-3">{{ '@' . $user->handle }}</h2>
                <img src="{{ $user->getProfilePicture() }}" class="w-52 h-52 rounded-full object-cover row-start-4 row-end-7">
            </article>
            <article class="m-2 col-start-3 flex items-end mb-6">
                <div class="profile-buttons row-start-9 ">
                    @if ($isOwnProfile)
                        @include('partials.text-button', ['text' => 'Edit Profile', 'anchorUrl' => route('user.edit',auth()->id())])
                    @endif
                </div>
            </article>
        </section>

        <section id="profile-left" class="h-min col-span-4 lg:col-span-1 grid grid-cols-4 space-y-3">
            <article id="user-info" class="card col-span-4 space-y-3">
                <h1 class="text-xl font-bold">User Info</h1>
                <p>{{$user->description}}</p>
                <p><span class="font-bold">Joined at: </span>{{ \Carbon\Carbon::parse($user->register_timestamp)->format('Y-m-d') }}</p>
                @if ($user->stats->languages->count() > 0)
                    <p><span class="font-bold">Top Languages: </span>
                        @foreach($user->stats->languages as $language)
                            {{ $language->name }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif
                @if ($user->stats->technologies->count() > 0)
                    <p><span class="font-bold">Technologies: </span>
                        @foreach($user->stats->technologies as $technology)
                            {{ $technology->name }}@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif
            </article>

            <article id="top-projects" class="card col-span-4 space-y-3">
                <h1 class="text-xl font-bold">Top Projects</h1>
                @if (count($user->stats->projects) > 0)
                    <ul class="ms-4 list-disc">
                        @foreach ($user->stats->projects as $project)
                            <li>
                                <p class="font-bold">{{ $project->name }}</p>
                                <a href="{{ $project->url }}" target="_blank">{{ $project->url }}</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No projects to show</p>
                @endif
            </article>
        </section>

        <section id="profile-middle" class="h-min col-span-4 lg:col-span-2 grid grid-cols-4 space-y-3">
            <article class="card col-span-4 space-y-3">
                <h1 class="text-xl font-bold">Posts</h1>
                @foreach ($user->posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </article>
        </section>

        <section id="profile-right" class="h-min col-span-4 lg:col-span-1 grid grid-cols-4 space-y-3">
            <article id="follows" class="card col-span-4 space-y-3">
                <p class="text-xl font-bold">Followers: {{ $user->num_followers }}</p>
                <p class="text-xl font-bold">Following: {{ $user->num_following }}</p>
            </article>

            @if ($recommendedUsers !== null && $recommendedUsers->count() > 0)
                <article id="users" class="card col-span-4 space-y-3">
                    <h3 class="text-xl font-bold">Users you might know</h3>
                    @foreach ($recommendedUsers as $recommendedUser)
                        @include('partials.user-card', ['user' => $recommendedUser])
                    @endforeach
                </article>     
            @endif
        </section>

    </main>

@endsection
