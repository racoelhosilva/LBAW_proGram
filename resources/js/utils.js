const encodeParams = (params) => {
    if (!params) {
        return '';
    }
    return '?' + Object.keys(params).map(key => {
        return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]);
    }).join('&');
}

const getView = (url, params) => {
    return fetch(url + encodeParams(params), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    }).then(response => {
        if (!response.ok) {
            throw new Error('Unexpected error occurred');
        }
        return response.text();
    });
}

const sendPost = (url) => {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
        }
    }).then(response => {
        if (!response.ok) {
            throw new Error(response.json.error);
        }
        return response.json();
    });
}

const sendDelete = (url) => {
    return fetch(url, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
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

export { getView, sendDelete, sendPost, fadeToastMessage, sendToastMessage };
