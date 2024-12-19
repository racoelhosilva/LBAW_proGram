import "./bootstrap";
import { fadeToastMessage } from "./utils";
import Quill from "quill";
import "quill/dist/quill.core.css";

const toggleDropdown = (dropdownContent, event) => {
	dropdownContent.classList.toggle("hidden");
	event.stopPropagation();
};

const hideDropdown = (dropdown, event) => {
	const dropdownContent = dropdown.querySelector(":scope > div");
	if (!dropdown.contains(event.target)) {
		dropdownContent.classList.add("hidden");
	}
};

const addDropdownListeners = () => {
	const dropdowns = document.querySelectorAll(".dropdown");

	dropdowns.forEach((dropdown) => {
		const dropdownButton = dropdown.querySelector(":scope > button");
		const dropdownContent = dropdown.querySelector(":scope > div");

		dropdownButton.addEventListener("click", (event) =>
			toggleDropdown(dropdownContent, event),
		);
		document.addEventListener("click", (event) =>
			hideDropdown(dropdown, event),
		);
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

		modalOpenButton.addEventListener("click", (event) =>
			openModal(modal, event),
		);
		modalCloseButton.addEventListener("click", (event) =>
			closeModal(modal, event),
		);
	});
};

const addToastMessageListeners = () => {
	document.addEventListener("DOMContentLoaded", () => {
		const toastMessages = document.querySelectorAll(
			".toast-message:not(.hidden)",
		);

		toastMessages.forEach(fadeToastMessage);
	});
};

const attachments = [];

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
						["link", "image"],
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

									try {
										const response = await fetch("/api/upload-file", {
											method: "POST",
											headers: {
												"X-CSRF-TOKEN": document.querySelector(
													'meta[name="csrf-token"]',
												).content,
											},
											body: formData,
										});
										console.log(response);

										if (response.ok) {
											const { url } = await response.json();
											const range = quill.getSelection();
											console.log(url);
											quill.insertEmbed(range.index, "image", url);
											attachments.add(url);
										} else {
											console.error("Failed to upload image", response);
										}
									} catch (error) {
										console.error("Error uploading image", error);
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
			console.log("here");
			const parser = new DOMParser();
			const content = quill.root.innerHTML;
			field.value = content;

			const doc = parser.parseFromString(content, "text/html");
			const currentImages = new Set(
				Array.from(doc.querySelectorAll("img")).map((img) => img.src),
			);

			const unusedImages = Array.from(attachments).filter(
				(url) => !currentImages.has(url),
			);

			for (const url of unusedImages) {
				try {
					const response = await fetch("/api/delete-file", {
						method: "POST",
						headers: {
							"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
								.content,
							"Content-Type": "application/json",
						},
						body: JSON.stringify({ url }),
					});
					console.log(response);
					attachments.delete(url);
				} catch (error) {
					console.error("Error deleting image", error);
				}
			}
		};
	}
};

addDropdownListeners();
addModalListeners();
addToastMessageListeners();
activateQuill();
