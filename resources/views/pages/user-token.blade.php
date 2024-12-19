@extends('layouts.app')

@section('title')
    User Token | ProGram
@endsection

@section('content')
    <main class="px-8">
        <article class="card h-min p-10 pt-16 flex flex-col gap-12">
            <h1 class="text-2xl font-bold text-center">User Token</h1>
            <div class="flex gap-4 items-center">
                @isset($user->token)
                    <div>
                        <label class="font-medium">
                            Your API Token
                            @include('partials.copy-button', ['value' => $user->token->value])
                        </label>
                        @if ($user->token->validity_timestamp->isPast())
                            <p class="text-red-500 dark:text-red-400">Your token expired {{ $user->token->validity_timestamp->diffForHumans() }}</p>
                        @else
                            <p class="text-green dark:text-green-400">Your token expires {{ $user->token->validity_timestamp->diffForHumans() }}</p>
                        @endif
                    </div>
                    <form method="post" action="{{ route('token.destroy', $user->token->id) }}" class="col-start-3 flex">
                        @csrf
                        @method('DELETE')
                        @include('partials.text-button', ['text' => 'Revoke Token', 'label' => 'Revoke Token', 'type' => 'primary', 'submit' => true])
                    </form>
                @else
                    <p class="text-gray-700 dark:text-gray-400">You do not have an API token yet.</p>
                    <form method="post" action="{{ route('token.store') }}" class="col-start-3 flex">
                        @csrf
                        <input type="hidden" name="duration" value="day">
                        <div class="">
                            @include('partials.text-button', ['text' => 'Generate Token', 'label' => 'Generate Token', 'type' => 'primary', 'submit' => true])
                        </div>
                    </form>
                @endisset
            </div>
        </article>
    </main>
@endsection