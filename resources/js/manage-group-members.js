import { sendDelete, sendToastMessage } from './utils.js';

const removeMemberListener = () => {
    console.log('Remove member listener...');
    const manageGroupPage = document.querySelector('#members-page');
    if(manageGroupPage){
        const groupId = manageGroupPage.getAttribute('data-group-id');
        console.log(groupId);
        console.log('Remove member listeddndnner...');
        const removeMemberBtns = document.querySelectorAll('.remove-member-button');
        console.log(removeMemberBtns);
        
        removeMemberBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                console.log('Removing member...');
                event.preventDefault();  
                console.log(btn);
                const userId = btn.closest('.manage-member-container').getAttribute('data-user-id');
                
                if (!userId) return;
                sendDelete(`/api/group/${groupId}/remove/${userId}`)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch((error) => {
                        sendToastMessage('An error occurred while removing the member.', 'error');
                    });
            });
        });

    }

}

removeMemberListener();