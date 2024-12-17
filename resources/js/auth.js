const showPassword = (showPasswordIcon, hidePasswordIcon) => {
	const passwordInput = document.querySelector("#password");
	passwordInput.type = "text";
	showPasswordIcon.classList.add("hidden");
	hidePasswordIcon.classList.remove("hidden");
};

const hidePassword = (showPasswordIcon, hidePasswordIcon) => {
	const passwordInput = document.querySelector("#password");
	passwordInput.type = "password";
	showPasswordIcon.classList.remove("hidden");
	hidePasswordIcon.classList.add("hidden");
};

const toggleVisibility = (toggleButton) => {
	const showPasswordIcon = toggleButton.querySelector("#eye-closed");
	const hidePasswordIcon = toggleButton.querySelector("#eye");
	if (showPasswordIcon.classList.contains("hidden")) {
		hidePassword(showPasswordIcon, hidePasswordIcon);
	} else {
		showPassword(showPasswordIcon, hidePasswordIcon);
	}
};

const addToggleViewPasswordVisibilityListener = () => {
	const toggleButton = document.querySelector("#toggle-password-visibility");

	toggleButton.addEventListener("click", (event) => {
		event.preventDefault();
		toggleVisibility(toggleButton);
	});
};

addToggleViewPasswordVisibilityListener();
