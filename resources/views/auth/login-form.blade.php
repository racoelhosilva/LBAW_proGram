<article class="card w-[40em] h-min p-10 pt-16 grid gap-12 justify-items-center">
    @include('partials.logo', ['size' => 'large'])

    <form method="post" action="{{ route('login') }}" class="grid gap-4 justify-self-stretch">
        {{ csrf_field() }}

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


        <div class="flex flex-col space-y-4">
            <div class="flex flex-row justify-between">
                <a href="{{ route('forgot-password') }}">
                    <span class="font-medium italic">
                        Forgot Password?
                    </span>
                </a>
                <label class="mb-2">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> <span
                        class="font-medium">Remember Me</span>
                </label>
            </div>

            @include('partials.text-button', [
                'text' => 'Login',
                'label' => 'Login',
                'type' => 'primary',
                'submit' => true,
            ])

            <div class="h-12 grid grid-cols-[1fr_auto_1fr] items-center gap-3">
                <span class="h-px bg-gray-300 dark:bg-gray-700"></span>
                Or Connect With
                <span class="h-px bg-gray-300 dark:bg-gray-700"></span>
            </div>
            <div class="grid grid-cols-3 gap-4">
                @include('auth.oauth-button', ['provider' => 'google'])
                @include('auth.oauth-button', ['provider' => 'github'])
                @include('auth.oauth-button', ['provider' => 'gitlab'])
            </div>
        </div>
    </form>

    <p>
        Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400">Register</a>
    </p>
</article>
