const seeUserResults = (postResults, userResults) => {
    userResults.classList.remove('hidden');
    userResults.classList.add('flex');

    postResults.classList.add('hidden');
    postResults.classList.remove('flex');
}

const seePostResults = (postResults, userResults) => {
    userResults.classList.add('hidden');
    userResults.classList.remove('flex');

    postResults.classList.remove('hidden');
    postResults.classList.add('flex');
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
        seePostResults(postResults, userResults);
    });
    seeUsersButton.addEventListener('click', () => {
        seeUserResults(postResults, userResults);
    });
}

addSearchListeners();
