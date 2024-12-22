<article class="card w-[50em] h-min p-10 pt-16 grid gap-12 justify-items-center">
    @include('partials.logo', ['size' => 'large'])

    <form method="post" action="{{ route('password.update') }}" class="grid gap-4 justify-self-stretch">
        @csrf

        @include('partials.input-field', [
            'name' => 'email',
            'label' => 'E-mail',
            'type' => 'email',
            'value' => old('email'),
            'placeholder' => 'johndoe@password.com',
            'required' => true,
        ])
        @include('partials.password-input-field', [
            'name' => 'password',
            'label' => 'Password',
            'type' => 'password',
            'placeholder' => 'password123',
            'required' => true,
        ])

        @include('partials.password-input-field', [
            'name' => 'password_confirmation',
            'label' => 'Password Confirmation',
            'type' => 'password',
            'placeholder' => 'password123',
            'required' => true,
        ])

        <input id="token" name="token" type="hidden" value="{{ $token }}">

        <div class="flex flex-col">

            @include('partials.text-button', [
                'text' => 'Reset Password',
                'label' => 'Reset',
                'type' => 'primary',
                'submit' => true,
            ])
        </div>
    </form>
</article>
