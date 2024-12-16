@extends('layouts.app')

@section('title') {{$group->name . 'invites | ProGram'}} @endsection

@section('content')
    <main id="group-invites-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{$group->id}}>
        <section id="invites" >
            <h1 class="text-4xl text-center font-medium m-4">Invites</h1>
            @include('partials.search-field', ['class' => 'w-full mb-4'])
            <div id="invite-results" class="flex flex-col gap-4">
                @foreach ($usersSearched as $user)
                    <div class="manage-invite-container flex flex-row w-full " data-user-id={{$user->id}}>
                        @php
                        if($group->isUserInvited($user)){

                            $inviteButton = view('partials.text-button', ['text' => 'Unsend', 'class' => 'w-40  uninvite-button'])->render();
                        }else {
                            $inviteButton = view('partials.text-button', ['text' => 'Send', 'class' => 'w-40    invite-button'])->render();
                                
                        }
                        $buttons = $inviteButton;
                        @endphp
                        @include('partials.user-card', ['user' => $user, 'class' => 'w-full ', 'buttons' => $buttons])
                    </div>
                @endforeach
            </div>
        </section>
      </main>
@endsection
