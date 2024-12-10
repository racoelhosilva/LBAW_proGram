import {addLikeButtonListeners} from "./post.js";
import {addLazyLoading} from "./utils.js";

const addSearchListeners = () => {
    const searchPosts = document.getElementById('search-posts');
    const searchUsers = document.getElementById('search-users');
    const searchLoadingSpinner = document.querySelector('#results > div:last-child > .loading-spinner');

    if (!searchLoadingSpinner) {
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (searchPosts) {
        addLazyLoading(searchPosts, searchLoadingSpinner, '/search',
            { search_type: 'posts', query: urlParams.get('query') }, addLikeButtonListeners);
    }
    if (searchUsers) {
        addLazyLoading(searchUsers, searchLoadingSpinner, '/search',
            { search_type: 'users', query: urlParams.get('query') });
    }
}

addSearchListeners();
