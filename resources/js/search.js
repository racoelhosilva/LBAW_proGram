import {addPostListeners} from "./post.js";
import {addLazyLoading} from "./utils.js";

const addSearchListeners = () => {
    const searchPosts = document.getElementById('search-posts');
    const searchUsers = document.getElementById('search-users');
    const searchGroups = document.getElementById('search-groups');
    const searchLoadingSpinner = document.querySelector('#search-results > div:last-child > .loading-spinner');
    const searchFilters = document.querySelectorAll('#search-filters .select');
    const searchField = document.getElementById('search-field');

    if (!searchLoadingSpinner || !searchField || !searchFilters) {
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const searchParams = {
        query: urlParams.get('query'),
        tags: urlParams.getAll('tags[]'),
        search_attr: urlParams.get('search_attr'),
        order_by: urlParams.get('order_by'),
    };
    if (searchPosts) {
        addLazyLoading(searchPosts, searchLoadingSpinner, '/search', { ...searchParams, search_type: 'posts' }, addPostListeners);
    }
    if (searchUsers) {
        addLazyLoading(searchUsers, searchLoadingSpinner, '/search', { ...searchParams, search_type: 'users' });
    }
    if (searchGroups) {
        addLazyLoading(searchGroups, searchLoadingSpinner, '/search', { ...searchParams, search_type: 'groups' });
    }
}

addSearchListeners();
