import {addLikeButtonListeners} from "./post.js";
import {addLazyLoading} from "./utils.js";

const addSearchListeners = () => {
    const searchPosts = document.getElementById('search-posts');
    const searchUsers = document.getElementById('search-users');
    const searchLoadingSpinner = document.querySelector('#search-results > div:last-child > .loading-spinner');
    const searchFilters = document.querySelectorAll('#search-filters .multiselect');
    const searchField = document.getElementById('search-field');

    if (!searchLoadingSpinner || !searchField || !searchFilters) {
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

    searchFilters.forEach(searchFilter => searchFilter.addEventListener('keypress', event => {
        if (event.key === 'Enter') {
            searchField.submit();
            event.stopPropagation();
        }
    }));
}

addSearchListeners();
