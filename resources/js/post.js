import { addLazyLoadingContainer, sendDelete, sendPost, sendToastMessage, addDropdownListeners} from './utils'

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

const addCommentSectionListeners = () => {
	const commentList = document.getElementById('comment-list');
	const commentListLoading = document.querySelector('#comment-list + div .loading-spinner');

	if (!commentList || !commentListLoading) {
		return;
	}

	const url = window.location.href;
	const id = url.split('/post/')[1];

	const commentSection = document.getElementById('comment-section');
	console.log("added listeners!");
	addLazyLoadingContainer(commentSection, commentListLoading, '/post/' + id, null ,addPostListeners);
}

const addPostListeners = () => {
	console.log("added post listeners!");
	addLikeButtonListeners();
	addDropdownListeners();
}

addPostListeners();
addCommentSectionListeners();

export { addPostListeners };
