import axiosClient from './axios';

/**
 * Token will allow the user access to the protected routes
 */
const setToken = () => {
  const token = localStorage.getItem('token');
  if (token) {
    axiosClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;
  } else {
    delete axiosClient.defaults.headers.common['Authorization'];
  }
};

const initAuth = () => {
  setToken();
};

export default initAuth;
