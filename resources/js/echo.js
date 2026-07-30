import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const CHAT_APP_URL = import.meta.env.VITE_CHAT_APP_URL || 'http://localhost:8000';

window.Pusher = Pusher;

function getCookie(name) {
  const match = document.cookie.match(new RegExp(`(?:^|;\\s*)${encodeURIComponent(name)}=([^;]*)`));
  return match ? decodeURIComponent(match[1]) : '';
}

async function initEcho() {
  try {
    await fetch(`${CHAT_APP_URL}/sanctum/csrf-cookie`, {
      credentials: 'include',
    });

    const xsrfToken = getCookie('XSRF-TOKEN');

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
          window.dispatchEvent(new CustomEvent('friendship:updated'));
        });
    }
  } catch (error) {
    console.error('Echo initialization failed:', error);
  }
}

if (window.isAuthenticated) {
  initEcho();
}
