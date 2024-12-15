import { sendPost, sendToastMessage } from './utils'

const PUSHER_APP_KEY = '0a98b979e256005ecb2f';
const PUSHER_CLUSTER = 'eu';

const pusher = new Pusher(PUSHER_APP_KEY, {
    cluster: PUSHER_CLUSTER,
    encrypted: true
});

const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
const channel = "user." + userId;
const counter = document.querySelector('#notification-count');

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

if (userId) {
    const pusherChannel = pusher.subscribe(channel);

    pusherChannel.bind('notification-postlike', function(data) {
        console.log(`New postlike notification: ${data.message}`);
        increaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-commentlike', function(data) {
        console.log(`New commentlike notification: ${data.message}`);
        increaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-postunlike', function(data) {
        console.log(`New unlike notification: ${data.message}`);
        decreaseCounter(counter);
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-commentunlike', function(data) {
        console.log(`New commentunlike notification: ${data.message}`);
        decreaseCounter(counter);
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

const addMarkReadButtonListeners = () => {
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

if (userId) {
    addMarkReadButtonListeners();
}
