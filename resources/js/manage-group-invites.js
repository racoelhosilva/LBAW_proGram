import { sendDelete, sendGet , sendPost} from './utils.js';

const inviteSendListener = () => {
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
                    })
                    .catch((error) => {
                        console.error('Error inviting user:', error);
                    });
            });
        });
    }
};

const inviteUnSendListener = () => {
    const invitesPage = document.querySelector('#group-invites-page');
    if (invitesPage) {
        const unInviteBtns = invitesPage.querySelectorAll('.uninvite-button');
        unInviteBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                event.preventDefault();
                const userId = btn.closest('.manage-invite-container').getAttribute('data-user-id');
                const groupId = invitesPage.getAttribute('data-group-id');
                if (!userId || !groupId) return;
                sendDelete(`/api/group/${groupId}/uninvite/${userId}`)
                    .then(() => {
                        btn.classList.add('hidden');
                        const invitebtn = btn.closest('.manage-invite-container').querySelector('.invite-button');
                        invitebtn.classList.remove('hidden');
                    })
                    .catch((error) => {
                        console.error('Error uninviting user:', error);
                    });
            });
        });
    }
};

inviteSendListener();
inviteUnSendListener();

