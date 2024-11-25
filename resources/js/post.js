import { sendDelete, sendPost } from './utils'

const togglePostLike = (likeButton, postId) => {
    if (likeButton.classList.contains('liked')) {
        sendDelete(`/api/post/${postId}/like`)
            .then(_ => likeButton.classList.remove('liked'))
            .catch(error => console.log(error));
    } else {
        sendPost(`/api/post/${postId}/like`)
            .then(_ => likeButton.classList.add('liked'))
            .catch(error => console.log(error));
    }
}

const addLikeButtonListeners = () => {
    const postCards = document.querySelectorAll('.post-card');

    postCards.forEach(postCard => {
        const postId = postCard.dataset.postId;
        const likeButton = postCard.querySelector('.like-button');

        likeButton.addEventListener('click', () => togglePostLike(likeButton, postId));
    })
}

addLikeButtonListeners();
