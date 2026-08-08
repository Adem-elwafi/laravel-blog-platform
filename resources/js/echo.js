import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { CHAT_APP_URL, refreshCsrfToken } from './services/csrfFetch';
import { dispatchFriendshipUpdated } from './services/appEvents';

window.Pusher = Pusher;

async function initEcho() {
  try {
    const xsrfToken = await refreshCsrfToken();

    window.Echo = new Echo({
      broadcaster: 'reverb',
      key: 'vjx0qs18inxhengngths',
      wsHost: 'localhost',
      wsPort: 8081,
      wssPort: 8081,
      forceTLS: false,
      enabledTransports: ['ws'],
      authEndpoint: `${CHAT_APP_URL}/broadcasting/auth`,
      auth: {
        headers: {
          'X-XSRF-TOKEN': xsrfToken,
        },
      },
    });

    const userId = window.authUserId;
    if (userId) {
      window.Echo.private(`friends.${userId}`)
        .listen('FriendshipUpdated', (e) => {
          console.log('FriendshipUpdated event received', e);
          dispatchFriendshipUpdated();
        });
    }
  } catch (error) {
    console.error('Echo initialization failed:', error);
  }
}

if (window.isAuthenticated) {
  initEcho();
}
