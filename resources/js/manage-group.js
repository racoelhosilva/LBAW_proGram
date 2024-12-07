const toggleSections = () => {
    const manageGroupPage = document.querySelector('#manage-group-page');
    if (!manageGroupPage) return; 
    const anchors = manageGroupPage.querySelectorAll('header a'); 
    const sections = manageGroupPage.querySelectorAll('section');
    anchors.forEach(anchor => {
        anchor.addEventListener('click', (event) => {
            event.preventDefault(); 
            anchors.forEach(link => link.classList.add('border-transparent'));
            anchor.classList.remove('border-transparent');
            const targetId = anchor.getAttribute('id');
            sections.forEach(section => section.classList.add('hidden'));
            const targetSection = manageGroupPage.querySelector(`section#${targetId}`);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }
        });
    });
}

toggleSections();