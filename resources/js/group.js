import {addPostListeners} from "./post.js";
import {addLazyLoading} from "./utils.js";

const toggleGroupChatAndAnnouncements = () => {
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            const tab = button.dataset.tab;
    
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-b-2'); 
                btn.classList.add('text-gray-500'); 
            });
            
            button.classList.remove('text-gray-500');
            button.classList.add('border-b-2');
    
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.getElementById(`${tab}-content`).classList.remove('hidden');
        });
    });
}

const addGroupPostsListeners = () => {
    const groupPosts = document.getElementById('group-posts');
    const groupPostsLoading = document.querySelector('#group-posts + div > .loading-spinner');
    const groupAnnouncements = document.getElementById('group-announcements');
    const groupAnnouncementsLoading = document.querySelector('#group-announcements + div > .loading-spinner');
    if (!groupPosts || !groupPostsLoading || !groupAnnouncements || !groupAnnouncementsLoading) {
        return;
    }

    addLazyLoading(groupPosts, groupPostsLoading, window.location.href, {}, addPostListeners);
    addLazyLoading(groupAnnouncements, groupAnnouncementsLoading, window.location.href, { 'announcements': 1 }, addPostListeners);
}

toggleGroupChatAndAnnouncements();
addGroupPostsListeners();
