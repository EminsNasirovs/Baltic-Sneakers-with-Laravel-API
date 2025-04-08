<script setup>
import { inject } from 'vue'
import CartItem from './CartItem.vue'
const { cart, removeFromCart } = inject('cart')

const onClickRemove = () => {
  addToCart(item)
}
</script>
<template>
  <div class="flex flex-col gap-5" v-auto-animate>
    <CartItem
      v-for="item in cart"
      v-bind:key="item.id"
      :title="item.title"
      :price="item.price"
      :imageUrl="item.imageUrl"
      @on-click-remove="() => removeFromCart(item)"
    />
  </div>
</template>
