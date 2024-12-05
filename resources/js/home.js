import { getView } from './utils';
import { addLikeButtonListeners } from "./post.js";

const loadMorePosts = async (homePosts, page) => {
    const posts = await getView(`/`, { page: page });

    if (posts.trim() !== '') {
        homePosts.insertAdjacentHTML('beforeend', posts);
        addLikeButtonListeners();
        return false;
    } else {
        return true;
    }
}

const addHomeEventListeners = () => {
    const homePosts = document.querySelector('#home-posts');
    if (!homePosts) {
        return;
    }

    let loading = false;
    let atEnd = false;
    let page = 1;
    document.addEventListener('scroll', async () => {
        if (!atEnd && !loading && window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            loading = true;
            page++;
            atEnd = await loadMorePosts(homePosts, page);
            loading = false;
        }
    });
}

addHomeEventListeners();