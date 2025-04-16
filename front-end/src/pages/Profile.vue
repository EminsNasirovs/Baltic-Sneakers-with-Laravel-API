<template>
  <div class="max-w-xl mx-auto my-10 p-8 bg-white rounded-2xl shadow-md">

    <div v-if="loading" class="text-center text-gray-500">
      <p>Loading your profile...</p>
    </div>

    <div v-else-if="error" class="text-center text-red-500">
      <p>{{ error }}</p>
      <button
        @click="fetchUserProfile"
        class="mt-4 px-4 py-2 bg-lime-500 text-white rounded-xl hover:bg-lime-600 active:bg-lime-700 transition"
      >
        Retry
      </button>
    </div>


    <div v-else-if="user">
      <h1 class="text-3xl font-bold text-center mb-6 text-lime-600">
        Welcome, {{ user.name }}
      </h1>

      <div class="space-y-4">
        <div class="flex justify-between items-center border-b pb-2">
          <span class="text-gray-600 font-semibold">Email:</span>
          <span class="text-gray-800">{{ user.email }}</span>
        </div>

        <div class="flex justify-between items-center border-b pb-2">
          <span class="text-gray-600 font-semibold">Member Since:</span>
          <span class="text-gray-800">{{ formatDate(user.created_at) }}</span>
        </div>
      </div>

      <button
        @click="logout"
        class="mt-8 w-full py-3 bg-green-500 hover:bg-red-600 active:bg-red-700 text-white rounded-xl transition"
      >
        Log Out
      </button>
    </div>

   
    <div v-else class="text-center text-gray-400">
      <p>No profile data available</p>
    </div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  data() {
    return {
      user: null,
      loading: true,
      error: null,
    };
  },
  created() {
    this.fetchUserProfile();
  },
  methods: {
    async fetchUserProfile() {
      this.loading = true;
      this.error = null;
      
      try {
        const token = localStorage.getItem('auth_token');
        if (!token) {
          throw new Error('Please log in to view your profile');
        }

        const response = await axios.get('/api/user/profile', {
          headers: {
            Authorization: `Bearer ${token}`,
            'Cache-Control': 'no-cache',
          },
          params: {
            timestamp: Date.now() 
          }
        });

        if (!response.data || !response.data.data) {
          throw new Error('Empty response from server');
        }

        this.user = response.data.data;
      } catch (error) {
        console.error('Profile fetch error:', error);
        
        if (error.response?.status === 401) {
          this.error = 'Session expired. Please log in again.';
          this.$router.push('/login');
        } else {
          this.error = error.response?.data?.message || 
                      error.message || 
                      'Failed to load profile data';
        }
      } finally {
        this.loading = false;
      }
    },
    
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    
    async logout() {
      try {
        await axios.post('/api/logout', {}, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('auth_token')}`
          }
        });
        localStorage.removeItem('auth_token');
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed:', error);
      }
    }
  }
};
</script>

