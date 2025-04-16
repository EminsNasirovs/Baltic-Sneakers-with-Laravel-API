<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded-2xl max-w-sm w-full">
        <h3 class="text-xl font-bold text-lime-600 mb-4">{{ title }}</h3>
        <p class="mb-4">{{ message }}</p>
        <div class="flex justify-end gap-2">
          <button 
            @click="$emit('close')" 
            class="px-4 py-2 border rounded-xl hover:bg-gray-100 transition"
          >
            Cancel
          </button>
          <router-link 
            to="/login" 
            class="px-4 py-2 bg-lime-500 hover:bg-lime-600 text-white rounded-xl transition"
            @click="$emit('close')"
          >
            Go to Login
          </router-link>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    title: {
      type: String,
      default: 'Login Required',
    },
    message: {
      type: String,
      default: 'Please log in to continue.',
    },
  })
  </script>