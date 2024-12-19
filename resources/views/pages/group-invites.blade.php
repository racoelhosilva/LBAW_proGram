@extends('layouts.app')

@section('title')
    {{ $group->name . 'invites | ProGram' }}
@endsection

@section('content')
    <main id="group-invites-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{ $group->id }}>
        <section id="invites" class="flex flex-col">
            <h1 class="text-4xl text-center font-medium m-4">Invites</h1>
            @include('partials.search-field', [
                'routeName' => 'group.invites',
                'routeParams' => ['id' => $group->id],
            ])
            <div id="invite-results" class="flex flex-col gap-4 mt-4">
                @if($searched)
                    @if($usersSearched->isEmpty())
                        <p>No users found.</p>
                    @else
                        @foreach ($usersSearched as $user)
                            <div class="manage-invite-container flex flex-row w-full " data-user-id={{ $user->id }}>    
                                @include('partials.user-card-group-invites', [
                                    'user' => $user,
                                    'class' => 'w-full ',
                                ])
                            </div>
                        @endforeach
                    @endif
                @else
                    @if($usersInvited->isEmpty())
                        <p>No invites sent yet.</p>
                    @else
                        @foreach ($usersInvited as $user)
                            <div class="manage-invite-container flex flex-row w-full " data-user-id={{ $user->id }}>    
                                @include('partials.user-card-group-invites', [
                                    'user' => $user,
                                    'class' => 'w-full ',
                                ])
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
        </section>
    </main>
@endsection
