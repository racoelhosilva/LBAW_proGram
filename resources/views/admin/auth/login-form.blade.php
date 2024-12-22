<article class="card w-[40em] h-min p-10 pt-16 grid gap-12 justify-items-center">
    @include('admin.partials.logo', ['size' => 'large'])
    
    <form method="post" action="{{ route('admin.login') }}" class="grid gap-4 justify-self-stretch">
        @csrf

        @include('partials.input-field', ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'value' => old('email'), 'placeholder' => 'johndoe@password.com', 'required' => true])
        @include('partials.password-input-field', ['name' => 'password', 'label' => 'Password', 'placeholder' => 'password123', 'required' => true])

        <div class="flex flex-col">
            <label class="mb-2">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> <span class="font-medium">Remember Me</span>
            </label>

            @include('partials.text-button', ['text' => 'Login', 'label' => 'Login', 'type' => 'primary', 'submit' => true])
        </div>
    </form>
</article>
