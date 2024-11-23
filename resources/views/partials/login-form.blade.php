@props(['route', 'logo'])

<article class="card p-10 grid gap-4 justify-items-center col-start-2">
    @include($logo, ['size' => 'large'])

    <form method="post" action="{{ route('login') }}" class="grid gap-4 justify-self-stretch">
        {{ csrf_field() }}

        @include('partials.input-field', ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'value' => old('email'), 'placeholder' => 'me@password.com', 'required' => true])
        @include('partials.input-field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'placeholder' => 'password123', 'required' => true])

        <div class="flex flex-col">
            <label class="mb-2">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>

            @include('partials.text-button', ['text' => 'Login', 'label' => 'Login', 'type' => 'primary', 'submit' => true])
            @if (session('success'))
                <p class="text-sm text-green-500">{{ session('success') }}</p>
            @endif
        </div>
    </form>
</article>