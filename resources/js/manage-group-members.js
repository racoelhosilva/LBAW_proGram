import { sendDelete, sendToastMessage } from './utils.js';

const removeMemberListener = () => {
    const manageGroupPage = document.querySelector('#members-page');
    if (manageGroupPage) {
        const groupId = manageGroupPage.getAttribute('data-group-id');
        const removeMemberBtns = document.querySelectorAll('.remove-member-button');
        removeMemberBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                event.preventDefault();  
                const userId = btn.closest('.manage-member-container').getAttribute('data-user-id');
                if (!userId) return;
                sendDelete(`/api/group/${groupId}/remove/${userId}`)
                    .then(() => {
                        btn.closest('.manage-member-container').remove();
                        sendToastMessage('Member removed with success', 'success');
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while removing the member.', 'error');
                    });
            });
        });
    }
}

export { removeMemberListener };
