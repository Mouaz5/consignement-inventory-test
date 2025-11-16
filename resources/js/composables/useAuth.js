import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const user = ref(null);
const token = ref(localStorage.getItem('auth_token'));
const isAuthenticated = computed(() => !!token.value && !!user.value);

export function useAuth() {
  const login = async (email, password) => {
    try {
      const response = await window.axios.post('/login', { email, password });
      setToken(response.data.token);
      setUser(response.data.user);
      return response.data;
    } catch (error) {
      throw error;
    }
  };

  const register = async (name, email, password, passwordConfirmation) => {
    try {
      const response = await window.axios.post('/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });
      setToken(response.data.token);
      setUser(response.data.user);
      return response.data;
    } catch (error) {
      throw error;
    }
  };

  const logout = async () => {
    try {
      await window.axios.post('/logout');
      clearAuth();
      router.visit('/login');
    } catch (error) {
      // Clear auth anyway even if request fails
      clearAuth();
      router.visit('/login');
    }
  };

  const fetchUser = async () => {
    try {
      const response = await window.axios.get('/user');
      setUser(response.data);
      return response.data;
    } catch (error) {
      clearAuth();
      throw error;
    }
  };

  const setToken = (newToken) => {
    token.value = newToken;
    localStorage.setItem('auth_token', newToken);
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${newToken}`;
  };

  const setUser = (newUser) => {
    user.value = newUser;
  };

  const clearAuth = () => {
    token.value = null;
    user.value = null;
    localStorage.removeItem('auth_token');
    delete window.axios.defaults.headers.common['Authorization'];
  };

  return {
    user,
    token,
    isAuthenticated,
    login,
    register,
    logout,
    fetchUser,
    setToken,
    setUser,
    clearAuth,
  };
}
