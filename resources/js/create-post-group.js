import { sendPost, sendToastMessage } from './utils';

const submitPostForm = () => {
    const handleFormSubmit = (event) => {
        event.preventDefault();

        const form = document.getElementById('create-post-form');
        const formData = new FormData(form);

        const groupId = document.getElementById('group_id').value;

        const data = {
            title: formData.get('title'),
            text: formData.get('text'),
            tags: formData.getAll('tags[]'),
            is_public: formData.has('is_public') ? 1 : 0,
            is_announcement: formData.has('is_announcement') ? 1 : 0
        };

        sendPost("/api/post", data)
            .then(responseData => {
   
                const postId = responseData.id;

                const postRouteUrl = `/api/group/${groupId}/post/${postId}`;
                    
                sendPost(postRouteUrl, {})
                    .then(() => {
                        sendToastMessage('Post created and handled successfully!', 'success');
                        window.location.href = `/group/${groupId}`;
                    })
                    .catch((error) => {
                        sendToastMessage('Error with handling post after creation.', 'error');
                    });
            })
            .catch((error) => {
                sendToastMessage('There was an issue submitting the post.', 'error');
            });
    }

    const formElement = document.getElementById('create-post-form');
    if (formElement) {
        formElement.addEventListener('submit', handleFormSubmit);
    }
}

submitPostForm();
