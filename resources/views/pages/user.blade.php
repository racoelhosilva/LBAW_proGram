@extends('layouts.app')

@section('content')

<main id="profile-page" class="px-8 py-4 grid grid-cols-4 gap-6">
    <section style="background-image: url('{{$user->banner_image_url}}');" class="card h-min col-span-4 grid grid-cols-[auto_1fr] bg-cover">
        <article class="m-2 col-span-2 grid grid-rows-6">
            <h1 class="text-4xl text-white font-bold row-start-1 row-end-2">{{$user->name}}</h1>
            <h2 class="text-2xl text-white row-start-2 row-end-3">{{'@' . $user->handle}}</h2>
            <img src="{{$user->profile_picture_url}}" class="w-52 h-52 rounded-full object-cover row-start-4 row-end-7">
        </article>
        <article class="m-2 col-start-6 col-end-7 grid grid-rows-6 grid-cols-8">
            <div class="profile-buttons row-start-6 row-end-7 col-start-5 col-end-9">
                @if ($isOwnProfile)
                    @include('partials.text-button', ['text' => 'Edit Profile'])
                <!--
                @elseif ($isFollowing)
                    @include('partials.text-button', ['text' => 'Unfollow'])
                @else
                    @include('partials.text-button', ['text' => 'Follow'])
                -->
                @endif
            </div>
        </article>
    </section>

    <section class="h-min col-span-4 lg:col-span-1 grid grid-cols-4 space-y-3">
        <article class="card col-span-4 space-y-3">
            <h3 class="text-xl font-bold">User Info</h3>
            <p class="text-lg">{{$user->description}}</p>
            <p><span class="font-bold">Joined at: </span>{{ \Carbon\Carbon::parse($user->register_timestamp)->format('Y-m-d') }}</p>
            <p><span class="font-bold">Top Languages: </span>
                @foreach($user->stats->languages as $language)
                    {{ $language->name }}@if(!$loop->last), @endif
                @endforeach
            </p>
        </article>

        <article class="card col-span-4 space-y-3">
            <h3 class="text-xl font-bold">Projects</h3>
            @foreach($user->stats->projects as $project)
                <p class="font-bold">{{ $project->name }}</p>
                <a href="{{ $project->url }}" target="_blank">{{ $project->url }}</a>
            @endforeach
        </article>
    </section>

    <section class="h-min col-span-4 lg:col-span-2 grid grid-cols-4 space-y-3">
        <article class="card col-span-4 space-y-3">
            <h3 class="text-xl font-bold">Posts</h3>
            @foreach ($user->posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </article>
    </section>

    <section class="h-min col-span-4 lg:col-span-1 grid grid-cols-4 space-y-3">
        <article class="card col-span-4 space-y-3">
            <h3 class="text-xl font-bold">Followers: {{$user->num_followers}}</h3>
            <h3 class="text-xl font-bold">Following: {{$user->num_following}}</h3>
        </article>
        <article class="card col-span-4 space-y-3">
            <h3 class="text-xl font-bold">Users you might know</h3>
            @foreach ($recommendedUsers as $recommendedUser)
                @include('partials.user-card', ['user' => $recommendedUser])
            @endforeach
        </article>
    </section>

</main>

@endsection
