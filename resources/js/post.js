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

const toggleCommentLike = (likeButton, likeCount, commentId) => {
    if (likeButton.classList.contains('liked')) {
        sendDelete(`/api/comment/${commentId}/like`)
            .then(_ => {
                likeButton.classList.remove('liked');
                likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
            })
            .catch(error => console.log(error));
    } else {
        sendPost(`/api/comment/${commentId}/like`)
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
    });

    const commentCards = document.querySelectorAll('.comment-card');

    commentCards.forEach(commentCard => {
        const commentId = commentCard.dataset.commentId;
        const likeButton = commentCard.querySelector('.like-button');
        const likeCount = commentCard.querySelector('.like-button + p');

        likeButton.addEventListener('click', () => toggleCommentLike(likeButton, likeCount, commentId));
    });
}

addLikeButtonListeners();
