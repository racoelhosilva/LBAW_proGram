import { addLazyLoading } from "./utils.js";
import { addLikeButtonListeners } from "./post.js";

const addHomeEventListeners = () => {
    const homePosts = document.getElementById('home-posts');
    const homePostsLoading = document.querySelector('#home-posts + div .loading-spinner');
    if (!homePosts || !homePostsLoading) {
        return;
    }

    addLazyLoading(homePosts, homePostsLoading, '/', null, addLikeButtonListeners);
}

addHomeEventListeners();