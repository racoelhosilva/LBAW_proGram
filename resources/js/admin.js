const toggleDuration = (modal, checkbox) => {
    const duration = modal.querySelector(':scope input[name="duration"]');
    duration.disabled = checkbox.checked;
};

const addBanModalListeners = () => {
    const banModals = document.querySelectorAll('.ban-modal');
    
    banModals.forEach(modal => {
        const permanentCheckbox = modal.querySelector(':scope input[name="permanent"]');
        permanentCheckbox.addEventListener('change', () => toggleDuration(modal, permanentCheckbox));
    });
};

addBanModalListeners();