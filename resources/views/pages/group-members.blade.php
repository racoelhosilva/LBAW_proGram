@extends('layouts.app')

@section('title') {{$group->name . 'members | ProGram'}} @endsection

@section('content')
    <main id="members-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{$group->id}}>
        <section id="members">
            <h1 class="text-4xl text-center font-medium m-4">Members</h1>
            @forelse ($group->members->where('id', '!=', $group->owner->id) as $user)
                <div class="manage-member-container flex flex-row w-full mb-4" data-user-id={{$user->id}}>
                    @php
                        $removeButton = view('partials.text-button', ['text' => 'Remove', 'class' => 'w-40 rounded-lg remove-member-button'])->render();
                        if($group->owner->id == Auth::id()){
                            $buttons = $removeButton;
                        }
                        else
                        {
                            $buttons = '';
                        }
                        
                    @endphp
                    @include('partials.user-card', ['user' => $user, 'class' => 'w-full ', 'buttons' => $buttons])
                </div>

            @empty
                <p>No users at the moment</p>
            @endforelse

        </section>  
      </main>
@endsection
