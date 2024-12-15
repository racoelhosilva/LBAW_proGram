import { sendToastMessage } from './utils'

const PUSHER_APP_KEY = '0a98b979e256005ecb2f';
const PUSHER_CLUSTER = 'eu';

const pusher = new Pusher(PUSHER_APP_KEY, {
    cluster: PUSHER_CLUSTER,
    encrypted: true
});

const channel = document.querySelector('meta[name="broadcast-channel"]').getAttribute('content');
const counter = document.querySelector('#notification-count');

const increaseCounter = () => {
    if (counter) {
        counter.classList.remove('hidden');
        counter.textContent = parseInt(counter.textContent) + 1;
    }
}

const decreaseCounter = () => {
    if (counter) {
        counter.textContent = parseInt(counter.textContent) - 1;
        if (parseInt(counter.textContent) === 0) {
            counter.classList.add('hidden');
        }
    }
}

if (channel) {
    const pusherChannel = pusher.subscribe(channel);

    pusherChannel.bind('notification-postlike', function(data) {
        console.log(`New postlike notification: ${data.message}`);
        increaseCounter();
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-commentlike', function(data) {
        console.log(`New commentlike notification: ${data.message}`);
        increaseCounter();
        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-unlike', function(data) {
        decreaseCounter();
    });
}
