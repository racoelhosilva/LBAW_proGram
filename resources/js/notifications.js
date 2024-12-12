import { sendToastMessage } from './utils'

const pusher = new Pusher(/*pusherAppKey*/, {
    cluster: /*pusherCluster*/,
    encrypted: true
});

const channel = document.querySelector('#notification-button').dataset.broadcastChannel;

if (channel) {
    const pusherChannel = pusher.subscribe(channel);

    pusherChannel.bind('notification-postlike', function(data) {
        console.log(`New postlike notification: ${data.message}`);

        sendToastMessage(data.message, 'success');
    });

    pusherChannel.bind('notification-commentlike', function(data) {
        console.log(`New commentlike notification: ${data.message}`);

        sendToastMessage(data.message, 'success');
    });
}
