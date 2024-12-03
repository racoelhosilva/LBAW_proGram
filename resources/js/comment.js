import { sendPost } from './utils';

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
addSubmitCommentListener();


