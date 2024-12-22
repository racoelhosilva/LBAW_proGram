@extends('layouts.app')

@section('title') {{$group->name . 'members | ProGram'}} @endsection

@section('content')
    <main id="members-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{$group->id}}>
        <section id="members">
            <h1 class="text-2xl text-center font-medium m-4">Members</h1>
            @forelse ($members as $user)
                <div class="manage-member-container flex flex-row w-full mb-4" data-user-id={{$user->id}}>
                    @include('partials.user-card-group-member', ['user' => $user, 'class' => 'w-full'])
                </div>
            @empty
                <p>No members at the moment</p>
            @endforelse
            @if(!$members->isEmpty())
                {{$members->onEachSide(0)->links()}}
            @endif
        </section>  
      </main>
@endsection
