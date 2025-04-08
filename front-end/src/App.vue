<script setup>
import { onMounted, ref, watch, provide, computed } from 'vue'
import Drawer from './components/Drawer.vue'
import Header from './components/Header.vue'
/* Drawer */
const cart = ref([])

const drawerOpen = ref(false)

const totalPrice = computed(() => cart.value.reduce((acc, item) => acc + item.price, 0))
const vatPrice = computed(() => Math.round((totalPrice.value * 5) / 100))

const items = ref([])
const closeDrawer = () => (drawerOpen.value = false)
const openDrawer = () => (drawerOpen.value = true)

const addToCart = (item) => {
  cart.value.push(item)
  item.isAdded = true
}

const removeFromCart = (item) => {
  const index = cart.value.findIndex((cartItem) => cartItem.id === item.id)
  if (index !== -1) {
    cart.value.splice(index, 1)
    item.isAdded = false
  }
}

onMounted(async () => {
  const localCart = JSON.parse(localStorage.getItem('cart') || '[]')
  cart.value = localCart
})

watch(cart, () => localStorage.setItem('cart', JSON.stringify(cart.value)), { deep: true })

provide('cart', {
  cart,
  closeDrawer,
  openDrawer,
  addToCart,
  removeFromCart,
})
provide('items', items)

/* Drawer */
</script>

<template>
  <main>
    <Drawer
      v-if="drawerOpen"
      @close-drawer="closeDrawer"
      :total-price="totalPrice"
      :vat-price="vatPrice"
      @create-order="createOrder"
    />
    <div class="bg-white w-4/5 m-auto rounded-xl shadow-xl mt-14">
      <Header :total-price="totalPrice" @open-drawer="openDrawer" />
      <div class="p-10">
        <router-view> </router-view>
      </div>
    </div>
  </main>
</template>
