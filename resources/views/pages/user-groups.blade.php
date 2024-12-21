@extends('layouts.app')

@section('title', 'Groups of ' . $user->name . ' | ProGram')

@section('content')
<main class="px-8 flex flex-col">
    <article id="user-groups" class="card space-y-3">
        <h3 class="text-xl font-bold">Groups of {{$user->name}}</h3>
        <div class="grid gap-x-4 gap-y-2">
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
</main>
@endsection
