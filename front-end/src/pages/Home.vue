<script setup>
import axios from 'axios'
import CardList from '../components/CardList.vue'

import { inject, reactive, ref, onMounted } from 'vue'
import debounce from 'lodash/debounce'

const { cart, addToCart, removeFromCart } = inject('cart')

const items = inject('items')
const toggleFavourite = async (item) => {
  try {
    if (!item.isFavourite) {
      const { data } = await axios.post(`https://6aebf3b8569a6036.mokky.dev/favourites`, {
        sneakerId: item.id,
        item,
      })
      item.isFavourite = true
      item.favouriteID = data.id
    } else {
      await axios.delete(`https://6aebf3b8569a6036.mokky.dev/favourites/${item.favouriteID}`)
      item.isFavourite = false
      item.favouriteID = null
    }
  } catch (error) {
    console.error('Error toggling favourite:', error)
  }
}

const fetchFavourites = async () => {
  try {
    const { data: favourites } = await axios.get(`https://6aebf3b8569a6036.mokky.dev/favourites`)
    items.value = items.value.map((item) => {
      const favourite = favourites.find((fav) => fav.sneakerId === item.id)
      return favourite
        ? { ...item, isFavourite: true, favouriteID: favourite.id }
        : { ...item, isFavourite: false, favouriteID: null }
    })
  } catch (error) {
    console.error('Error fetching favourites:', error)
  }
}

const fetchItems = async () => {
  try {
    const params = { sortBy: filters.sortBy }
    if (filters.searchQuery) {
      params.title = `*${filters.searchQuery}*`
    }
    const { data } = await axios.get(`https://6aebf3b8569a6036.mokky.dev/items`, { params })
    items.value = data.map((obj) => ({
      ...obj,
      isFavourite: false,
      favouriteID: null,
      isAdded: cart.value.some((cartItem) => cartItem.id === obj.id),
    }))
    await fetchFavourites()
  } catch (error) {
    console.error('Error fetching items:', error)
  }
}

const filters = reactive({
  sortBy: 'title',
  searchQuery: '',
})

const onChangeSelect = (event) => {
  filters.sortBy = event.target.value
  fetchItems()
}

const onChangeSearchInput = debounce((event) => {
  filters.searchQuery = event.target.value
  fetchItems()
}, 300)

const toggleCartItem = (item) => {
  item.isAdded ? removeFromCart(item) : addToCart(item)
}
onMounted(async () => {
  await fetchItems()
})
</script>

<template>
  <div>
    <div class="flex justify-between items-center">
      <h2 class="text-3xl font-bold mb-8">All Shoes</h2>
      <div class="flex gap-4">
        <select @change="onChangeSelect" class="py-2.3 px-3 border rounded-2xl outline-none">
          <option value="title">By name</option>
          <option value="price">By price (cheaper first)</option>
          <option value="-price">By price (expensive first)</option>
        </select>
        <div class="relative">
          <img class="absolute left-4 top-3" src="/search.svg" />
          <input
            @input="onChangeSearchInput"
            placeholder="Search..."
            class="rounded-2xl border border-slate-300 py-2 pl-10 pr-4 outline-none focus:border-slate-500"
          />
        </div>
      </div>
    </div>
    <div class="mt-10">
      <CardList :items="items" @add-to-favourite="toggleFavourite" @add-to-cart="toggleCartItem" />
    </div>
  </div>
</template>
