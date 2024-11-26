<section id="toast-container" class="fixed bottom-4 right-4 space-y-4">
    <article class="toast-message message-toast {{ ! session('message') ? 'hidden' : '' }} p-4 rounded-lg bg-blue-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'info'])
        {{ session('message') }}
    </article>

    <article class="toast-message error-toast {{ ! $errors->any() ? 'hidden' : '' }} p-4 rounded-lg bg-red-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'circle-x'])
        {{ $errors->first() }}
    </article>

    <article class="toast-message success-toast {{ ! session('success') ? 'hidden' : '' }} p-4 rounded-lg bg-green-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'circle-check'])
        <p>{{ session('success') }}</p>
    </article>
</section>