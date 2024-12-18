@extends('layouts.app')

@section('title') {{$group->name . 'requests | ProGram'}} @endsection

@section('content')
    <main id="group-requests-page" class="px-8 py-4 flex flex-col gap-6" data-group-id={{$group->id}}>
        <section id="requests" class=" flex flex-col w-full gap-4 " >
            <h1 class="text-4xl text-center font-medium m-4">Join Requests</h1>
            @forelse ($usersWhoWantToJoin as $user)
                <div class="manage-request-container flex flex-row gap-4" data-user-id={{$user->id}}>
                    @include('partials.user-card-group-request', ['user' => $user, 'class' => 'w-full'])             
                </div>    
            @empty  
                <p>No requests yet.</p>
            @endforelse
        </section>
      </main>
@endsection
