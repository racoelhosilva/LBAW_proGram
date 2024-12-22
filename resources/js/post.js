import {
    addLazyLoadingContainer,
    sendDelete,
    sendPost, sendPostView,
    sendToastMessage
} from "./utils.js";
import {addCommentListeners} from "./comment.js";
import {addDropdownListeners, addModalListeners} from "./component.js";

const togglePostLike = (likeButton, likeCount, postId) => {
    likeButton.disabled = true;
    if (likeButton.classList.contains("liked")) {
        sendDelete(`/api/post/${postId}/like`)
            .then(_ => {
                likeButton.classList.remove("liked");
                likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
                likeButton.disabled = false;
            })
            .catch(_ => {
                likeButton.disable = false;
                sendToastMessage('An error occurred while unliking.', 'error');
            });
    } else {
        sendPost(`/api/post/${postId}/like`)
            .then(_ => {
                likeButton.classList.add("liked");
                likeCount.innerHTML = parseInt(likeCount.innerHTML) + 1;
                likeButton.disabled = false;
            })
            .catch(_ => {
                likeButton.disabled = false;
                sendToastMessage('An error occurred while liking.', 'error');
            });
    }
};

const toggleCommentLike = (likeButton, likeCount, commentId) => {
    likeButton.disabled = true;
    if (likeButton.classList.contains("liked")) {
        sendDelete(`/api/comment/${commentId}/like`)
            .then(_ => {
                likeButton.classList.remove("liked");
                likeCount.innerHTML = parseInt(likeCount.innerHTML) - 1;
                likeButton.disabled = false;
            })
            .catch(_ => {
                likeButton.disabled = false;
                sendToastMessage('An error occurred while unliking.', 'error');
            });
    } else {
        sendPost(`/api/comment/${commentId}/like`)
            .then(_ => {
                likeButton.classList.add("liked");
                likeCount.innerHTML = parseInt(likeCount.innerHTML) + 1;
                likeButton.disabled = false;
            })
            .catch(_ => {
                likeButton.disabled = false;
                sendToastMessage('An error occurred while liking.', 'error');
            });
    }
};

const addLikeButtonListeners = () => {
    const postCards = document.querySelectorAll(".post-card");

    postCards.forEach((postCard) => {
        const postId = postCard.dataset.postId;
        const likeButton = postCard.querySelector(".like-button");
        const likeCount = postCard.querySelector(".like-button + p");
        likeButton.onclick = () =>	{
            if (likeButton.classList.contains('enabled')) {
                togglePostLike(likeButton, likeCount, postId);
            } else {
                switch (likeButton.dataset.disabledReason) {
                    case 'not-logged-in':
                        sendToastMessage('You must be logged in to like a post.', 'info');
                        break;
                    case 'is-owner':
                        sendToastMessage('You cannot like your own post.', 'info');
                        break;
                    default:
                        sendToastMessage('You are not authorized to like this post.', 'info');
                        break;
                }
            }
        }
    });

    const commentCards = document.querySelectorAll(".comment-card");

    commentCards.forEach((commentCard) => {
        const commentId = commentCard.dataset.commentId;
        const likeButton = commentCard.querySelector(".like-button");
        const likeCount = commentCard.querySelector(".like-button + p");

        likeButton.onclick = () =>	{
            if (likeButton.classList.contains('enabled')) {
                toggleCommentLike(likeButton, likeCount, commentId);
            } else {
                switch (likeButton.dataset.disabledReason) {
                    case 'not-logged-in':
                        sendToastMessage('You must be logged in to like a comment.', 'info');
                        break;
                    case 'is-owner':
                        sendToastMessage('You cannot like your own comment.', 'info');
                        break;
                    default:
                        sendToastMessage('You are not authorized to like this comment.', 'info');
                        break;
                }
            }
        }
    });
};

const addPostListeners = () => {
    addLikeButtonListeners();
    addDropdownListeners();
    addModalListeners();
}

const addCommentSectionListeners = () => {
    const commentList = document.getElementById('comment-list');
    const commentListLoading = document.querySelector('#comment-list + div .loading-spinner');
    const commentSection = document.getElementById('comment-section');

    if (!commentList || !commentListLoading) {
        return;
    }

    const url = window.location.href;
    const id = url.split('/post/')[1];

    addLazyLoadingContainer(commentSection, commentListLoading, '/post/' + id, null, addCommentListeners);
}

const addSubmitCommentListener = () => {
    const form = document.getElementById('comment-submit-form');
    const commentSection = document.querySelector('#comment-list'); // Container for comments
    if (!form || !commentSection)
        return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const params = Object.fromEntries(formData.entries());

        const comment = await sendPostView(form.action, params);
        commentSection.insertAdjacentHTML('afterbegin', comment);
        form.reset();

        addDropdownListeners();
        addCommentListeners();
        addPostListeners();
        addModalListeners();

        sendToastMessage('Comment submitted successfully.', 'success');
    });
};

export { addLikeButtonListeners, addPostListeners, addSubmitCommentListener, addCommentSectionListeners };
