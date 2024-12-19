<article class="card w-[30em] h-min p-10 pt-16 grid gap-12 justify-items-center">
    @include('partials.logo', ['size' => 'large'])

    <form method="post" action="{{ route('forgot-password') }}" class="grid gap-6 justify-self-stretch">
        @csrf

        @include('partials.input-field', [
            'name' => 'email',
            'label' => 'E-mail',
            'type' => 'email',
            'value' => old('email'),
            'placeholder' => 'johndoe@password.com',
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

</article>
