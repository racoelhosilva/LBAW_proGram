@extends('layouts.app')

@section('title', 'Invites of ' . $group->name . ' | ProGram')

@section('content')
    <main id="group-invites-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{ $group->id }}>
        <section id="invites" class="card flex flex-col">
            <h1 class="text-2xl font-medium mb-4">Invites</h1>
            <div class="grid grid-cols-[1fr_auto] gap-3">
                @include('partials.search-invites', ['group' => $group])
                @if($searched)
                    <a href="{{ route('group.invites', ['id' => $group->id]) }}" class="primary-btn px-4 py-3 text-center font-medium">See Current Invites</a>
                @endif
            </div>
            <div id="invite-results" class="flex flex-col gap-4 mt-4">
                @if($searched)
                    @forelse ($usersSearched as $user)
                        <div class="manage-invite-container flex flex-row w-full " data-user-id={{ $user->id }}>    
                            @include('partials.user-card-group-invites', [
                                'user' => $user,
                                'class' => 'w-full ',
                            ])
                        </div>
                    @empty
                        <p>No users found.</p>
                    @endforelse
                @else
                    @forelse ($usersInvited as $user)
                        <div class="manage-invite-container flex flex-row w-full " data-user-id={{ $user->id }}>    
                            @include('partials.user-card-group-invites', [
                                'user' => $user,
                                'class' => 'w-full ',
                            ])
                        </div>
                    @empty
                        <p>No invites sent yet.</p>
                    @endforelse
                @endif
            </div>
        </section>
    </main>
@endsection
