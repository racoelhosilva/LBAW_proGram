const seeUserResults = (seePostsButton, seeUsersButton, postResults, userResults) => {
    userResults.classList.remove('hidden');
    userResults.classList.add('flex');

    postResults.classList.add('hidden');
    postResults.classList.remove('flex');

    seePostsButton.classList.remove('font-bold');
    seePostsButton.classList.add('font-medium');

    seeUsersButton.classList.remove('font-medium');
    seeUsersButton.classList.add('font-bold');
}

const seePostResults = (seePostsButton, seeUsersButton, postResults, userResults) => {
    userResults.classList.add('hidden');
    userResults.classList.remove('flex');

    postResults.classList.remove('hidden');
    postResults.classList.add('flex');

    seeUsersButton.classList.remove('font-bold');
    seeUsersButton.classList.add('font-medium');
    
    seePostsButton.classList.remove('font-medium');
    seePostsButton.classList.add('font-bold');
}

const addSearchListeners = () => {
    const seePostsButton = document.getElementById('see-posts-button');
    const seeUsersButton = document.getElementById('see-users-button');
    const userResults = document.getElementById('user-results');
    const postResults = document.getElementById('post-results');

    if (!seePostsButton || !seeUsersButton || !userResults || !postResults) {
        return;
    }

    seePostsButton.addEventListener('click', () => {
        seePostResults(seePostsButton, seeUsersButton, postResults, userResults);
    });
    seeUsersButton.addEventListener('click', () => {
        seeUserResults(seePostsButton, seeUsersButton, postResults, userResults);
    });
}

addSearchListeners();
