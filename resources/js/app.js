import './bootstrap';

const toggleDropdown = (dropdownContent, event) => {
    dropdownContent.classList.toggle('hidden');
    event.stopPropagation();
};

const hideDropdown = (dropdown, event) => {
    const dropdownContent = dropdown.querySelector(':scope > div');
    if (!dropdown.contains(event.target)) {
        dropdownContent.classList.add('hidden');
    }
}

const addDropdownListeners = () => {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        const dropdownButton = dropdown.querySelector(':scope > button');
        const dropdownContent = dropdown.querySelector(':scope > div');

        dropdownButton.addEventListener('click', event => toggleDropdown(dropdownContent, event));
        document.addEventListener('click', event => hideDropdown(dropdown, event));
    });
};

addDropdownListeners();
