<section id="toast-container" class="fixed bottom-4 right-4 space-y-4">
    <article id="error-toast" class="toast-message {{ ! $errors->any() ? 'hidden' : '' }} p-4 rounded-lg bg-red-500 text-white font-medium shadow-2xl transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'circle-x'])
        <p>{{ $errors->first() }}</p>
    </article>

    <article id="success-toast" class="toast-message {{ ! session('success') ? 'hidden' : '' }} p-4 rounded-lg bg-green-500 text-white font-medium shadow-2xl transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'circle-check'])
        <p>{{ session('success') }}</p>
    </article>

    <article id="info-toast" class="toast-message {{ ! session('info') ? 'hidden' : '' }} p-4 rounded-lg bg-blue-500 text-white font-medium shadow-2xl transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'info'])
        <p>{{ session('info') }}</p>
    </article>
</section>