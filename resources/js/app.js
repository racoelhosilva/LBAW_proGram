import "./bootstrap";
import {addDropdownListeners, fadeToastMessage, hideDropdowns, sendToastMessage, addLazyLoading,addLazyLoadingContainer, sendDelete, sendPost, sendPostView, sendPutView} from "./utils";
import Quill from "quill";

const openModal = (modal, event) => {
	modal.classList.add("active");
	event.stopPropagation();
};

const closeModal = (modal, event) => {
	modal.classList.remove("active");
    hideDropdowns(event);
	event.stopPropagation();
};

const addModalListeners = () => {
	const modals = document.querySelectorAll(".modal");

    modals.forEach(modal => {
        const modalOpenButton = modal.querySelector(`:scope .open-button`);
        const modalContent = modal.querySelector(':scope > div');
        const modalCloseButtons = modal.querySelectorAll(':scope .close-button');

        modalContent.addEventListener('click', event => event.stopPropagation());
        modalOpenButton.addEventListener('click', event => openModal(modal, event));
        modalCloseButtons.forEach(closeButton => {
            closeButton.addEventListener('click', event => closeModal(modal, event));
        });
    });
}

const addToastMessageListeners = () => {
	document.addEventListener("DOMContentLoaded", () => {
		const toastMessages = document.querySelectorAll(
			".toast-message:not(.hidden)",
		);

		toastMessages.forEach(fadeToastMessage);
	});
};

const activateQuill = () => {
	const form = document.querySelector(".quill-form");

	if (form) {
		const field = form.querySelector(
			`input[name="${form.dataset.quillField}"]`,
		);
		const quill = new Quill("#quill-editor", {
			modules: {
				toolbar: {
					container: [
						["bold", "italic", "underline", "code"],
						[{ list: "ordered" }, { list: "bullet" }, "code-block"],
						["link", "image", "video"],
						["clean"],
					],
					handlers: {
						image: function() {
							const input = document.createElement("input");
							input.setAttribute("type", "file");
							input.setAttribute("accept", "image/*");
							input.click();

							input.onchange = async () => {
								const file = input.files[0];
								if (file) {
									const formData = new FormData();
									formData.append("file", file);
									formData.append("type", "temporary");
									formData.append(
										"user_id",
										document
											.querySelector('meta[name="user-id"]')
											.getAttribute("content"),
									);

									const response = await fetch("/api/upload-file", {
										method: "POST",
										headers: {
											"X-CSRF-TOKEN": document.querySelector(
												'meta[name="csrf-token"]',
											).content,
										},
										body: formData,
									});

									if (response.ok) {
										const { url } = await response.json();
										const range = quill.getSelection();
										quill.insertEmbed(range.index, "image", url);
									}
								}
							};
						},
						video: function() {
							const input = document.createElement("input");
							input.setAttribute("type", "file");
							input.setAttribute("accept", "video/*");
							input.click();

							input.onchange = async () => {
								const file = input.files[0];
								if (file) {
									const formData = new FormData();
									formData.append("file", file);
									formData.append("type", "temporary");
									formData.append(
										"user_id",
										document
											.querySelector('meta[name="user-id"]')
											.getAttribute("content"),
									);

									const response = await fetch("/api/upload-file", {
										method: "POST",
										headers: {
											"X-CSRF-TOKEN": document.querySelector(
												'meta[name="csrf-token"]',
											).content,
										},
										body: formData,
									});
									if (response.ok) {
										const { url } = await response.json();
										const range = quill.getSelection();
										quill.insertEmbed(range.index, "video", url);
									}
								}
							};
						},
					},
				},
			},
			placeholder: "Write something amazing...",
			theme: "snow",
		});

		quill.clipboard.dangerouslyPasteHTML(field.value ?? "", "silent");

		form.onsubmit = async () => {
			field.value = quill.root.innerHTML;
		};
	}
};

const toggleSelect = (select, event) => {
	select.classList.toggle('closed');
	event.stopPropagation();
};

const closeSelect = (select, event) => {
	select.classList.add('closed');
	event.stopPropagation();
}

const updateSelect = (select, selectedOptionsText) => {
	const selectedOptions = select.querySelectorAll(':scope label:has(input:checked) span');

	selectedOptionsText.textContent = Array.from(selectedOptions)
		.map(option => option.textContent)
		.join(", ");
};

