import './bootstrap';
import { fadeToastMessage } from './utils'

const toggleDropdown = (dropdownContent, event) => {
    dropdownContent.classList.toggle('hidden');
    event.stopPropagation();
};

const hideDropdown = (dropdown, event) => {
    const dropdownContent = dropdown.querySelector(':scope > div');
    if (!dropdown.contains(event.target)) {
        dropdownContent.classList.add('hidden');
    }
};

const addDropdownListeners = () => {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const dropdownButton = dropdown.querySelector(':scope > button');
        const dropdownContent = dropdown.querySelector(':scope > div');

        dropdownButton.addEventListener('click', event => toggleDropdown(dropdownContent, event));
        document.addEventListener('click', event => hideDropdown(dropdown, event));
    });
};


const openModal = (modal, event) => {
    modal.classList.add('active');
    event.stopPropagation();
};

const closeModal = (modal, event) => {
    modal.classList.remove('active');
    event.stopPropagation();
}

const addModalListeners = () => {
    const modals = document.querySelectorAll('.modal');

    modals.forEach(modal => {
        const modalOpenButton = modal.querySelector(`:scope .open-button`);
        const modalCloseButton = modal.querySelector(':scope .close-button');

        modalOpenButton.addEventListener('click', event => openModal(modal, event));
        modalCloseButton.addEventListener('click', event => closeModal(modal, event));
    });
}


const addToastMessageListeners = () => {
    document.addEventListener('DOMContentLoaded', () => {
        const toastMessages = document.querySelectorAll('.toast-message:not(.hidden)');

        toastMessages.forEach(fadeToastMessage);
    });
};

const toggleSelect = (select, event) => {
    select.classList.toggle('closed');
    event.stopPropagation();
};

const closeSelect = (select, event) => {
    select.classList.add('closed');
    event.stopPropagation();
}

const updateSelect = (select, selectedOptionsText) => {
    const selectedOptions = select.querySelectorAll(':scope label:has(input:checked) span');

    selectedOptionsText.textContent = Array.from(selectedOptions)
        .map(option => option.textContent)
        .join(", ");
};

const addSelectListeners = () => {
    const selects = document.querySelectorAll('.select');

    selects.forEach(select => {
        const selectDropdown = select.querySelector(':scope > div');
        const selectedOptionsText = select.querySelector(':scope .selected-options');

        select.addEventListener('click', event => {
            toggleSelect(select, event);
            selects.forEach(otherSelect => {
                if (otherSelect !== select)
                    closeSelect(otherSelect, event)
            });
        });

        selectDropdown.addEventListener('click', () => updateSelect(select, selectedOptionsText))
        document.addEventListener('click', event => closeSelect(select, event));
    });
}

addDropdownListeners();
addModalListeners();
addToastMessageListeners();
addSelectListeners();

export { addDropdownListeners, addModalListeners, addToastMessageListeners, addSelectListeners };