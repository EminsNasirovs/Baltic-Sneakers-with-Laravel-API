<script setup>
import Card from './Card.vue'

defineProps({
  items: Array,
  isFavourites: Boolean,
})

const emit = defineEmits(['addToFavourite', 'addToCart'])
</script>
<template>
  <div class="grid grid-cols-4 gap-5" v-auto-animate>
    <Card
      v-for="item in items"
      :key="item.id"
      :id="item.id"
      :price="item.price"
      :title="item.title"
      :imageUrl="item.imageUrl"
      :onClickAdd="isFavourites ? null : () => emit('addToCart', item)"
      :onClickFavourite="isFavourites ? null : () => emit('addToFavourite', item)"
      :isFavourite="item.isFavourite"
      :isAdded="item.isAdded"
    />
  </div>
</template>
