import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// After creating axios instance
window.axios.defaults.baseURL = '/';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = 
    document.querySelector('meta[name="csrf-token"]')?.content;

// Echo (real-time) — only for authenticated users
if (window.isAuthenticated) {
    import('./echo');
}