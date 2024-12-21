import { sendPostView, sendPutView, sendToastMessage ,sendDelete, addDropdownListeners } from './utils';
import { addModalListeners } from './app';
import { addPostListeners } from './post';

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
            const updatedComment = await sendPutView(contentEditForm.action, params, 'PATCH');
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

addSaveCommentListener();
addEditCommentListener();
addDeleteCommentListener();
addSubmitCommentListener();
