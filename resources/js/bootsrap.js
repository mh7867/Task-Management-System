import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});



window.Echo.connector.pusher.connection.bind('connected', function () {
    console.log('WebSocket connection established successfully!');
});

window.Echo.connector.pusher.connection.bind('disconnected', function () {
    console.log('WebSocket connection lost!');
});

window.Echo.connector.pusher.connection.bind('error', function (err) {
    console.error('WebSocket connection error:', err);
});

window.Echo.channel('user-status')
    .listen('.App\\Events\\UserActiveStatus', (event) => {
        const userId = event.user_id;
        const activeStatus = event.active_status;

        const userElement = document.querySelector(`.activeUser--box[data-user-id="${userId}"] .active`);

        if (userElement) {
            if (activeStatus) {
                userElement.classList.remove('d-none');
                userElement.classList.add('d-inline');
            } else {
                userElement.classList.remove('d-inline');
                userElement.classList.add('d-none');
            }
        }
    });
