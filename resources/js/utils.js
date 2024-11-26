const sendDelete = (url) => {
    return fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => response.json());
}

const sendPost = (url) => {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => response.json());
}

const fadeToastMessage = (toastMessage) => {
    setTimeout(() => {
        toastMessage.classList.add('opacity-0');
        setTimeout(() => {
            toastMessage.classList.add('hidden');
            toastMessage.classList.remove('opacity-0');
        }, 500);
    }, 3000);
}

const sendToastMessage = (message, type) => {
    switch (type) {
        case 'success':
            className = 'success-toast';
            break;
        case 'error':
            className = 'error-toast';
            break;
        case 'message':
            className = 'message-toast';
            break;
        default:
            return;
    }

    const toastMessage = document.querySelector('.toast-message.' + className);
    const toastMessageText = toastMessage.querySelector(':scope > p');
    toastMessageText.textContent = message;

    fadeToastMessage(toastMessage);
}

export { sendDelete, sendPost, fadeToastMessage, sendToastMessage };
