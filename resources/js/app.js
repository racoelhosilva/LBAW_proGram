import './bootstrap';
import { fadeToastMessage } from './utils'

const toggleDropdown = (dropdownContent, event) => {
    dropdownContent.classList.toggle('hidden');

    const dropdownContents = document.querySelectorAll('.dropdown > div');
    dropdownContents.forEach(content => {
        if (content !== dropdownContent)
            content.classList.add('hidden');
    });

    event.stopPropagation();
};

const hideDropdowns = event => {
    const dropdownContents = document.querySelectorAll('.dropdown > div');
    dropdownContents.forEach(content => {
        content.classList.add('hidden');
    });
};

const addDropdownListeners = () => {
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const dropdownButton = dropdown.querySelector(':scope > button');
        const dropdownContent = dropdown.querySelector(':scope > div');
        dropdownButton.onclick = (event) => toggleDropdown(dropdownContent, event);
        document.onClick = (event) => hideDropdowns(event);
    });
};


const openModal = (modal, event) => {
    modal.classList.add('active');
    event.stopPropagation();
};

const closeModal = (modal, event) => {
    modal.classList.remove('active');
    hideDropdowns(event);
    event.stopPropagation();
}

const addModalListeners = () => {
    const modals = document.querySelectorAll('.modal');

    modals.forEach(modal => {
        const modalOpenButton = modal.querySelector(`:scope .open-button`);
        const modalContent = modal.querySelector(':scope > div');
        const modalCloseButtons = modal.querySelectorAll(':scope .close-button');

        modalContent.addEventListener('click', event => event.stopPropagation());
        modalOpenButton.addEventListener('click', event => openModal(modal, event));
        modalCloseButtons.forEach(closeButton => {
            closeButton.addEventListener('click', event => closeModal(modal, event));
        });
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
        const selectForm = document.getElementById(select.dataset.form);

        select.addEventListener('click', event => {
            toggleSelect(select, event);
            selects.forEach(otherSelect => {
                if (otherSelect !== select)
                    closeSelect(otherSelect, event)
            });
        });

        selectDropdown.addEventListener('click', () => updateSelect(select, selectedOptionsText));
        select.addEventListener('keypress', event => {
            if (event.key === 'Enter') {
                selectForm.submit();
                event.stopPropagation();
            }
        });
        document.addEventListener('click', event => closeSelect(select, event));
    });
}

const addResponsiveDropdownListeners = () => {
    const dropdowns = document.querySelectorAll('.responsive-dropdown');

    dropdowns.forEach(dropdown => {
        const dropdownButton = dropdown.querySelector(':scope > button');

        dropdownButton.addEventListener('click', () => dropdown.classList.toggle('closed'));
    });
};

addDropdownListeners();
addModalListeners();
addToastMessageListeners();
addSelectListeners();
addResponsiveDropdownListeners();

export { addDropdownListeners, addModalListeners, addToastMessageListeners, addSelectListeners };