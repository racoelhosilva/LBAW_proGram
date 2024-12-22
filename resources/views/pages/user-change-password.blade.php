@extends('layouts.app')

@section('title', 'Change Password | ProGram')

@section('content')
    <main class="px-8 grid grid-cols-4 grid-rows-[auto_1fr] gap-4">
        @include('partials.user-settings-menu')

        <section class="card h-min p-10 pt-16 col-span-4 lg:col-span-3 flex flex-col gap-12">
            <h1 class="text-2xl font-bold text-center">Change Password</h1>
            <form method="post" action="{{ route('user.password.update') }}" class="grid gap-4 justify-self-stretch">
                @csrf

                @include('partials.password-input-field', [
                    'name' => 'current_password',
                    'label' => 'Current Password',
                    'type' => 'password',
                    'value' => old('current_password'),
                    'placeholder' => 'password123',
                    'required' => true,
                ])
                @include('partials.password-input-field', [
                    'name' => 'new_password',
                    'label' => 'New Password',
                    'type' => 'password',
                    'placeholder' => 'password123',
                    'required' => true,
                ])

                @include('partials.password-input-field', [
                    'name' => 'new_password_confirmation',
                    'label' => 'Password Confirmation',
                    'type' => 'password',
                    'placeholder' => 'password123',
                    'required' => true,
                ])


                <div class="flex flex-col">

                    @include('partials.text-button', [
                        'text' => 'Reset Password',
                        'label' => 'Reset',
                        'type' => 'primary',
                        'submit' => true,
                    ])
                </div>
            </form>
        </section>
    </main>
@endsection
