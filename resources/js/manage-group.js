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

toggleSections();
