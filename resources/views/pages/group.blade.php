@extends('layouts.app')

@section('title') {{$group->name . ' | ProGram'}} @endsection

@section('content')
    <main id="profile-page" class="px-8 py-4 flex flex-col gap-6">
        <section id="banner-section" class="card h-min  grid grid-cols-[auto_1fr_1fr] gap-y-16 p-4 ">
            <div class="col-span-full flex justify-between">
                <h1 class="text-4xl font-bold">{{ $group->name }}</h1>
                @if($group->is_public)
                    @include('partials.icon', ['name' => 'earth'])

                @else
                   @include('partials.icon', ['name' => 'lock'])
                @endif
            </div>
            <div class="col-span-full flex justify-start items-end">

                <p class="text-xl">{{$group->description}}</p>
                
            </div>
            <div class="col-span-full flex justify-between ">
                <div class="flex flex-col">
                    <a href="{{ route('group.members', ['id' => $group->id]) }}" class="text-xl font-bold">{{$group->member_count}} members</a>
                </div>

                <div class="buttons ">
                    @if ($isMember && !$isOwner)
                        @include('partials.text-button', ['text' => 'Leave Group'])
                        @include('partials.text-button', ['text' => 'Create Post', 'url' => route('post.create', ['group_id' => $group->id])])
                    @elseif (!$isMember)
                        @include('partials.text-button', ['text' => 'Join Group'])
                    @elseif($isOwner)
                        @include('partials.text-button', ['text' => 'Edit Group', 'anchorUrl' => route('group.edit', ['id' => $group->id])])
                    @endif

                </div>
            </div>  

        </section>
        <section class="grid gap-4" >
            @if ($posts->count() === 0)
                    <p>No posts to show</p>
                @else
                    @foreach ($posts as $post)
                        @include('partials.post-card', ['post' => $post])
                    @endforeach
                @endif

        </section>

       
    </main>
@endsection
