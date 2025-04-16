import axios from 'axios';


const api = axios.create({
  baseURL: 'http://127.0.0.1:8000', 
});


api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');  
    if (token) {
     
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;  
  },
  (error) => {
    return Promise.reject(error);  
  }
);

export default {

    /**
     * Register a new user
     * @param {Object} userData 
     * @returns {Object} 
     */
   
  async register(userData) {
    try {
      const response = await api.post('/api/register', userData);
      return response.data;  
    } catch (error) {
      console.error('Registration failed', error);  
      throw error;  
    }
  },

  /**
   * Log in a user and store the token in localStorage
   * @param {Object} credentials 
   * @returns {Object} 
   */
  async login(credentials) {
    try {
      const response = await api.post('/api/login', credentials);  

      if (response.data && response.data.token) {
        localStorage.setItem('auth_token', response.data.token);
        return response.data.user;
      } else {
        throw new Error('No token returned from the server');
      }
    } catch (error) {
      console.error('Login failed', error); 
      throw error;  
    }
  },

  /**
   * Log out the user and remove the token from localStorage
   * @returns {void}
   */
  async logout() {
    try {
      await api.post('/api/logout');  
      localStorage.removeItem('auth_token');
    } catch (error) {
      console.error('Logout failed', error);  
      throw error;  n
    }
  },

  /**
   * Fetch the current authenticated user's data
   * @returns {Object}
   */
  async getUser() {
    try {
      const response = await api.get('/api/user'); 
      return response.data; 
    } catch (error) {
      console.error('Failed to fetch user data', error);  
      throw error;  
    }
  }
};
  