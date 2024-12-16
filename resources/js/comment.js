import { sendPost,sendPatch, sendToastMessage ,sendDelete} from './utils';

const addSubmitCommentListener = () => {
    const form = document.getElementById('comment-submit-form');
    if (!form)
        return;

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries()); 
        data.post_id = parseInt(data.post_id,10);
        data.author_id = parseInt(data.author_id,10);
        res = sendPost('/api/comment', data)
        .then((_) => {
            form.reset();
            window.location.reload();
        })
        .catch((error) => {
            sendToastMessage('An error occurred while submiting comment.', 'error');
        });
    
    });
};
const addEditCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');
    comments.forEach((comment) => {
        const editButton = comment.querySelector('.edit-button-container button');

        if (editButton) {
            const onEditClick = () => {
                const contentContainer = comment.querySelector('.content-container');
                const paragraph = contentContainer.querySelector('p');
                const text = paragraph.textContent;

                paragraph.remove();
                const textarea = document.createElement('textarea');
                textarea.classList.add('edit-textarea');
                textarea.textContent = text;
                contentContainer.appendChild(textarea);

                const saveButton = document.createElement('button');
                saveButton.classList.add('primary-btn', 'p-2', 'w-full', 'mt-2', 'update-button');
                saveButton.textContent = 'Save';

                saveButton.addEventListener('click', async () => {
                    const updatedText = textarea.value;
                    const data = { content: updatedText };
                    const commentId = comment.dataset.commentId;

                    sendPatch(`/api/comment/${commentId}`, data)
                        .then(() => {
                            window.location.reload();
                        })
                        .catch((error) => {
                            sendToastMessage('An error occurred while updating the comment.', 'error');
                        });
                });

                comment.appendChild(saveButton);
                editButton.removeEventListener('click', onEditClick);
                editButton.addEventListener('click', () => {
                    window.location.reload();
                });
            };
            editButton.addEventListener('click', onEditClick);
        }
    });
};

const addDeleteCommentListener = () => {
    const commentSection = document.getElementById('comment-section');
    if (!commentSection) return;

    const comments = commentSection.querySelectorAll('.comment-card');

    comments.forEach(comment => {
        const deleteButton = comment.querySelector('.delete-button-container button');
        
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                const commentId = comment.dataset.commentId;
                sendDelete(`/api/comment/${commentId}`)
                    .then((_) => {
                        window.location.reload();
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while deleting comment.', 'error');
                    });
            });
        }
    });
}
addEditCommentListener();
addDeleteCommentListener();

addSubmitCommentListener();


