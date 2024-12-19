import "./bootstrap";
import { fadeToastMessage } from "./utils";
import Quill from "quill";
import "quill/dist/quill.core.css";

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
	const dropdowns = document.querySelectorAll(".dropdown");

	dropdowns.forEach((dropdown) => {
		const dropdownButton = dropdown.querySelector(":scope > button");
		const dropdownContent = dropdown.querySelector(":scope > div");

        dropdownButton.addEventListener('click', event => toggleDropdown(dropdownContent, event));
        document.addEventListener('click', hideDropdowns);
    });
};

const openModal = (modal, event) => {
	modal.classList.add("active");
	event.stopPropagation();
};

const closeModal = (modal, event) => {
	modal.classList.remove("active");
	event.stopPropagation();
};

const addModalListeners = () => {
	const modals = document.querySelectorAll(".modal");

	modals.forEach((modal) => {
		const modalOpenButton = modal.querySelector(`:scope .open-button`);
		const modalCloseButton = modal.querySelector(":scope .close-button");
		const modalContent = modal.querySelector(':scope > div');

		modalContent.addEventListener('click', event => event.stopPropagation());
		modalOpenButton.addEventListener('click', event => openModal(modal, event));
		modalCloseButton.addEventListener('click', event => closeModal(modal, event));
	});
}

const addToastMessageListeners = () => {
	document.addEventListener("DOMContentLoaded", () => {
		const toastMessages = document.querySelectorAll(
			".toast-message:not(.hidden)",
		);

		toastMessages.forEach(fadeToastMessage);
	});
};

const activateQuill = () => {
	const form = document.querySelector("#quill-form");

	if (form) {
		const field = form.querySelector(
			`input[name="${form.dataset.quillField}"]`,
		);
		const quill = new Quill("#quill-editor", {
			modules: {
				toolbar: {
					container: [
						["bold", "italic", "underline", "code"],
						[{ list: "ordered" }, { list: "bullet" }, "code-block"],
						["link", "image", "video"],
						["clean"],
					],
					handlers: {
						image: function() {
							const input = document.createElement("input");
							input.setAttribute("type", "file");
							input.setAttribute("accept", "image/*");
							input.click();

							input.onchange = async () => {
								const file = input.files[0];
								if (file) {
									const formData = new FormData();
									formData.append("file", file);
									formData.append("type", "temporary");
									formData.append(
										"user_id",
										document
											.querySelector('meta[name="user-id"]')
											.getAttribute("content"),
									);

									const response = await fetch("/api/upload-file", {
										method: "POST",
										headers: {
											"X-CSRF-TOKEN": document.querySelector(
												'meta[name="csrf-token"]',
											).content,
										},
										body: formData,
									});

									if (response.ok) {
										const { url } = await response.json();
										const range = quill.getSelection();
										quill.insertEmbed(range.index, "image", url);
									}
								}
							};
						},
						video: function() {
							const input = document.createElement("input");
							input.setAttribute("type", "file");
							input.setAttribute("accept", "video/*");
							input.click();

							input.onchange = async () => {
								const file = input.files[0];
								if (file) {
									const formData = new FormData();
									formData.append("file", file);
									formData.append("type", "temporary");
									formData.append(
										"user_id",
										document
											.querySelector('meta[name="user-id"]')
											.getAttribute("content"),
									);

									const response = await fetch("/api/upload-file", {
										method: "POST",
										headers: {
											"X-CSRF-TOKEN": document.querySelector(
												'meta[name="csrf-token"]',
											).content,
										},
										body: formData,
									});
									if (response.ok) {
										const { url } = await response.json();
										const range = quill.getSelection();
										quill.insertEmbed(range.index, "video", url);
									}
								}
							};
						},
					},
				},
			},
			placeholder: "Write something amazing...",
			theme: "snow",
		});

		quill.clipboard.dangerouslyPasteHTML(field.value ?? "", "silent");

		form.onsubmit = async () => {
			field.value = quill.root.innerHTML;
		};
	}
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
activateQuill();
addSelectListeners();
addResponsiveDropdownListeners();