const addSelectListeners = () => {
	const selects = document.querySelectorAll('.select');

	selects.forEach(select => {
		const selectDropdown = select.querySelector(':scope > div');
		const selectedOptionsText = select.querySelector(':scope .selected-options');
		const selectForm = document.getElementById(select.dataset.form);

		select.addEventListener('click', event => {
			toggleSelect(select, event);
			selects.forEach(otherSelect => {
				if (otherSelect !== select)
					closeSelect(otherSelect, event)
			});
		});

		selectDropdown.addEventListener('click', () => updateSelect(select, selectedOptionsText));
		select.addEventListener('keypress', event => {
			if (event.key === 'Enter') {
				selectForm.submit();
				event.stopPropagation();
			}
		});
		document.addEventListener('click', event => closeSelect(select, event));
	});
}

const addResponsiveDropdownListeners = () => {
	const dropdowns = document.querySelectorAll('.responsive-dropdown');

	dropdowns.forEach(dropdown => {
		const dropdownButton = dropdown.querySelector(':scope > button');

		dropdownButton.addEventListener('click', () => dropdown.classList.toggle('closed'));
	});
};

const addCopyButtonListeners = () => {
    const copyButtons = document.querySelectorAll('.copy-button');

    copyButtons.forEach(copyButton => {
        copyButton.addEventListener('click', () => {
            const copyText = copyButton.querySelector('span').textContent;

            navigator.clipboard.writeText(copyText).then(() => {
                sendToastMessage('Copied to clipboard!', 'success');
            });
        });
    });
};

const addSubmitCommentListener = () => {
    const form = document.getElementById('comment-submit-form');
    if (!form) return;
    const commentSection = document.querySelector('#comment-list'); // Container for comments
    if(!commentSection) return;
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const params = Object.fromEntries(formData.entries());
        const comment = await sendPostView(form.action, params);
        commentSection.insertAdjacentHTML('afterbegin', comment);
        form.reset();
        addDropdownListeners();
        addEditCommentListener();
        addDeleteCommentListener();
        addSaveCommentListener();
        addPostListeners();
        addModalListeners();
    });
};

const addEditCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');
    comments.forEach((comment) => {
        const editButton = comment.querySelector('.comment-actions .edit-comment');
        const contentContainer = comment.querySelector('.content-container');
        const contentEditContainer = comment.querySelector('.edit-content-container');
        if(editButton) {
            editButton.onclick = () => {
                contentContainer.classList.toggle('hidden');
                contentEditContainer.classList.toggle('hidden');
            };
        }
    });
};

const addDeleteCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');
    comments.forEach(comment => {
        const deleteButton = comment.querySelector(' .comment-actions .delete-comment');

        if (deleteButton) {
            deleteButton.onclick = () => {
                const commentId = comment.dataset.commentId;
                sendDelete(`/api/comment/${commentId}`)
                    .then((_) => {
                        comment.remove();
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while deleting comment.', 'error');
                    });
            };
        }
    });
}

const addSaveCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');

    comments.forEach(comment => {
        const saveButton = comment.querySelector('.edit-comment-form button');
        const contentEditForm = comment.querySelector('.edit-comment-form');
        if (saveButton) {
            saveButton.onclick = async (event) => {
            event.preventDefault();
            const formData = new FormData(contentEditForm);
            const params = Object.fromEntries(formData.entries());
            const updatedComment = await sendPutView(contentEditForm.action, params);
            comment.outerHTML = updatedComment;
            addDropdownListeners();
            addEditCommentListener();
            addDeleteCommentListener();
            addSaveCommentListener();
            addPostListeners();
            addModalListeners();
            };
        }
    });
}

const addHomeEventListeners = () => {
    const homePosts = document.getElementById('home-posts');
    const homePostsLoading = document.querySelector('#home-posts + div .loading-spinner');
    if (!homePosts || !homePostsLoading) {
        return;
    }

    addLazyLoading(homePosts, homePostsLoading, '/', null, addPostListeners);
}

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
	const commentSection = document.getElementById('comment-section');

	if (!commentList || !commentListLoading) {
		return;
	}

	const url = window.location.href;
	const id = url.split('/post/')[1];

	addLazyLoadingContainer(commentSection, commentListLoading, '/post/' + id, null ,addPostListeners);
}

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

const addPostListeners = () => {
	addLikeButtonListeners();
	addDropdownListeners();
}

addDropdownListeners();
addModalListeners();
addToastMessageListeners();
activateQuill();
addSelectListeners();
addResponsiveDropdownListeners();
addCopyButtonListeners();

addHomeEventListeners();
addSaveCommentListener();
addEditCommentListener();
addDeleteCommentListener();
addSubmitCommentListener();
addPostListeners();
addCommentSectionListeners();
addSearchListeners();

export { addModalListeners, addToastMessageListeners, addSelectListeners };
