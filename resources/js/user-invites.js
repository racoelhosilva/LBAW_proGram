import { sendPost, sendDelete, sendToastMessage } from './utils.js';  

const addInviteAcceptListeners =  () =>{
    const inviteContainers = document.querySelectorAll('.group-invite-container');
    inviteContainers.forEach(inviteContainer => {
        const acceptButton = inviteContainer.querySelector('.accept-invite-button');
        acceptButton.addEventListener('click', async (event) => {
            event.preventDefault();
            const groupId = inviteContainer.getAttribute('data-group-id');
            sendPost(`/api/group/${groupId}/acceptInvite`)
                .then(() => {
                    inviteContainer.remove();
                })
                .catch((error) => {
                    sendToastMessage('An error occurred while accepting the invite.', 'error');
                });
        });
    });
}

const addInviteRejectListeners = () =>{
    const inviteContainers = document.querySelectorAll('.group-invite-container');
    inviteContainers.forEach(inviteContainer => {
        const rejectButton = inviteContainer.querySelector('.reject-invite-button');
        rejectButton.addEventListener('click', async (event) => {
            event.preventDefault();
            const groupId = inviteContainer.getAttribute('data-group-id');
            sendDelete(`/api/group/${groupId}/rejectInvite`)
                .then(() => {
                    inviteContainer.remove();
                })
                .catch((error) => {
                    sendToastMessage('An error occurred while rejecting the invite.', 'error');
                });
        });
    });
}   
addInviteRejectListeners();
addInviteAcceptListeners();