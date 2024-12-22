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
toggleGroupChatAndAnnouncements();
