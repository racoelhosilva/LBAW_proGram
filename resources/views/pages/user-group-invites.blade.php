@extends('layouts.app')

@section('title', 'Group Invites of ' . $user->name . ' | ProGram')

@section('content')
<main class="px-8 flex flex-col">
    <article id="user-group-invites" class="card space-y-3">
        <h3 class="text-xl font-bold">Groups of {{ $user->name }}</h3>
        <div class="grid gap-x-4 gap-y-2 sm:grid-cols-2 md:grid-cols-3">
            @forelse ($invites as $group)
                <div class = "group-invite-container " data-group-id={{$group->id}}>
                @include('partials.group-invitation-card', ['group' => $group])
                </div>
            @empty
                <p>This user does not follow any other user</p>
            @endforelse
        </div>
    </article>
</main>
@endsection
