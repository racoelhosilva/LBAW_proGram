const showPassword = (showPasswordIcon, hidePasswordIcon, passwordInput) => {
	passwordInput.type = "text";
	showPasswordIcon.classList.add("hidden");
	hidePasswordIcon.classList.remove("hidden");
};

const hidePassword = (showPasswordIcon, hidePasswordIcon, passwordInput) => {
	passwordInput.type = "password";
	showPasswordIcon.classList.remove("hidden");
	hidePasswordIcon.classList.add("hidden");
};

const toggleVisibility = (toggleButton) => {
	const showPasswordIcon = toggleButton.querySelector(".eye-closed");
	const hidePasswordIcon = toggleButton.querySelector(".eye");
	const passwordInput = toggleButton.parentElement.querySelector(".password-input");
	
	if (showPasswordIcon.classList.contains("hidden")) {
		hidePassword(showPasswordIcon, hidePasswordIcon, passwordInput);
	} else {
		showPassword(showPasswordIcon, hidePasswordIcon, passwordInput);
	}
};

const addToggleViewPasswordVisibilityListener = () => {
	const toggleButtons = document.querySelectorAll(
		".toggle-password-visibility",
	);

	toggleButtons.forEach((toggleButton) => {
		toggleButton.addEventListener("click", (event) => {
			event.preventDefault();
			toggleVisibility(toggleButton);
		});
	});
};

const addOAuthButtonListeners = () => {
	const oAuthButtons = document.querySelectorAll(".oauth-button");

	oAuthButtons.forEach((oAuthButton) => {
		const button = oAuthButton.querySelector("a");
		const loadingSpinner = oAuthButton.querySelector(".loading-spinner");

		button.addEventListener("click", () => {
			button.classList.add("hidden");
			loadingSpinner.classList.remove("hidden");
		});
	});
};

addToggleViewPasswordVisibilityListener();
addOAuthButtonListeners();
