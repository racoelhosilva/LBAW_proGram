@extends('layouts.app')

@section('title') {{$group->name . ' | ProGram'}} @endsection

@section('content')
    <main id="manage-group-page" class="px-8 py-4 flex flex-col gap-6">
        @if($group->is_public)
            <header class="grid grid-cols-[auto_auto] gap-0 w-full">    
        @else
            <header class="grid grid-cols-[auto_auto_auto] gap-0 w-full">
        @endif
            <a href="#members" 
               class="text-xl border text-center font-bold p-4 hover:border">Members</a>
            @if(!$group->is_public)
                <a href="#requests" 
                class="text-xl border border-transparent text-center font-bold p-4  hover:border">Join Requests</a>
            @endif
            <a href="#invites" 
               class="text-xl border border-transparent text-center font-bold p-4 hover:border">Invites</a>
        </header>
        <section id="members">
            <h1>Members of {{$group->name}}</h1>
        </section>
        @if(!$group->is_public)
            <section id="requests" class="hidden">
                <h1>Join Requests</h1>
                @forelse ($usersWhoWantToJoin as $user)
                    @include('partials.user-card', ['user' => $user])
                @empty
                    <p>No requests yet.</p>
                @endforelse
            </section>
        @endif
        <section id="invites" class="hidden">
            <h1>Invites</h1>
        </section>
    </main>
@endsection
