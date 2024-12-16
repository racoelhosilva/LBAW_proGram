import { sendDelete, sendGet , sendPost} from './utils.js';

const updateUserSearch = () => {
    const invitesPage = document.querySelector('#group-invites-page');
    
    if (invitesPage) {
        const searchForm = invitesPage.querySelector('form');
        const searchInput = searchForm ? searchForm.querySelector('input[type="search"]') : null;

        if (searchForm && searchInput) {
            searchForm.addEventListener('submit', (event) => {
                event.preventDefault(); 
                
                const query = searchInput.value;
                
                if (!query.trim()) {
                    const groupId = invitesPage.dataset.groupId;
                    window.location.href = `/group/${groupId}/invites`;
                    return;
                }

                sendGet('/api/search/users?query=' + query)
                    .then(response => {   
                        const userIds = response.map(user => user.id);
                        const groupId = invitesPage.dataset.groupId;
                        const newUrl = `/group/${groupId}/invites?users=${userIds.join(',')}`;
                        window.location.href = newUrl;
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error);
                    });
            });
        }
    }
};


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
updateUserSearch();
