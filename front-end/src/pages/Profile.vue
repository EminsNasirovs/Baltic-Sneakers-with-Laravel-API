<template>
  <div class="profile-container">
    <!-- Loading state -->
    <div v-if="loading" class="loading-indicator">
      <p>Loading your profile...</p>
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="error-message">
      <p>{{ error }}</p>
      <button @click="fetchUserProfile" class="retry-button">Retry</button>
    </div>

    <!-- Success state -->
    <div v-else-if="user" class="profile-content">
      <h1 class="profile-title">Welcome, {{ user.name }}</h1>
      
      <div class="profile-details">
        <div class="detail-item">
          <span class="detail-label">Email:</span>
          <span class="detail-value">{{ user.email }}</span>
        </div>
        
        <div class="detail-item">
          <span class="detail-label">Member Since:</span>
          <span class="detail-value">{{ formatDate(user.created_at) }}</span>
        </div>
      </div>

      <button @click="logout" class="logout-button">Log Out</button>
    </div>

    <!-- Empty state (shouldn't normally show) -->
    <div v-else class="empty-state">
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
          throw new Error('No authentication token found');
        }

        const response = await axios.get('/api/user/profile', {
          headers: {
            Authorization: `Bearer ${token}`,
            'Cache-Control': 'no-cache',
          },
          params: {
            timestamp: Date.now() // Prevent caching
          }
        });

        if (!response.data || !response.data.data) {
          throw new Error('Empty response from server');
        }

        // Assign the user data from response.data.data
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

<style scoped>
.profile-container {
  max-width: 600px;
  margin: 2rem auto;
  padding: 2rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.loading-indicator {
  text-align: center;
  padding: 2rem;
  color: #666;
}

.error-message {
  text-align: center;
  padding: 2rem;
  color: #d32f2f;
}

.retry-button {
  margin-top: 1rem;
  padding: 0.5rem 1rem;
  background: #1976d2;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.profile-title {
  color: #333;
  margin-bottom: 1.5rem;
  text-align: center;
}

.profile-details {
  margin: 2rem 0;
}

.detail-item {
  display: flex;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #eee;
}

.detail-label {
  font-weight: bold;
  width: 120px;
  color: #555;
}

.detail-value {
  flex: 1;
}

.logout-button {
  display: block;
  width: 100%;
  padding: 0.75rem;
  background: #d32f2f;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 2rem;
}

.empty-state {
  text-align: center;
  padding: 2rem;
  color: #666;
}
</style>
