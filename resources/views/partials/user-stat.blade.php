<div class=" p-4 rounded-lg text-center">
    <h3 class="text-xl text-white font-semibold">Stats</h3>
    
    @if($user->stats->github_url !== 'NULL')
        <p class="text-lg text-gray-300">
            <a href="{{ $user->stats->github_url }}" class="text-blue-500" target="_blank">GitHub</a>
        </p>
    @else
        <p class="text-lg text-gray-300">GitHub: Not available</p>
    @endif

    @if($user->stats->gitlab_url !== 'NULL')
        <p class="text-lg text-gray-300">
            <a href="{{ $user->stats->gitlab_url }}" class="text-blue-500" target="_blank">GitLab</a>
        </p>
    @else
        <p class="text-lg text-gray-300">GitLab: Not available</p>
    @endif

    @if($user->stats->linkedin_url !== 'NULL')
        <p class="text-lg text-gray-300">
            <a href="{{ $user->stats->linkedin_url }}" class="text-blue-500" target="_blank">LinkedIn</a>
        </p>
    @else
        <p class="text-lg text-gray-300">LinkedIn: Not available</p>
    @endif
    
    
</div>
