import {
    addDropdownListeners,
    addModalListeners,
    sendDelete,
    sendPutView,
    sendToastMessage
} from "./utils.js";
import {addPostListeners} from "./post.js";


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
                        sendToastMessage('Comment deleted successfully.', 'success');
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

                sendToastMessage('Comment updated successfully.', 'success');
            };
        }
    });
}

const addCommentListeners = () => {
    addDropdownListeners();
    addEditCommentListener();
    addDeleteCommentListener();
    addSaveCommentListener();
    addModalListeners();
}

export { addCommentListeners };