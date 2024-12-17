<article class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2 col-span-2">
    @include('partials.logo', ['size' => 'large'])

    <form method="post" action="{{ route('register') }}" class="grid grid-cols-2 gap-4 justify-self-stretch">
        {{ csrf_field() }}

        <div class="col-span-2">
            @include('partials.input-field', ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => old('name'), 'placeholder' => 'John Doe', 'required' => true])
        </div>
        @include('partials.input-field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => old('email'), 'placeholder' => 'johndoe@example.com', 'required' => true])
        @include('partials.input-field', ['name' => 'handle', 'label' => 'User Handle', 'type' => 'text', 'value' => old('handle'), 'placeholder' => 'johndoe2024', 'required' => true])
        @include('partials.input-field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'placeholder' => 'password123', 'required' => true])
        @include('partials.input-field', ['name' => 'password_confirmation', 'label' => 'Confirm Password', 'type' => 'password', 'placeholder' => 'password123', 'required' => true])

        <div class="flex flex-col col-span-2">
            <label class="mb-2">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> <span
                        class="font-medium">Remember Me</span>
            </label>

            @include('partials.text-button', ['text' => 'Register', 'label' => 'Register', 'type' => 'primary', 'submit' => true])
            @if (session('success'))
                <p class="text-sm text-green-500">{{ session('success') }}</p>
            @endif
        </div>

        <div class="h-12 col-span-2 grid grid-cols-[1fr_auto_1fr] items-center gap-3">
            <span class="h-px bg-gray-300 dark:bg-gray-700"></span>
            Or Connect With
            <span class="h-px bg-gray-300 dark:bg-gray-700"></span>
        </div>
        <div class="col-span-2 grid grid-cols-3 gap-4">
            @include('auth.oauth-button', ['provider' => 'google'])
            <!-- TODO: when the user is logging in using github after the first time, they get redirected automatically to the home page, but that process takes some time. maybe add a spinning loading indicator?  -->
            @include('auth.oauth-button', ['provider' => 'github'])
            @include('auth.oauth-button', ['provider' => 'gitlab'])
        </div>
    </form>

    <p>
        Already have an account? <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400">Log In</a>
    </p>
</article>
