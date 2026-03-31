window.EduFlow = {
    getToken() {
        return localStorage.getItem('token');
    },

    setAuth(data) {
        if (data.access_token) {
            localStorage.setItem('token', data.access_token);
        }

        if (data.user) {
            localStorage.setItem('user', JSON.stringify(data.user));
        }
    },

    clearAuth() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
    },

    getUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    },

    requireAuth() {
        if (!this.getToken()) {
            window.location.href = '/login';
            return false;
        }

        return true;
    },

    async api(url, options = {}) {
        const headers = {
            'Accept': 'application/json',
            ...(options.body ? { 'Content-Type': 'application/json' } : {}),
            ...(options.headers || {})
        };

        const token = this.getToken();

        if (token) {
            headers.Authorization = `Bearer ${token}`;
        }

        const response = await fetch(url, {
            ...options,
            headers
        });

        const data = await response.json();

        if (!response.ok) {
            throw data;
        }

        return data;
    }
};
