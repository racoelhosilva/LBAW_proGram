@extends('layouts.app')

@section('title', 'Groups of ' . $user->name . ' | ProGram')

@section('content')
<main class="px-8 flex flex-col">
    <section id="user-groups" class="card space-y-3">
        <h1 class="text-xl font-bold">Groups of {{$user->name}}</h1>
        <div class="grid gap-x-4 gap-y-2">
            @forelse ($groups as $group)
                @include('partials.group-card', ['group' => $group])
            @empty
                <p>This user is not a member of any group.</p>
            @endforelse
            @if(!$groups->isEmpty()) 
                {{ $groups->onEachSide(0)->links() }}
            @endif
        </div>
    </section>
</main>
@endsection
