@extends('layouts.app')

@section('title', 'User Token | ProGram')

@section('content')
    <main class="px-8 grid grid-cols-4 grid-rows-[auto_1fr] gap-4">
        @include('partials.user-settings-menu')

        <section class="card h-min p-10 pt-16 col-span-4 lg:col-span-3 flex flex-col gap-12">
            <h1 class="text-2xl font-bold text-center">User Token</h1>
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

                <form method="post" action="{{ route('token.destroy', $user->token->id) }}" class="flex flex-col">
                    @csrf
                    @method('DELETE')
                    @include('partials.text-button', ['text' => 'Revoke Token', 'type' => 'danger', 'submit' => true])
                </form>
            @else
                <form id="create-token-form" method="post" action="{{ route('token.store') }}" class="flex flex-col gap-4">
                    <p class="text-gray-700 dark:text-gray-400">You do not have an API token yet.</p>
                    @csrf
                    <div>
                    <p class="font-medium">Token Duration</p>
                    @include('partials.select', [
                        'name' => 'duration',
                        'label' => 'Token Duration',
                        'options' => [
                            ['value' => '', 'name' => '1 Day'],
                            ['value' => 'week', 'name' => '1 Week'],
                            ['value' => 'month', 'name' => '1 Month'],
                            ['value' => 'year', 'name' => '1 Year'],
                        ],
                        'required' => true,
                        'form' => 'create-token-form',
                    ])
                    @if ($errors->has('duration'))
                        <p class="text-red-600 dark:text-red-400 text-sm font-medium">{{ $errors->first('duration') }}</p>
                    @endif
                    </div>
                    @include('partials.text-button', ['text' => 'Generate Token', 'type' => 'primary', 'submit' => true])
                </form>
            @endisset
        </section>
    </main>
@endsection