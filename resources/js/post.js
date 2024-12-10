import { sendDelete, sendPost, sendToastMessage } from './utils'

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
			if (likeButton.classList.contains('enabled'))
				togglePostLike(likeButton, likeCount, postId);
			else
				sendToastMessage('You must be logged in to like a post.', 'info');
		}
	});

	const commentCards = document.querySelectorAll(".comment-card");

	commentCards.forEach((commentCard) => {
		const commentId = commentCard.dataset.commentId;
		const likeButton = commentCard.querySelector(".like-button");
		const likeCount = commentCard.querySelector(".like-button + p");

		likeButton.onclick = () =>	{
			if (likeButton.classList.contains('enabled'))
				toggleCommentLike(likeButton, likeCount, commentId);
			else
				sendToastMessage('You must be logged in to like a comment.', 'info');
		}
    });
};

addLikeButtonListeners();

export { addLikeButtonListeners };
