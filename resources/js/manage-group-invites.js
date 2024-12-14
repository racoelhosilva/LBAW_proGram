import { sendDelete, sendGet , sendPost} from './utils.js';

const updateUserSearch = () => {
    // Check if we are on the group-invites-page
    const invitesPage = document.querySelector('#group-invites-page');
    
    if (invitesPage) {
        // Get the search form and input elements
        const searchForm = invitesPage.querySelector('form');
        const searchInput = searchForm ? searchForm.querySelector('input[type="search"]') : null;

        if (searchForm && searchInput) {
            // Add an event listener to the search form to handle submission
            searchForm.addEventListener('submit', (event) => {
                event.preventDefault(); // Prevent the form from submitting normally
                
                // Get the search query from the input
                const query = searchInput.value;
                
                // Log the search query (you can replace this with AJAX logic if needed)
                console.log('Search Query:', query);

                // If query is empty, reload the page without the users query parameter
                if (!query.trim()) {
                    const groupId = invitesPage.dataset.groupId;
                    window.location.href = `/group/${groupId}/invites`;
                    return;
                }

                // Perform an AJAX request to search users
                sendGet('/api/search/users?query=' + query)
                    .then(response => {
                        // Assuming the response contains an array of user objects
                        console.log('Search Results:', response);

                        // Extract the user IDs from the response (assuming it's an array of users)
                        const userIds = response.map(user => user.id);

                        // Get the current group ID from the page (assuming it's stored in a data attribute)
                        const groupId = invitesPage.dataset.groupId;

                        // Construct the new URL with the user IDs as a query parameter
                        const newUrl = `/group/${groupId}/invites?users=${userIds.join(',')}`;

                        // Redirect to the new URL with the selected user IDs
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
                console.log('Inviting user...');
                const userId = btn.closest('.manage-invite-container').getAttribute('data-user-id');
                const groupId = invitesPage.getAttribute('data-group-id');
                if (!userId || !groupId) return;
                sendPost(`/api/group/${groupId}/invite/${userId}`)
                    .then(() => {
                        window.location.reload();
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
                console.log('Uninviting user...');
                const userId = btn.closest('.manage-invite-container').getAttribute('data-user-id');
                const groupId = invitesPage.getAttribute('data-group-id');
                if (!userId || !groupId) return;
                sendDelete(`/api/group/${groupId}/uninvite/${userId}`)
                    .then(() => {
                        window.location.reload();
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
// Call the function to set up the search functionality
updateUserSearch();
