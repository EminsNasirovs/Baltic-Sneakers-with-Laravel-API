import axios from 'axios';

// Create an instance of axios with a base URL pointing to your Laravel API
const api = axios.create({
  baseURL: 'http://127.0.0.1:8000',  // Replace with your actual API URL
});

// Add the token to every request if it exists in localStorage
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('auth_token');  // Get the token from localStorage
    if (token) {
      // Attach token to the Authorization header for protected routes
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;  // Return the modified config
  },
  (error) => {
    return Promise.reject(error);  // Handle request error
  }
);

export default {
  /**
   * Register a new user by sending their data to the API
   * @param {Object} userData - The user data to register
   * @returns {Object} - The response data from the API
   */
  async register(userData) {
    try {
      const response = await api.post('/api/register', userData);
      return response.data;  // Return the response data from the API
    } catch (error) {
      console.error('Registration failed', error);  // Log any errors that occur during registration
      throw error;  // Rethrow error to be handled by the calling function
    }
  },

  /**
   * Log in a user and store the token in localStorage
   * @param {Object} credentials - The user credentials (e.g., email, password)
   * @returns {Object} - The user data if login is successful
   */
  async login(credentials) {
    try {
      const response = await api.post('/api/login', credentials);  // Send login request to the server
      if (response.data.token) {
        // Store the authentication token in localStorage if the response contains a token
        localStorage.setItem('auth_token', response.data.token);
      }
      return response.data.user;  // Return user data on successful login
    } catch (error) {
      console.error('Login failed', error);  // Log any errors that occur during login
      throw error;  // Rethrow error to be handled by the calling function
    }
  },

  /**
   * Log out the user and remove the token from localStorage
   * @returns {void}
   */
  async logout() {
    try {
      await api.post('/api/logout');  // Send logout request to the server
      // Remove token from localStorage after logout
      localStorage.removeItem('auth_token');
    } catch (error) {
      console.error('Logout failed', error);  // Log any errors that occur during logout
      throw error;  // Rethrow error to be handled by the calling function
    }
  },

  /**
   * Fetch the current authenticated user's data
   * @returns {Object} - The current authenticated user data
   */
  async getUser() {
    try {
      const response = await api.get('/api/user');  // Send request to fetch user data
      return response.data;  // Return the response data from the API
    } catch (error) {
      console.error('Failed to fetch user data', error);  // Log any errors that occur while fetching user data
      throw error;  // Rethrow error to be handled by the calling function
    }
  }
};
