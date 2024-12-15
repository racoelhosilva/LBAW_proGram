@extends('layouts.app')

@section('title') {{'Followed by ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3 min-h-[50rem]">
    <h3 class="text-xl font-bold">Groups of {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 sm:grid-cols-2 md:grid-cols-3">
        @forelse ($user->groups as $group)
            @if($user->id= Auth::id())
                @php
                 $buttons = view('partials.icon-button', ['iconName' => 'eye', 'id' => '','class'=>'reject-invite-button', 'label' => 'reject', 'type' => 'transparent'])->render();
                    $buttons .= view('partials.icon-button', ['iconName' => 'check', 'id' => '','class'=>'accept-invite-button', 'label' => 'accept', 'type' => 'transparent'])->render();
                    $buttons .= view('partials.icon-button', ['iconName' => 'x', 'id' => '','class'=>'reject-invite-button', 'label' => 'reject', 'type' => 'transparent'])->render();
                   
                @endphp
            @else
                @php
                    $buttons = view('partials.text-button', ['text' => 'View', 'class' => 'w-40 delete-group-button'])->render();
                @endphp
            @endif
            @include('partials.group-card', ['group' => $group, 'buttons' => $buttons])
        @empty
            <p>This user does not follow any other user</p>
        @endforelse
    </div>
</article>
@endsection
