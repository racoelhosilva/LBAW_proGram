@extends('layouts.app')

@section('title', 'About Us | ProGram')

@section('content')
    <main id="about-page" class="px-8">
        <section class="flex flex-col items-center">
            <h1 class="text-4xl font-bold mb-4">ProGram</h1>
            <h2 class="text-2xl mb-4">Where Programmers Build More Than Code</h2>
            <p class="text-xl text-justify">
                Welcome to our platform, a dynamic social network tailored specifically for programmers!
            </p>
            <p class="text-xl text-justify">
                Here, users can explore, connect, and showcase their skills in an environment designed to inspire collaboration and growth.
            </p>
        </section>
        <section class="flex flex-col items-center mt-16">
            <h1 class="text-4xl font-bold mb-4">Main Features</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <article class="card rounded-lg p-6  col-span-1">
                    <h2 class="text-2xl font-bold mb-4">Discover Public Profiles</h2>
                    <p class="text-xl text-justify">
                        Users can browse through public profiles, gaining insights into the skills and interests of fellow programmers, making it easier to connect and collaborate.
                    </p>
                </article>
                <!-- Card 2 -->
                <article class="card rounded-lg p-6 col-span-1">
                    <h2 class="text-2xl font-bold mb-4">Engage with a Public Timeline</h2>
                    <p class="text-xl text-justify">
                        Dive into the community’s public timeline to explore posts, updates, and discussions that foster a culture of sharing and engagement.
                    </p>
                </article>
                <!-- Card 3 -->
                <article class="card rounded-lg p-6 col-span-1">
                    <h2 class="text-2xl font-bold mb-4">Advanced Search Capabilities</h2>
                    <p class="text-xl text-justify">
                        Utilize robust search functionalities, including full-text and exact match search, combined with filters and multi-attribute capabilities, to find posts, users, or groups that align with your interests.
                    </p>
                </article>
                <!-- Card 4 -->
                <article class="card rounded-lg p-6 col-span-1">
                    <h2 class="text-2xl font-bold mb-4">Personalized User Experience</h2>
                    <p class="text-xl text-justify">
                        Authenticated users enjoy personalized timelines, the ability to follow others, comment on posts, and manage their profiles, making every interaction meaningful and tailored to their preferences.
                    </p>
                </article>
                <!-- Card 5 -->
                <article class="card rounded-lg p-6 col-span-1">
                    <h2 class="text-2xl font-bold mb-4">Build and Join Groups</h2>
                    <p class="text-xl text-justify">
                        Create or join groups that resonate with your interests, connect with like-minded individuals, and participate in group discussions, projects, and activities.
                    </p>
                </article>
                <!-- Card 6 -->
                <article class="card rounded-lg p-6 col-span-1">
                    <h2 class="text-2xl font-bold mb-4">Developer-Focused Features</h2>
                    <p class="text-xl text-justify">
                        Highlight your programming prowess with developer stats, showcase your contributions, and link to external accounts like GitHub to amplify your professional profile.
                    </p>
                </article>
            </div>
        </section>
        <section class="flex flex-col items-center mt-16">
            <h1 class="text-4xl font-bold mb-4">Our Team</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-16 m-8">
                <div class="flex flex-col items-center gap-4">
                    <img src="{{ asset('devs/bruno.jpg') }}" class="rounded-full border-2 border-gray-300 w-32 h-32 object-cover">
                    <p class="text-wrap font-bold">Bruno Oliveira</p>
                    <div class="flex gap-2">
                        @include('partials.icon-button', ['iconName' => 'github', 'label' => 'GitHub', 'type' => 'transparent', 'anchorUrl' => 'https://github.com/process-ing'])
                        @include('partials.icon-button', ['iconName' => 'linkedin', 'label' => 'LinkedIn', 'type' => 'transparent', 'anchorUrl' => 'https://linkedin.com/in/brunool'])
                    </div>
                </div>
                <div class="flex flex-col items-center gap-4">
                    <img src="{{ asset('devs/henrique.jpg') }}" class="rounded-full border-2 border-gray-300 w-32 h-32 object-cover">
                    <p class="text-wrap font-bold">Henrique Fernandes</p>
                    <div class="flex gap-2">
                        @include('partials.icon-button', ['iconName' => 'github', 'label' => 'GitHub', 'type' => 'transparent', 'anchorUrl' => 'https://github.com/HenriqueSFernandes/'])
                        @include('partials.icon-button', ['iconName' => 'linkedin', 'label' => 'LinkedIn', 'type' => 'transparent', 'anchorUrl' => 'https://www.linkedin.com/in/-henriquesfernandes/'])
                    </div>
                </div>
                <div class="flex flex-col items-center gap-4">
                    <img src="{{ asset('devs/jose.jpg') }}" class="rounded-full border-2 border-gray-300 w-32 h-32 object-cover">
                    <p class="text-wrap font-bold">José Sousa</p>
                    <div class="flex gap-2">
                        @include('partials.icon-button', ['iconName' => 'github', 'label' => 'GitHub', 'type' => 'transparent', 'anchorUrl' => 'https://github.com/jose-carlos-sousa'])
                        @include('partials.icon-button', ['iconName' => 'linkedin', 'label' => 'LinkedIn', 'type' => 'transparent', 'anchorUrl' => 'https://www.linkedin.com/in/jos%C3%A9-sousa-70b048212/'])
                    </div>
                </div>
                <div class="flex flex-col items-center gap-4">
                    <img src="{{ asset('devs/rodrigo.jpg') }}" class="rounded-full border-2 border-gray-300 w-32 h-32 object-cover">
                    <p class="text-wrap font-bold">Rodrigo Coelho</p>
                    <div class="flex gap-2">
                        @include('partials.icon-button', ['iconName' => 'github', 'label' => 'GitHub', 'type' => 'transparent', 'anchorUrl' => 'https://github.com/racoelhosilva'])
                        @include('partials.icon-button', ['iconName' => 'linkedin', 'label' => 'LinkedIn', 'type' => 'transparent', 'anchorUrl' => 'https://linkedin.com/in/racoelhosilva'])
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
