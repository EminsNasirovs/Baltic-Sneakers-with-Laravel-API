<script setup>
import axios from 'axios'
import { ref, onMounted, inject } from 'vue'
import CardList from '../components/CardList.vue'
const favourites = ref([])
const fetchFavourites = async () => {
  try {
    const { data } = await axios.get(`https://6aebf3b8569a6036.mokky.dev/favourites`)
    favourites.value = data.map((fav) => ({
      ...fav.item,
      isFavourite: true,
      favouriteID: fav.id,
    }))
  } catch (error) {
    console.error('Error fetching favourites:', error)
  }
}
onMounted(fetchFavourites)
</script>
<template>
  <div>
    <h2 class="text-3xl font-bold mb-8">My Favourites</h2>
    <CardList :items="favourites" :isFavourites="true" />
  </div>
</template>
