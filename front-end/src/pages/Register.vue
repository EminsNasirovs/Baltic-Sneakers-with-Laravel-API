<template>
    <div>
      <h2>Register</h2>
  
      <!-- Registration Form -->
      <form @submit.prevent="handleRegister">
        <div>
          <label for="name">Name:</label>
          <input v-model="name" type="text" id="name" />
          <div v-if="validationErrors.name" class="error-message">{{ validationErrors.name[0] }}</div>
        </div>
  
        <div>
          <label for="email">Email:</label>
          <input v-model="email" type="email" id="email" />
          <div v-if="validationErrors.email" class="error-message">{{ validationErrors.email[0] }}</div>
        </div>
  
        <div>
          <label for="password">Password:</label>
          <input v-model="password" type="password" id="password" />
          <div v-if="validationErrors.password" class="error-message">{{ validationErrors.password[0] }}</div>
        </div>
  
        <div>
          <label for="password_confirmation">Confirm Password:</label>
          <input v-model="confirmPassword" type="password" id="password_confirmation" />
          <div v-if="validationErrors.password_confirmation" class="error-message">{{ validationErrors.password_confirmation[0] }}</div>
        </div>
  
        <button type="submit">Register</button>
      </form>
  
      <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
  
      <p>Already have an account? <router-link to="/login">Login</router-link></p>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import api from '@/api';
  
  const name = ref('');
  const email = ref('');
  const password = ref('');
  const confirmPassword = ref('');
  const validationErrors = ref({});
  const errorMessage = ref('');
  const router = useRouter();
  
  const handleRegister = async () => {
    validationErrors.value = {};  // Clear previous validation errors
    errorMessage.value = '';      // Clear the general error message
  
    try {
      // Send the registration request to the backend
      await api.register({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: confirmPassword.value
      });
  
      // Redirect to login page on success
      router.push('/login');
    } catch (error) {
      // If the response contains validation errors, capture them
      if (error.response && error.response.data.errors) {
        validationErrors.value = error.response.data.errors;
      } else {
        // General error message if no validation errors are found
        errorMessage.value = 'Registration failed. Please try again later.';
      }
    }
  };
  </script>
  
  <style scoped>
  .error-message {
    color: red;
    font-size: 0.875rem;
  }
  
  input {
    display: block;
    margin-bottom: 10px;
    padding: 8px;
  }
  
  button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #45a049;
  }
  </style>
  