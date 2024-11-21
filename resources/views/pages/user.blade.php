@extends('layouts.app')

@section('content')

<main id="profile-page" class="px-8 py-4 grid grid-cols-4 gap-6">
    <article class="col-span-4 w-full bg-slate-700 text-white rounded-lg p-6">
        <div class="grid grid-cols-12 gap-6 h-full">

            <div class="col-span-3 grid grid-rows-3 items-start">
                <div class="row-start-1 row-end-2 text-left ml-6 mt-6">
                    <h2 class="text-6xl">{{$user->name}}</h2>
                    <p class="text-3xl mt-4 text-gray-400">{{'@' . $user->handle}}</p>
                </div>
        
                <div class="row-start-3 row-end-4 flex justify-start ml-6 mb-6">
                    <img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTT5wgld84FW_-apTUYFMGpB_ZEhlT2KMqon-3Jx4DHdWWd-k5_" 
                         alt="Profile picture" 
                         class="w-60 h-60 object-cover border-4 border-slate-800 rounded-lg shadow-lg transform transition-transform duration-300 hover:scale-110 hover:shadow-2xl hover:rotate-3">
                </div>
            </div>
        
            <div class="col-span-6 grid grid-rows-3 items-center">
                <p class="text-4xl row-start-3 row-end-4 text-center lg:text-left">{{$user->description}}</p>
            </div>
            <div class="col-span-3 grid grid-rows-3 mb-6">
                <div class="row-start-3 row-end-4 flex justify-center mt-auto">
                    @include('partials.text-button', [
                        'text' => 'Edit Profile', 
                        'type' => 'primary', 
                        'anchorUrl' => url('user/' . $user->id . '/edit'), 
                        'class' => 'extra-class', 
                    ])
                </div>
            </div>
        </div>
        
    </article>
</main>

@endsection
