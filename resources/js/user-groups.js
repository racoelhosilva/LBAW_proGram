import { sendDelete, sendToastMessage } from './utils'

console.log('User groups js loaded');
const addLeaveGroupListeners = () => {
    const leaveGroupButtons = document.querySelectorAll('.leave-group-button');
    console.log(leaveGroupButtons);
    leaveGroupButtons.forEach(btn => {
        btn.addEventListener('click', async (event) => {
            event.preventDefault();
            const groupId = btn.closest('.group-buttons-container').getAttribute('data-group-id');
            sendDelete(`/api/group/${groupId}/leave`)
                .then(() => {
                    window.location.reload();
                })
                .catch((error) => {
                    sendToastMessage('An error occurred while leaving the group.', 'error');
                });
        });
    });
}

addLeaveGroupListeners();