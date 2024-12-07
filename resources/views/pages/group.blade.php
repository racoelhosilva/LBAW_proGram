@extends('layouts.app')

@section('title') {{$group->name . ' | ProGram'}} @endsection

@section('content')
    <main id="profile-page" class="px-8 py-4 flex flex-col gap-6">
        <section id="banner-section" class="card h-min  grid grid-cols-[auto_1fr_1fr] gap-y-16 p-4 ">
            <div class="col-span-full">
                <h1 class="text-4xl font-bold">{{ $group->name }}</h1>

            </div>
            <div class="flex justify-end items-end">

                <p class="text-xl">{{$group->description}}</p>
            </div>
            <div>
                <a href="{{ route('group.members', ['id' => $group->id]) }}" class="text-xl font-bold">{{$group->member_count}} members</a>
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
