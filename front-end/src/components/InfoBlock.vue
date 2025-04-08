<script setup>
import { inject } from 'vue'
defineProps({
  title: String,
  description: String,
  ImageUrl: String,
})
const { closeDrawer } = inject('cart')
</script>

<template>
  <div class="flex flex-col items-center text-center w-72 mx-auto">
    <img height="100" width="100" :src="ImageUrl" alt="Info image" />
    <h2 class="mt-4 text-2xl font-medium">{{ title }}</h2>
    <p class="text-gray-400 mt-2">{{ description }}</p>
    <button
      @click="closeDrawer"
      class="mt-4 bg-lime-500 w-full rounded-xl py-3 text-white cursor-pointer disabled:bg-slate-300 hover:bg-lime-600 active:bg-lime-700 transition"
    >
      Go back
    </button>
  </div>
</template>
