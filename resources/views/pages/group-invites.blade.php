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

                @foreach ($usersSearched as $user)
                    <div class="manage-invite-container flex flex-row w-full " data-user-id={{ $user->id }}>
                        @php
                            if ($group->isUserInvited($user)) {
                                $inviteButton = view('partials.text-button', [
                                    'text' => 'Send',
                                    'type' => 'secondary',
                                    'class' => 'w-40    invite-button hidden',
                                ])->render();
                                $inviteButton .= view('partials.text-button', [
                                    'text' => 'Unsend',
                                    'type' => 'secondary',
                                    'class' => 'w-40  uninvite-button ',
                                ])->render();
                            } else {
                                $inviteButton = view('partials.text-button', [
                                    'text' => 'Send',
                                    'type' => 'secondary',
                                    'class' => 'w-40    invite-button',
                                ])->render();
                                $inviteButton .= view('partials.text-button', [
                                    'text' => 'Unsend',
                                    'type' => 'secondary',
                                    'class' => 'w-40  uninvite-button hidden',
                                ])->render();
                            }
                            $buttons = $inviteButton;
                        @endphp
                        @include('partials.user-card-group-invites', [
                            'user' => $user,
                            'class' => 'w-full ',
                            'buttons' => $buttons,
                        ])
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection
