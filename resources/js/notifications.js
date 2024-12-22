import { sendPost, sendToastMessage } from './utils'

const PUSHER_APP_KEY = '0a98b979e256005ecb2f';
const PUSHER_CLUSTER = 'eu';

const pusher = new Pusher(PUSHER_APP_KEY, {
    cluster: PUSHER_CLUSTER,
    encrypted: true
});

const increaseCounter = (counter) => {
    if (counter) {
        counter.classList.remove('hidden');
        counter.textContent = parseInt(counter.textContent) + 1;
    }
}

const decreaseCounter = (counter) => {
    if (counter) {
        counter.textContent = parseInt(counter.textContent) - 1;
        if (parseInt(counter.textContent) === 0) {
            counter.classList.add('hidden');
        }
    }
}

const subscribeNotifications = (userId, counter) => {
    const channel = "user." + userId;
    const pusherChannel = pusher.subscribe(channel);

    pusherChannel.bind('notification-postlike', function(data) {
        increaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-commentlike', function(data) {
        increaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-postunlike', function(data) {
        decreaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-commentunlike', function(data) {
        decreaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-comment', function(data) {
        increaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-follow', function(data) {
        increaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });
}

/** Notification Page **/

const markAllRead = (userId, counter) => {
    sendPost(`/api/user/${userId}/notifications/read`)
        .then((_) => {
            counter.textContent = 0;
            counter.classList.add('hidden');
            const buttons = document.querySelectorAll("#read-notification-button");
            buttons.forEach((button) => button.classList.add("hidden"));
        })
        .catch((error) => {
            sendToastMessage('An error occurred while marking all as read.', 'error');
        });
};

const markNotificationRead = (button, notificationId, userId, counter) => {
	button.disabled = true;
    sendPost(`/api/user/${userId}/notification/${notificationId}/read`)
        .then((_) => {
            button.classList.add("hidden");
            decreaseCounter(counter);
            button.disabled = false;
        })
        .catch((error) => {
            button.disabled = false;
            sendToastMessage('An error occurred while marking as read.', 'error');
        });
};

const addMarkReadButtonListeners = (userId, counter) => {
	const notifications = document.querySelectorAll(".notification-card");

	notifications.forEach((card) => {
		const notificationId = card.dataset.notificationId;
        const button = card.querySelector("#read-notification-button");

		button.addEventListener("click", () =>	markNotificationRead(button, notificationId, userId, counter));
	});

    const markAllButton = document.querySelector("#read-notifications-button");
    if (markAllButton) {
        markAllButton.addEventListener("click", () => { markAllRead(userId, counter) });
    }
};

const addNotificationListeners = () => {
    const userIdElement = document.querySelector('meta[name="user-id"]');
    const counter = document.querySelector('#notification-count');

    if (userIdElement && counter) {
        const userId = userIdElement.getAttribute('content');
        subscribeNotifications(userId, counter);
        addMarkReadButtonListeners(userId, counter);
    }
}

export { addNotificationListeners };
