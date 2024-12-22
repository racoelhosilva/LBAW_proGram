@extends('layouts.app')

@section('title', 'Group Invites of ' . $user->name . ' | ProGram')

@section('content')
<main class="px-8 flex flex-col">
    <section id="user-group-invites" class="card space-y-3">
        <h1 class="text-2xl font-bold">Groups of {{ $user->name }}</h1>
        <div class="grid gap-x-4 gap-y-2 sm:grid-cols-2 md:grid-cols-3">
            @forelse ($invites as $group)
                <div class = "group-invite-container " data-group-id={{$group->id}}>
                @include('partials.group-invitation-card', ['group' => $group])
                </div>
            @empty
                <p>No invites sent yet</p>
            @endforelse
            @if(!$invites->isEmpty())
                {{ $invites->onEachSide(0)->links() }}
            @endif
        </div>
    </section>
</main>
@endsection
