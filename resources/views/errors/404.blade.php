@extends('layouts.auth')

@section('content')
    <main id="login-page" class="grid grid-cols-3 items-center">
        <article class="h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2">
            @include('partials.logo', ['size' => 'large'])
            <h1 class="text-3xl font-bold text-center">
                404 - Page Not Found
            </h1>
        </article>
    </main>
@endsection

