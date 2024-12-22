@extends('layouts.app')

@section('title') {{'Group invites of ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<main class="px-8 flex flex-col">
    <article id="user-group-invites" class="card space-y-3">
        <h3 class="text-xl font-bold">Groups of {{ $user->name }}</h3>
        <div class="grid gap-x-4 gap-y-2 sm:grid-cols-2 md:grid-cols-3">
            @forelse ($invites as $group)
                <div class = "group-invite-container " data-group-id={{$group->id}}>
                @php
                    if($user->id == Auth::id()){
                        $buttons = view('partials.icon-button', ['iconName' => 'check','type' =>'secondary', 'id' => '','class'=>'accept-invite-button', 'label' => 'accept'])->render();
                        $buttons .= view('partials.icon-button', ['iconName' => 'x','type' =>'secondary',   'id' => '','class'=>'reject-invite-button', 'label' => 'reject'])->render();
                    } else {
                        $buttons = '';
                    }
                @endphp
                @include('partials.group-card', ['group' => $group, 'buttons' => $buttons])
                </div>
            @empty
                <p>This user has no invites to groups</p>
            @endforelse
            @if(!$invites->isEmpty())
                {{ $invites->onEachSide(0)->links() }}
            @endif
        </div>
    </article>
</main>
@endsection
