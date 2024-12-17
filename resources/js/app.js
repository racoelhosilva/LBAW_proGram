import './bootstrap';
import { fadeToastMessage } from './utils'
import Quill from "quill";
import "quill/dist/quill.core.css";

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

const activateQuill = () => {
    const form = document.querySelector('#quill-form');

    if (form) {
        const field = form.querySelector(`input[name="${form.dataset.quillField}"]`);
        const quill = new Quill('#quill-editor', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'code'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Write something amazing...',
            theme: 'snow'
        });
        quill.clipboard.dangerouslyPasteHTML(field.value ?? '', "silent");

        form.onsubmit = () => {
            field.value = quill.root.innerHTML;
        };
    }
};

addDropdownListeners();
addModalListeners();
addToastMessageListeners();
activateQuill();
