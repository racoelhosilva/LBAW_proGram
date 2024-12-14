import { sendDelete, sendPost, sendToastMessage } from './utils.js';

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

const acceptRequestListener = () => {
    console.log('Accepting request listener...');
    console.log('Accepting request laaallistener...');
    const manageGroupPage = document.querySelector('#group-requests-page');
    console.log(manageGroupPage);
    if (manageGroupPage){
       
        const groupId = manageGroupPage.getAttribute('data-group-id');
        console.log(groupId);
    
        const acceptRequestBtns = document.querySelectorAll('.accept-request-button');
        console.log(acceptRequestBtns);
    
        acceptRequestBtns.forEach(btn => {
            btn.addEventListener('click', async (event) => {
                console.log('Accepting request...');
                event.preventDefault();
                console.log(btn);
    
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
    console.log('Rejecting request listener...');
    const manageGroupPage = document.querySelector('#group-requests-page');
    const groupId = manageGroupPage.getAttribute('data-group-id');

    const rejectRequestBtns = document.querySelectorAll('.decline-request-button');
    rejectRequestBtns.forEach(btn => {
        btn.addEventListener('click', async (event) => {
            console.log('Rejecting request...');
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

removeMemberListener();
acceptRequestListener();
rejectRequestListener();