@extends('layouts.app')

@section('title') {{'Followed by ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3 min-h-[50rem]">
    <h3 class="text-xl font-bold">Groups of {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 sm:grid-cols-2 md:grid-cols-3">
        @forelse ($invites as $group)
            <div class = "group-invite-container" data-group-id={{$group->id}}>
            @php
                if($user->id == Auth::id()){
                    $buttons = view('partials.icon-button', ['iconName' => 'check', 'id' => '','class'=>'accept-invite-button', 'label' => 'accept', 'type' => 'transparent'])->render();
                    $buttons .= view('partials.icon-button', ['iconName' => 'x', 'id' => '','class'=>'reject-invite-button', 'label' => 'reject', 'type' => 'transparent'])->render();
                }
                else
                {
                    $buttons = '';
                }
            @endphp
            @include('partials.group-card', ['group' => $group])
            </div>
        @empty
            <p>This user does not follow any other user</p>
        @endforelse
    </div>
</article>
@endsection
