import "./bootstrap";
import {
	addDropdownListeners,
	fadeToastMessage,
	sendToastMessage,
	addModalListeners
} from "./utils";
import Quill from "quill";
import {addCommentSectionListeners, addPostListeners, addSubmitCommentListener} from "./post.js";
import {addSearchListeners} from "./search.js";
import {addHomeEventListeners} from "./home.js";
import {addCommentListeners} from "./comment.js";
import {addOAuthButtonListeners, addToggleViewPasswordVisibilityListener} from "./auth.js";
import {addBanModalListeners} from "./admin.js";
import {addFaqListeners} from "./faq.js";
import {addGroupPostsListeners, toggleGroupChatAndAnnouncements} from "./group.js";
import {addHeaderListeners} from "./header.js";
import {addSendInviteListeners, addUnsendInviteListeners} from "./manage-group-invites.js";
import {removeMemberListener} from "./manage-group-members.js";
import {acceptRequestListener, rejectRequestListener} from "./manage-group-requests.js";
import {addNotificationListeners} from "./notifications.js";
import {addHandleRequestListeners, addRemoveFollowerListeners, addUserPostsListeners} from "./user.js";
import {addLeaveGroupListeners} from "./user-groups.js";
import {addInviteAcceptListeners, addInviteRejectListeners} from "./user-invites.js";

const addToastMessageListeners = () => {
	document.addEventListener("DOMContentLoaded", () => {
		const toastMessages = document.querySelectorAll(
			".toast-message:not(.hidden)",
		);

		toastMessages.forEach(fadeToastMessage);
	});
};

const activateQuill = () => {
	const form = document.querySelector(".quill-form");

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

const addCopyButtonListeners = () => {
    const copyButtons = document.querySelectorAll('.copy-button');

    copyButtons.forEach(copyButton => {
        copyButton.addEventListener('click', () => {
            const copyText = copyButton.querySelector('span').textContent;

            navigator.clipboard.writeText(copyText).then(() => {
                sendToastMessage('Copied to clipboard!', 'success');
            });
        });
    });
};

const addAllListeners = () => {
	addDropdownListeners();
	addModalListeners();
	addToastMessageListeners();
	activateQuill();
	addSelectListeners();
	addResponsiveDropdownListeners();
	addCopyButtonListeners();

	addHomeEventListeners();
	addSubmitCommentListener();
	addPostListeners();
	addCommentListeners();
	addCommentSectionListeners();
	addSearchListeners();

	addBanModalListeners();
	addToggleViewPasswordVisibilityListener();
	addOAuthButtonListeners();
	addFaqListeners();
	toggleGroupChatAndAnnouncements();
	addGroupPostsListeners();
	addHeaderListeners();
	addSendInviteListeners();
	addUnsendInviteListeners();
	removeMemberListener();
	acceptRequestListener();
	rejectRequestListener();
	addNotificationListeners();
	addRemoveFollowerListeners();
	addHandleRequestListeners();
	addUserPostsListeners();
	addLeaveGroupListeners();
	addInviteRejectListeners();
	addInviteAcceptListeners();
}

addAllListeners();

export { addToastMessageListeners, addSelectListeners };
