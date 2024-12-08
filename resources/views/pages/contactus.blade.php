@extends('layouts.app')

@section('title', 'Contact Us | ProGram')

@section('content')
    <main id="contactus-page" class="min-h-[80vh] flex flex-col items-center justify-center px-4">
        <section class="mb-8 text-center">
            <h1 class="text-4xl font-medium">Contact Us</h1>
        </section>
        <section class="flex flex-col  justify-center gap-8 items-center  w-full m-20 max-w-[50rem]">
            <article class="address card  p-4 border rounded-lg shadow w-full">
                <h2 class="text-2xl font-semibold mb-2">Address</h2>
                <p>R. Dr. Roberto Frias, 4200-465 Porto</p>
            </article>
            <article class="phone card p-4 border rounded-lg shadow w-full">
                <h2 class="text-2xl font-semibold mb-2">Phone</h2>
                <p>+351 22 508 1400
                </p>
            </article>
            <article class="email card p-4 border rounded-lg shadow w-full">
                <h2 class="text-2xl font-semibold mb-2">Email</h2>
                <a href="mailto:contact@program.com">contact@program.com</a>
            </article>
        </section>
    </main>
@endsection
