import { sendPostView, sendPutView, sendToastMessage ,sendDelete} from './utils';
import { addDropdownListeners } from './app';
import { addLikeButtonListeners } from './post';
const addSubmitCommentListener = () => {
    const form = document.getElementById('comment-submit-form');
    const commentSection = document.querySelector('.comment-list'); // Container for comments

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const params = Object.fromEntries(formData.entries());
        const comment = await sendPostView(form.action, params, 'POST');
        commentSection.insertAdjacentHTML('afterbegin', comment);
        form.reset();
        addDropdownListeners();
        addEditCommentListener();
        addDeleteCommentListener();
        addSaveCommentListener();
        addLikeButtonListeners();
    });
};


const addEditCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');
    comments.forEach((comment) => {
        const editButton = comment.querySelector('.edit-button-container button');
        const contentContainer = comment.querySelector('.content-container');
        const contentEditContainer = comment.querySelector('.edit-content-container');
        if(editButton  && !editButton.classList.contains('has-edit-listener')) {
            editButton.classList.add('has-edit-listener');
            editButton.addEventListener('click', () => {
                contentContainer.classList.toggle('hidden');
                contentEditContainer.classList.toggle('hidden');
            });
    }
    });
};

const addDeleteCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');
    comments.forEach(comment => {
        const deleteButton = comment.querySelector('.delete-button-container button');
        
        if (deleteButton && !deleteButton.classList.contains('has-delete-listener')) {
            deleteButton.classList.add('has-delete-listener');
            deleteButton.addEventListener('click', () => {
                const commentId = comment.dataset.commentId;
                sendDelete(`/api/comment/${commentId}`)
                    .then((_) => {
                        comment.remove();
                    })
                    .catch((error) => {
                        console.error('Error deleting comment:', error);
                        sendToastMessage('An error occurred while deleting comment.', 'error');
                    });
            });
        }
    });
}

const addSaveCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    const commentList = document.querySelector('.comment-list');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');

    comments.forEach(comment => {
        const saveButton = comment.querySelector('.edit-comment-form button');
        const contentEditForm = comment.querySelector('.edit-comment-form');
        if(saveButton && !saveButton.classList.contains('has-save-listener')) {
            saveButton.classList.add('has-save-listener');
            saveButton.addEventListener('click', async (event) => {
                event.preventDefault();
                const formData = new FormData(contentEditForm);
                const params = Object.fromEntries(formData.entries());
                const updatedComment = await sendPutView(contentEditForm.action, params, 'PATCH');
                comment.outerHTML = updatedComment;
                addDropdownListeners();
                addEditCommentListener();
                addDeleteCommentListener();
                addSaveCommentListener();
                addLikeButtonListeners();
            });
        }
    });
}

addSaveCommentListener();
addEditCommentListener();
addDeleteCommentListener();
addSubmitCommentListener();


