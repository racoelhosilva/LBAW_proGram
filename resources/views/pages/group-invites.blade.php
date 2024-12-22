@extends('layouts.app')

@section('title')
    {{ $group->name . 'invites | ProGram' }}
@endsection

@section('content')
    <main id="group-invites-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{ $group->id }}>
        <section id="invites" class="flex flex-col">
            <h1 class="text-4xl text-center font-medium m-4">Invites</h1>
            @include('partials.search-invites', ['group' => $group])
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
                    @if(!$usersSearched->isEmpty())
                        <div class="flex justify-center">
                            {{ $usersSearched->links() }}
                        </div>
                    @endif
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
                    @if(!$usersInvited->isEmpty())
                        <div class="flex justify-center">
                            {{ $usersInvited->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </section>
    </main>
@endsection
