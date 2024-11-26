const sendDelete = (url) => {
    return fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => {
        if (!response.ok) {
            throw new Error(response.json.error);
        }
        return response.json();
    });
}

const sendPost = (url) => {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => {
        if (!response.ok) {
            throw new Error(response.json.error);
        }
        return response.json();
    });
}

const fadeToastMessage = (toastMessage) => {
    if (toastMessage.dataset.timeoutId) {
        clearTimeout(toastMessage.dataset.timeoutId);
    }

    toastMessage.dataset.timeoutId = setTimeout(() => {
        toastMessage.classList.add('opacity-0');
        setTimeout(() => {
            toastMessage.classList.add('hidden');
            toastMessage.classList.remove('opacity-0');
        }, 500);
    }, 3000);
}

const sendToastMessage = (message, type) => {
    let toast;
    switch (type) {
        case 'success':
            toast = 'success-toast';
            break;
        case 'error':
            toast = 'error-toast';
            break;
        default:
            return;
    }

    const toastMessage = document.getElementById(toast);
    const toastMessageText = toastMessage.querySelector(':scope > p');
    toastMessageText.textContent = message;
    
    toastMessage.classList.remove('hidden');
    toastMessage.classList.remove('opacity-0');
    fadeToastMessage(toastMessage);
}

export { sendDelete, sendPost, fadeToastMessage, sendToastMessage };
