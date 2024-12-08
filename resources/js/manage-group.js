import { sendDelete, sendPost, sendToastMessage } from './utils.js';
const toggleSections = () => {
    const manageGroupPage = document.querySelector('#manage-group-page');
    if (!manageGroupPage) return;

    const anchors = manageGroupPage.querySelectorAll('header a');
    const sections = manageGroupPage.querySelectorAll('section');

    const activateSection = (targetId) => {
        sections.forEach(section => section.classList.add('hidden'));
        anchors.forEach(link => link.classList.add('border-transparent'));
        const targetSection = manageGroupPage.querySelector(`section#${targetId}`);
        const targetAnchor = manageGroupPage.querySelector(`header a[href="#${targetId}"]`);
        if (targetSection && targetAnchor) {
            targetSection.classList.remove('hidden');
            targetAnchor.classList.remove('border-transparent');
        }
    };

    anchors.forEach(anchor => {
        anchor.addEventListener('click', (event) => {
            event.preventDefault();
            const targetId = anchor.getAttribute('href').substring(1); 
            window.location.hash = targetId; 
            activateSection(targetId); 
        });
    });

    const initialHash = window.location.hash.substring(1);
    if (initialHash) {
        activateSection(initialHash);
    } else {
        const defaultId = anchors[0].getAttribute('href').substring(1);
        activateSection(defaultId);
    }
};

const removeMemberListener = () => {
    console.log('Remove member listener...');
    const manageGroupPage = document.querySelector('#manage-group-page');
    const groupId = manageGroupPage.getAttribute('data-group-id');
    console.log(groupId);

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

const acceptRequestListener = () => {
    console.log('Accepting request listener...');
    const manageGroupPage = document.querySelector('#manage-group-page');
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

const rejectRequestListener = () => {
    console.log('Rejecting request listener...');
    const manageGroupPage = document.querySelector('#manage-group-page');
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

toggleSections();

removeMemberListener();
acceptRequestListener();
rejectRequestListener();