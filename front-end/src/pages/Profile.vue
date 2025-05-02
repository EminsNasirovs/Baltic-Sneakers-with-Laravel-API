<template>
  <div class="max-w-xl mx-auto my-10 p-8 bg-white rounded-2xl shadow-md">
    <div v-if="loading" class="text-center text-gray-500">
      <p>Loading your profile...</p>
    </div>

    <div v-else-if="error" class="text-center text-red-500">
      <p class="mb-4">{{ error }}</p>
      <a
        href="/login"
        class="mt-4 ml-1 px-4 py-2 bg-lime-500 text-white rounded-xl hover:bg-lime-600 active:bg-lime-700 transition"
      >
        Login
      </a>
    </div>

    <div v-else-if="user">
      <h1 class="text-3xl font-bold text-center mb-6 text-lime-600">
        Welcome, {{ user.name }}
      </h1>

      <div class="space-y-4">
        <!-- Name -->
        <div class="flex justify-between items-center border-b pb-2">
          <label class="text-gray-600 font-semibold">Name:</label>
          <div class="flex items-center gap-2">
            <template v-if="editField === 'name'">
              <input
                v-model="editUser.name"
                class="border rounded p-1 text-sm"
              />
              <button @click="saveField('name')" class="text-blue-500 text-sm">Save</button>
              <button @click="cancelEdit" class="text-gray-500 text-sm">Cancel</button>
            </template>
            <template v-else>
              <span class="text-gray-800">{{ user.name }}</span>
              <button @click="editField = 'name'" class="text-lime-600 text-sm">Edit</button>
            </template>
          </div>
        </div>

        <!-- Email -->
        <div class="flex justify-between items-center border-b pb-2">
          <label class="text-gray-600 font-semibold">Email:</label>
          <div class="flex items-center gap-2">
            <template v-if="editField === 'email'">
              <input
                v-model="editUser.email"
                class="border rounded p-1 text-sm"
              />
              <button @click="saveField('email')" class="text-blue-500 text-sm">Save</button>
              <button @click="cancelEdit" class="text-gray-500 text-sm">Cancel</button>
            </template>
            <template v-else>
              <span class="text-gray-800">{{ user.email }}</span>
              <button @click="editField = 'email'" class="text-lime-600 text-sm">Edit</button>
            </template>
          </div>
        </div>

        <!-- Created At -->
        <div class="flex justify-between items-center border-b pb-2">
          <span class="text-gray-600 font-semibold">Member Since:</span>
          <span class="text-gray-800">{{ formatDate(user.created_at) }}</span>
        </div>
      </div>

      <button
        @click="logout"
        class="mt-8 w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl"
      >
        Log Out
      </button>
    </div>

    <div v-else class="text-center text-gray-400">
      <p>No profile data available</p>
    </div>
  </div>
</template>

<script setup>

import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const user = ref(null)
const editUser = ref({ name: '', email: '' })
const editField = ref(null)
const loading = ref(true)
const error = ref(null)

onMounted(fetchUserProfile)

async function fetchUserProfile() {
  loading.value = true
  error.value = null

  try {
    const token = localStorage.getItem('auth_token')
    if (!token) throw new Error('Please log in to view your profile')

    const response = await axios.get('/api/user/profile', {
      headers: {
        Authorization: `Bearer ${token}`,
        'Cache-Control': 'no-cache'
      },
      params: { timestamp: Date.now() }
    })

    if (!response.data || !response.data.data) {
      throw new Error('Empty response from server')
    }

    user.value = response.data.data
    editUser.value.name = user.value.name
    editUser.value.email = user.value.email
  } catch (err) {
    error.value =
      err.response?.data?.message || err.message || 'Failed to load profile data'
    if (err.response?.status === 401) {
      router.push('/login')
    }
  } finally {
    loading.value = false
  }
}

async function saveField(field) {
  try {
    const token = localStorage.getItem('auth_token')
    const payload = { [field]: editUser.value[field] }

    const response = await axios.put('/api/user/update', payload, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })

    user.value = response.data.user
    editField.value = null
  } catch (err) {
    alert('Update failed: ' + (err.response?.data?.message || err.message))
  }
}

function cancelEdit() {
  editUser.value.name = user.value.name
  editUser.value.email = user.value.email
  editField.value = null
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

async function logout() {
  try {
    await axios.post('/api/logout', {}, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('auth_token')}`
      }
    })
    localStorage.removeItem('auth_token')
    router.push('/login')
  } catch (err) {
    console.error('Logout failed:', err)
  }
}
</script>