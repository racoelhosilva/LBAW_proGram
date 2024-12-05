import { getView } from './utils';

const loadMorePosts = async (homePosts) => {
    const posts = await getView('/');
    homePosts.insertAdjacentHTML('beforeend', posts);
}

const addHomeEventListeners = () => {
    const homePosts = document.querySelector('#home-posts');
    if (!homePosts) {
        return;
    }

    let loading = false;
    document.addEventListener('scroll', async () => {
        if (!loading && window.innerHeight + window.scrollY >= document.body.offsetHeight - 100) {
            loading = true;
            await loadMorePosts(homePosts);
            loading = false;
        }
    });
}

addHomeEventListeners();