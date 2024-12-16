import { sendDelete, sendPost, sendToastMessage } from './utils.js';

const acceptRequestListener = () => {
    const manageGroupPage = document.querySelector('#group-requests-page');
    if (manageGroupPage){
        const groupId = manageGroupPage.getAttribute('data-group-id');
        const acceptRequestBtns = document.querySelectorAll('.accept-request-button');
        acceptRequestBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                event.preventDefault();
                const userId = btn.closest('.manage-request-container').getAttribute('data-user-id');
                if (!userId) return;
                sendPost(`/api/group/${groupId}/request/${userId}/accept`)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while accepting the request.', 'error');
                    });
            });
        });
        
    }

}

const rejectRequestListener = () => {
    const manageGroupPage = document.querySelector('#group-requests-page');
    const groupId = manageGroupPage.getAttribute('data-group-id');

    const rejectRequestBtns = document.querySelectorAll('.decline-request-button');
    rejectRequestBtns.forEach(btn => {
        btn.addEventListener('click', async (event) => {
            event.preventDefault();
            const userId = btn.closest('.manage-request-container').getAttribute('data-user-id');
            if (!userId) return;
            sendDelete(`/api/group/${groupId}/request/${userId}/reject`)
                .then(() => {
                    window.location.reload();
                })
                .catch((error) => {
                    sendToastMessage('An error occurred while rejecting the request.', 'error');
                });
        });
    });
}


acceptRequestListener();
rejectRequestListener();