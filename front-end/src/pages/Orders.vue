<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'

const orders = ref([])
const isLoading = ref(true)
const isAuthenticated = ref(!!localStorage.getItem('auth_token'))

const fetchOrders = async () => {
  try {
    const token = localStorage.getItem('auth_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    const { data } = await axios.get('http://127.0.0.1:8000/api/orders', { headers })
    orders.value = data
  } catch (error) {
    console.error('Error fetching orders:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(fetchOrders)
</script>

<template>
  <div>
    <h2 class="text-3xl font-bold mb-8">My Orders</h2>

    <div v-if="isLoading" class="text-lg text-gray-600">Loading orders...</div>

    <div v-else-if="orders.length === 0" class="text-lg text-gray-600">No orders found.</div>

    <div v-else class="grid gap-6">
      <div
        v-for="order in orders"
        :key="order.id"
        class="rounded-2xl shadow-md border p-6 bg-white hover:shadow-lg transition-all duration-300"
      >
        <div class="mb-4 flex justify-between items-center">
          <h3 class="text-xl font-semibold text-lime-600">Order #{{ order.id }}</h3>
          <span class="text-sm text-gray-500"
            >Total: <b>{{ order.total_price }}</b> €</span
          >
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4">
          <div
            v-for="item in order.items"
            :key="item.id"
            class="rounded-xl border p-4 text-center hover:shadow transition"
          >
            <img :src="item.imageUrl" :alt="item.title" class="w-full h-28 object-contain mb-2" />
            <div class="text-sm font-medium">{{ item.title }}</div>
            <div class="text-gray-500 text-sm">{{ item.price }} €</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
