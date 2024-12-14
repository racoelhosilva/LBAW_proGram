@extends('layouts.app')

@section('title') {{$group->name . 'members | ProGram'}} @endsection

@section('content')
    <main id="group-invites-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{$group->id}}>
        <section id="invites" >
            <h1 class="text-4xl text-center font-medium m-4">Invites</h1>
            @include('partials.search-field', ['class' => 'w-full mb-4'])
            <div id="invite-results" class="flex flex-col gap-4">
                @foreach ($usersSearched as $user)
                    @include('partials.user-card', ['user' => $user, 'class' => 'w-full '])
                @endforeach
            </div>
        </section>
      </main>
@endsection
