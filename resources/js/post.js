import { sendDelete, sendPost } from './utils'

const togglePostLike = (likeButton, likeCount, postId) => {
    if (likeButton.classList.contains('liked')) {
        sendDelete(`/api/post/${postId}/like`)
            .then(_ => {
                likeButton.classList.remove('liked');
                likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
            })
            .catch(error => console.log(error));
    } else {
        sendPost(`/api/post/${postId}/like`)
            .then(_ => {
                likeButton.classList.add('liked');
                likeCount.innerHTML = parseInt(likeCount.innerHTML) + 1;
            })
            .catch(error => console.log(error));
    }
}

const addLikeButtonListeners = () => {
    const postCards = document.querySelectorAll('.post-card');

    postCards.forEach(postCard => {
        const postId = postCard.dataset.postId;
        const likeButton = postCard.querySelector('.like-button');
        const likeCount = postCard.querySelector('.like-button + p');

        likeButton.addEventListener('click', () => togglePostLike(likeButton, likeCount, postId));
    })
}

addLikeButtonListeners();
