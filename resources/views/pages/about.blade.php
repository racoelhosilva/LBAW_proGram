@extends('layouts.app')
@section('about') {{'About | ProGram'}} @endsection
@section('content')
    <main id="about-page" class="px-8 ">
        <section>
            <article class="flex flex-col items-center ">
                <h1 class="text-4xl font-bold mb-4 text-blue-600">ProGram</h1>
                <h3 class="text-2xl  mb-4">Where Programmers Build More Than Code</h3>
                <p class="text-xl text-justify ">
                    Welcome to our platform, a dynamic social network tailored specifically for programmers!
                </p>
                <p   class="text-xl text-justify ">
                    Here, users can explore, connect, and showcase their skills in an environment designed to inspire collaboration and growth.
                </p>
            </article>
            <article class="flex flex-col items-center mt-16">
                <h1 class="text-4xl font-bold mb-4 text-blue-600">Main Features</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Card 1 -->
                    <div class="card rounded-lg p-6  col-span-1">
                        <h3 class="text-2xl font-bold mb-4">Discover Public Profiles</h3>
                        <p class="text-xl text-justify">
                            Users can browse through public profiles, gaining insights into the skills and interests of fellow programmers, making it easier to connect and collaborate.
                        </p>
                    </div>
                    <!-- Card 2 -->
                    <div class="card rounded-lg p-6 col-span-1">
                        <h3 class="text-2xl font-bold mb-4">Engage with a Public Timeline</h3>
                        <p class="text-xl text-justify">
                            Dive into the community’s public timeline to explore posts, updates, and discussions that foster a culture of sharing and engagement.
                        </p>
                    </div>
                    <!-- Card 3 -->
                    <div class="card rounded-lg p-6 col-span-1">
                        <h3 class="text-2xl font-bold mb-4">Advanced Search Capabilities</h3>
                        <p class="text-xl text-justify">
                            Utilize robust search functionalities, including full-text and exact match search, combined with filters and multi-attribute capabilities, to find posts, users, or groups that align with your interests.
                        </p>
                    </div>
                    <!-- Card 4 -->
                    <div class="card rounded-lg p-6 col-span-1">
                        <h3 class="text-2xl font-bold mb-4">Personalized User Experience</h3>
                        <p class="text-xl text-justify">
                            Authenticated users enjoy personalized timelines, the ability to follow others, comment on posts, and manage their profiles, making every interaction meaningful and tailored to their preferences.
                        </p>
                    </div>
                    <!-- Card 5 -->
                    <div class="card rounded-lg p-6 col-span-1">
                        <h3 class="text-2xl font-bold mb-4">Build and Join Groups</h3>
                        <p class="text-xl text-justify">
                            Create or join groups that resonate with your interests, connect with like-minded individuals, and participate in group discussions, projects, and activities.
                        </p>
                    </div>
                    <!-- Card 6 -->
                    <div class="card rounded-lg p-6 col-span-1">
                        <h3 class="text-2xl font-bold mb-4">Developer-Focused Features</h3>
                        <p class="text-xl text-justify">
                            Highlight your programming prowess with developer stats, showcase your contributions, and link to external accounts like GitHub to amplify your professional profile.
                        </p>
                    </div>
                </div>
                
                
            </article>
        </section>


@endsection
