import { sendDelete , sendPost, sendToastMessage} from './utils.js';

const addSendInviteListeners = () => {
    const invitesPage = document.querySelector('#group-invites-page');
    if (invitesPage) {
        const inviteBtns = invitesPage.querySelectorAll('.invite-button');
        inviteBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                event.preventDefault();
                const userId = btn.closest('.manage-invite-container').getAttribute('data-user-id');
                const groupId = invitesPage.getAttribute('data-group-id');
                if (!userId || !groupId) return;
                sendPost(`/api/group/${groupId}/invite/${userId}`)
                    .then(() => {
                        btn.classList.add('hidden');
                        const uninvitebtn = btn.closest('.manage-invite-container').querySelector('.uninvite-button');
                        uninvitebtn.classList.remove('hidden');
                        sendToastMessage('Invite sent with success', 'success');
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while inviting the user.', 'error');
                    });
            });
        });
    }
};

const addUnsendInviteListeners = () => {
    const invitesPage = document.querySelector('#group-invites-page');
    if (invitesPage) {
        const unInviteBtns = invitesPage.querySelectorAll('.uninvite-button');
        unInviteBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                event.preventDefault();
                const userId = btn.closest('.manage-invite-container').getAttribute('data-user-id');
                const groupId = invitesPage.getAttribute('data-group-id');
                if (!userId || !groupId) return;
                sendDelete(`/api/group/${groupId}/invite/${userId}`)
                    .then(() => {
                        btn.classList.add('hidden');
                        const invitebtn = btn.closest('.manage-invite-container').querySelector('.invite-button');
                        invitebtn.classList.remove('hidden');
                        sendToastMessage('Invite unsent with success', 'success');
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while uninviting the user.', 'error');
                    });
            });
        });
    }
};

export { addSendInviteListeners, addUnsendInviteListeners };
