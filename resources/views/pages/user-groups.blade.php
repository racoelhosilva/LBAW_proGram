@extends('layouts.app')

@section('title') {{'Groups of ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="user-groups" class="mx-12 card space-y-3 min-h-[50rem]">
    <h3 class="text-xl font-bold">Groups of {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 sm:grid-cols-2 md:grid-cols-3">
        @forelse ($user->groups as $group)
            @php
                if($user->id == Auth::id() && $group->owner_id != Auth::id()){
                    $buttons = view('partials.icon-button', ['iconName' => 'x', 'id' => '','class'=>'leave-group-button', 'label' => 'reject', 'type' => 'secondary'])->render();
                }
                else
                {
                    $buttons = '';
                }
            @endphp
            @include('partials.group-card', ['group' => $group])
        @empty
            <p>This user does not follow any other user</p>
        @endforelse
    </div>
</article>
@endsection
