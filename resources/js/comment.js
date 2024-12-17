import { sendPost, getView, sendPatch, sendToastMessage ,sendDelete} from './utils';
import { addDropdownListeners } from './app';
const addSubmitCommentListener = () => {
    const form = document.getElementById('comment-submit-form');
    const commentSection = document.querySelector('.comment-list'); // Container for comments

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        console.log("called submit");
        const formData = new FormData(form);
        const params = Object.fromEntries(formData.entries());
        const comment = await getView(form.action, params, 'POST');
        commentSection.insertAdjacentHTML('afterbegin', comment);
        form.reset();
        addDropdownListeners();
        addEditCommentListener();
        addDeleteCommentListener();
        
    });
};

const addEditCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');
    comments.forEach((comment) => {
        console.log("called edit");
        const editButton = comment.querySelector('.edit-button-container button');
        console.log(editButton);
        const contentContainer = comment.querySelector('.content-container');
        const contentEditContainer = comment.querySelector('.edit-content-container');
        if(!editButton) return;
        editButton.addEventListener('click', () => {
            console.log("clicked");
            contentContainer.classList.toggle('hidden');
            contentEditContainer.classList.toggle('hidden');
        });
    });
};

const addDeleteCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');

    comments.forEach(comment => {
        console.log("called delete");
        const deleteButton = comment.querySelector('.delete-button-container button');
        
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                const commentId = comment.dataset.commentId;
                sendDelete(`/api/comment/${commentId}`)
                    .then((_) => {
                        comment.remove();
                    })
                    .catch((error) => {
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
        saveButton.addEventListener('click', async () => {
            console.log("called save");
            event.preventDefault();
            const commentId = comment.dataset.commentId;
            console.log(contentEditForm);
            const formData = new FormData(contentEditForm);
            const params = Object.fromEntries(formData.entries());
            const updatedComment = await getView(`/api/comment/${commentId}`, params, 'PATCH');
            comment.remove();
            commentList.insertAdjacentHTML('afterbegin', updatedComment);
            addDropdownListeners();
            addEditCommentListener();
            addDeleteCommentListener();
        });
    });
}

addSaveCommentListener();
addEditCommentListener();
addDeleteCommentListener();

addSubmitCommentListener();


