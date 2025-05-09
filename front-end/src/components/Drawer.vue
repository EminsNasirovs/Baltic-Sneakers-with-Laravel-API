<script setup>
import { ref, computed, inject } from 'vue'
import InfoBlock from './InfoBlock.vue'
import CartItemList from './CartItemList.vue'
import DrawerHead from './DrawerHead.vue'
import axios from 'axios'
import LoginPromptModal from '../components/LoginPrompt.vue'
const cartIsEmpty = computed(() => cart.value.length === 0)
const orderId = ref(null)
const props = defineProps({
  totalPrice: Number,
  vatPrice: Number,
})
const isCreatingOrder = ref(false)
const isAuthenticated = ref(!!localStorage.getItem('auth_token'))
const showLoginModal = ref(false)
const { cart, closeDrawer } = inject('cart')
const items = inject('items')

const createOrder = async () => {
  if (!isAuthenticated.value) {
    showLoginModal.value = true
    return
  }
  try {
    isCreatingOrder.value = true

    const token = localStorage.getItem('auth_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    const { data } = await axios.post(
      `http://127.0.0.1:8000/api/orders`,
      {
        items: cart.value,
        total_price: props.totalPrice,
      },
      { headers },
    )

    cart.value = []
    orderId.value = data.id

    if (items) {
      items.value.forEach((item) => {
        item.isAdded = false
      })
    }
  } catch (error) {
    console.error('Error creating order:', error)
  } finally {
    isCreatingOrder.value = false
  }
}

const cartButtonDisabled = computed(() => isCreatingOrder.value || cartIsEmpty.value)
</script>
<template>
  <div>
    <LoginPromptModal
      v-if="showLoginModal"
      title="Login Required"
      message="Please log in to place an order!"
      @close="showLoginModal = false"
    />

    <div class="fixed top-0 left-0 h-full w-full bg-black z-10 opacity-50"></div>
    <div class="bg-white w-96 fixed right-0 top-0 p-8 h-full z-20">
      <DrawerHead />

      <div v-if="!totalPrice || orderId" class="flex h-full items-center">
        <InfoBlock
          v-if="!totalPrice && !orderId"
          title="Cart is Empty"
          description="Add some sneakers, to make an order"
          ImageUrl="/package-icon.png"
        />
        <InfoBlock
          v-if="orderId"
          title="Order has been placed!"
          :description="`Your order #${orderId} will be delivered by courier`"
          ImageUrl="/order-success-icon.png"
        />
      </div>
      <div v-else>
        <CartItemList />
        <div class="flex flex-col gap-4 my-7">
          <div class="flex gap-2">
            <span>Total Price:</span>
            <div class="flex-1 border-b border-dashed"></div>
            <b class="flex items-center space-x-1">
              <span>{{ totalPrice }}</span>
              <img class="w-3" src="/eiroicon.svg" />
            </b>
          </div>

          <div class="flex gap-2">
            <span>Tax 5%:</span>
            <div class="flex-1 border-b border-dashed"></div>
            <b class="flex items-center space-x-1">
              <span>{{ vatPrice }}</span>
              <img class="w-3" src="/eiroicon.svg" />
            </b>
          </div>
          <button
            :disabled="cartButtonDisabled"
            @click="createOrder"
            class="mt-4 bg-lime-500 w-full rounded-xl py-3 text-white cursor-pointer disabled:bg-slate-300 hover:bg-lime-600 active:bg-lime-700 transition"
          >
            Confirm order
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
