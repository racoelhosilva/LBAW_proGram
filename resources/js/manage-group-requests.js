import { sendDelete, sendPost, sendToastMessage } from './utils.js';

const acceptRequestListener = () => {
    const manageGroupPage = document.querySelector('#group-requests-page');
    if (manageGroupPage){
        const groupId = manageGroupPage.getAttribute('data-group-id');
        const acceptRequestBtns = document.querySelectorAll('#group-requests-page .accept-group-request-button');
        acceptRequestBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                event.preventDefault();
                const userId = btn.closest('.manage-request-container').getAttribute('data-user-id');
                if (!userId) return;
                sendPost(`/api/group/${groupId}/request/${userId}/accept`)
                    .then(() => {
                        btn.closest('.manage-request-container').remove();
                        sendToastMessage('Request accepted with success', 'success');
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
    if (!manageGroupPage) return;
    const groupId = manageGroupPage.getAttribute('data-group-id');

    const rejectRequestBtns = document.querySelectorAll('.decline-group-request-button');
    rejectRequestBtns.forEach(btn => {
        btn.addEventListener('click', async (event) => {
            event.preventDefault();
            const userId = btn.closest('.manage-request-container').getAttribute('data-user-id');
            if (!userId) return;
            sendDelete(`/api/group/${groupId}/request/${userId}/reject`)
                .then(() => {
                    btn.closest('.manage-request-container').remove();
                    sendToastMessage('Request rejected with success', 'success');
                })
                .catch((error) => {
                    sendToastMessage('An error occurred while rejecting the request.', 'error');
                });
        });
    });
}

acceptRequestListener();
rejectRequestListener();