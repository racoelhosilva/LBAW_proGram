const toggleTheme = () => {
    if (document.documentElement.classList.contains('dark')) {
        localStorage.setItem('theme', 'light');
        document.documentElement.classList.remove('dark');
    } else {
        localStorage.setItem('theme', 'dark');
        document.documentElement.classList.add('dark');
    }
}

const addThemeButtonListener = (themeButton) => {
    themeButton.addEventListener('click', toggleTheme);
}

(function() {
    const themeButton = document.getElementById('theme-button');
    if (themeButton) {
        addThemeButtonListener(themeButton);
    }
})();
