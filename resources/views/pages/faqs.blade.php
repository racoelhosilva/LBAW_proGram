@extends('layouts.app')
@section('title', 'FAQs | ProGram')
@section('content')
    @php
        $faqs = [
            ['question' => 'What is the aim of ProGram?', 'answer' => 'ProGram aims to bring together programmers, developers and other people passionate about informatics, while providing a supportive environment for communication and discussion. Our vision is to create a safe space where like-minded individuals can share ideas, learn new tricks and even start projects together.'],
            ['question' => 'Can I link external accounts?', 'answer' => 'Yes, ProGram lets you link GitLab, GitHub and LinkedIn accounts, so that people can find your profile on those platforms and discover more about you and your projects'],
            ['question' => 'How many projects can I have on my profile?', 'answer' => 'The maximum number of projects allowed is 10.'],
            ['question' => 'What type of content can my post have?', 'answer' => 'Your post can contain text ,images, videos, links and tags.'],
            ['question' => 'How do I change my profile picture and banner?', 'answer' => 'You can change your profile picture or your banner by visiting your profile settings and uploading a new image from your device.'],
            ['question' => 'How can I create a post', 'answer' => 'You can create a post by clicking the dropdown on the top right of the page and choosing the create post option. You will need to provide a title, content for your post, tags for the post and specify its visibility and if it is an announcement.'],
            ['question' => 'Can I delete my posts?', 'answer' => 'Yes, you can delete any post you\'ve created. Simply visit the post and click on the delete option to remove it from the platform.'],
            ['question' => 'Can I edit my posts?', 'answer' => 'Yes, you can edit any post you\'ve created. Simply visit the post and click on the edit option to make changes.'],
            ['question' => 'How can I join a private group?', 'answer' => 'You can request to join a private group by visiting the group page and clicking on the join button. The group admin will then review your request and decide whether to accept or reject it.'],
            ['question' => 'How can I create a group?', 'answer' => 'You can create a group by clicking the dropdown on the top right of the page and choosing the create group option. You will need to provide a name, description and privacy setting for your group.'],
            ['question' => 'How can I delete my account?', 'answer' => 'You can delete your account by visiting your profile settings and clicking on the delete account option. You will be asked to confirm your decision before your account is permanently removed.'],
            ['question' =>' Who can view posts in a private group?', 'answer' => 'Only members of the group can view posts in a private group.'],
            ['question' =>'Do group posts appear on my profile?', 'answer' => 'Yes, the posts may appear on your profile but only for the users that have permission to see them, if the post belongs to a private group that means that only members of that group will be able to see the post.']
        ];
    @endphp

    <main id="faqs-page" class="px-8">
        <section>
            <h1 class="text-3xl text-center font-bold mb-4">Frequently Asked Questions</h1>
            @foreach ($faqs as $faq)
                <article class="card faq-container h-min mt-4 mb-4 p-4 cursor-pointer ">
                    <div class="flex items-center justify-between px-4">
                        <h1 class="text-xl font-bold question ">{{ $faq['question'] }}</h1>
                        <div class="arrow-up-icon-container">
                            @include('partials.icon', ['name' => 'plus', 'class' => 'max-w-10'])
                        </div>
                        <div class="arrow-down-icon-container hidden">
                            @include('partials.icon', ['name' => 'x', 'class' => 'max-w-10'])
                        </div>
                    </div>
                    <p class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out answer px-4">
                        {{ $faq['answer'] }}
                    </p>
                </article>
            @endforeach
        </section>
    </main>
@endsection
