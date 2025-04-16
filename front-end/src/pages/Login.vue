<template>
  <div class="min-h-screen flex items-center justify-center ">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
      <h2 class="text-2xl font-bold mb-6 text-center">Login to your account</h2>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1" for="email">Email</label>
          <input
            id="email"
            type="email"
            v-model="email"
            class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1" for="password">Password</label>
          <input
            id="password"
            type="password"
            v-model="password"
            class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring focus:border-blue-400"
            required
            autocomplete="current-password"
          />
        </div>

        <div>
          <button
            type="submit"
            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg transition duration-200"
          >
            Login
          </button>
        </div>
        <p class="text-sm text-center mt-4">
          Don't have an account?
          <router-link to="/register" class="text-blue-500">Sign up</router-link>
        </p>

        <p v-if="errorMessage" class="text-red-500 text-sm text-center">{{ errorMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api' 

const email = ref('')
const password = ref('')
const errorMessage = ref('')
const router = useRouter()

const handleLogin = async () => {
  try {
    errorMessage.value = '' 


    const user = await api.login({
      email: email.value,
      password: password.value,
    })

    router.push('/')
  } catch (error) {
    errorMessage.value = 'Invalid email or password.'
    console.error('Login error:', error) 
  }
}
</script>
