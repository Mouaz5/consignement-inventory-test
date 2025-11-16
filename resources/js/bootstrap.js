import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Set CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// Set authorization header from session/localStorage
const getToken = () => {
    // Try to get token from localStorage first
    let token = localStorage.getItem('auth_token');
    
    // If not in localStorage, try to get from page props (set by Inertia middleware)
    if (!token && window.__INERTIA_INITIAL_PAGE__) {
        token = window.__INERTIA_INITIAL_PAGE__.props.auth?.token;
    }
    
    return token;
};

const token = getToken();
if (token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    localStorage.setItem('auth_token', token);
}
