@extends('layouts.app')

@section('title') {{$group->name . ' | ProGram'}} @endsection

@section('content')
    <main id="manage-group-page"  data-group-id={{$group->id}} class="px-8 py-4 flex flex-col gap-6">
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
            @forelse ($group->members->where('id', '!=', $group->owner->id) as $user)
                <div class="manage-member-container flex flex-row w-full gap-4" data-user-id={{$user->id}}>
                    @php
                        $removeButton = view('partials.text-button', ['text' => 'Remove', 'class' => 'w-40 rounded-lg remove-member-button'])->render();
                        $buttons = $removeButton;
                    @endphp
                    @include('partials.user-card', ['user' => $user, 'class' => 'w-full ', 'buttons' => $buttons])
                </div>

            @empty
                <p>No users at the moment</p>
            @endforelse

        </section>
        @if(!$group->is_public)
            <section id="requests" class="hidden flex flex-col w-full gap-4 " >
                @forelse ($usersWhoWantToJoin as $user)
                    <div class="manage-request-container flex flex-row gap-4" data-user-id={{$user->id}}>
                        @php
                        $acceptButton = view('partials.text-button', ['text' => 'Accept', 'class' => 'w-40 rounded-lg accept-request-button'])->render();
                        $declineButton = view('partials.text-button', ['text' => 'Decline', 'class' => 'w-40 rounded-lg decline-request-button'])->render();
                        $buttons = $acceptButton . $declineButton;
                    @endphp
                    
                    @include('partials.user-card', ['user' => $user, 'class' => 'w-full', 'buttons' => $buttons])
                    
                    
                    </div>    
                @empty  
                    <p>No requests yet.</p>
                @endforelse
            </section>
        @endif
        <section id="invites" class="hidden">
        </section>
    </main>
@endsection
