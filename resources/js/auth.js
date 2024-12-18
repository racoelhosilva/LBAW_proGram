const addOAuthButtonListeners = () => {
    const oAuthButtons = document.querySelectorAll('.oauth-button');

    oAuthButtons.forEach(oAuthButton => {
        const button = oAuthButton.querySelector('a');
        const loadingSpinner = oAuthButton.querySelector('.loading-spinner');

        button.addEventListener('click', () => {
            button.classList.add('hidden');
            loadingSpinner.classList.remove('hidden');
        });
    });
}

addOAuthButtonListeners();