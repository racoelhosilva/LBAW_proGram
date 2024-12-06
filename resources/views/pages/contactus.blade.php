@extends('layouts.app')

@section('title', 'Contact Us | ProGram')

@section('content')
    <main id="contactus-page" class="min-h-[80vh] flex flex-col items-center justify-center px-4">
        <section class="mb-8 text-center">
            <h1 class="text-4xl font-medium">Contact Us</h1>
        </section>
        <section class="flex flex-col md:flex-row justify-center gap-8 items-start w-full m-20">
            <article class="address card h-min p-4 border rounded-lg shadow">
                <h2 class="text-2xl font-semibold mb-2">Address</h2>
                <p>4200-465 Porto</p>
            </article>
            <article class="phone card h-min p-4 border rounded-lg shadow">
                <h2 class="text-2xl font-semibold mb-2">Phone</h2>
                <p>+351 123 456 789</p>
            </article>
            <article class="email card h-min p-4 border rounded-lg shadow">
                <h2 class="text-2xl font-semibold mb-2">Email</h2>
                <p>contact@program.com</p>
            </article>
        </section>
    </main>
@endsection
