import { sendPost,sendPatch, sendToastMessage ,sendDelete} from './utils';

const addSubmitCommentListener = () => {
    const form = document.getElementById('comment-submit-form');
    console.log(form);
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        console.log('Submitting comment...');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries()); 
        data.post_id = parseInt(data.post_id,10);
        data.author_id = parseInt(data.author_id,10);
        console.log(data);
        res = sendPost('/api/comment', data)
        .then((_) => {
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
    console.log("hi")

    const comments = commentSection.querySelectorAll('.comment-card');
    console.log(comments)
    
    comments.forEach(comment => {

        const editButton = comment.querySelector('.edit-button-container button');
        console.log(editButton)
        
        if (editButton) {
            editButton.addEventListener('click', () => {
               const contentContainer = comment.querySelector('.content-container');
               const paragraph = contentContainer.querySelector('p');
               const text = paragraph.textContent;
               paragraph.remove();
               const textarea = document.createElement('textarea');
               textarea.classList.add(
                'w-full',
                'px-5',
                'py-3',
                'rounded-lg',
                'bg-white',
                'dark:bg-slate-700',
                'text-gray-600',
                'dark:text-white',
                'placeholder-gray-500',
                'dark:placeholder-gray-400',
                'border',
                'border-slate-300',
                'dark:border-slate-600',
                'focus:border-blue-600',
                'outline-none',
                'resize-none',
                'min-h-32'
            );
               textarea.textContent = text;
               contentContainer.appendChild(textarea);
               const button = document.createElement('button');
                button.classList.add(
                    'primary-btn',
                    'p-2',
                    'w-full',
                    'mt-2',
                    'update-button'
                    
                )
               
               button.textContent = 'Save';
               button.addEventListener('click', async () => {
                console.log('Updating comment...');
                const updatedText = textarea.value;
                const data ={}
                data.content=updatedText;
                const commentId = comment.dataset.commentId;
                sendPatch(`/api/comment/${commentId}`, data)
                    .then((_) => {
                        window.location.reload();
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while updating comment.', 'error');
                    }); 
                });

               comment.appendChild(button);


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
        
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                const commentId = comment.dataset.commentId;
                console.log('Deleting comment...');
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


