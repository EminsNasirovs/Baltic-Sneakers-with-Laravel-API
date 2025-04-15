<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'
import CardList from '../components/CardList.vue'

const favourites = ref([])

const fetchFavourites = async () => {
  try {
    // 1. Get auth token for Laravel API
    const token = localStorage.getItem('auth_token')
    const headers = token ? { Authorization: `Bearer ${token}` } : {}

    // 2. Fetch favorites list from Laravel (just IDs)
    const favsResponse = await axios.get('http://127.0.0.1:8000/api/favourites', { headers })
    
    // 3. Fetch ALL sneakers from mock API
    const sneakersResponse = await axios.get('https://6aebf3b8569a6036.mokky.dev/items')
    
    // 4. Merge the data
    favourites.value = favsResponse.data.map(fav => {
      // Find the full sneaker details by matching sneaker_id with mock API data
      const sneaker = sneakersResponse.data.find(item => item.id === fav.sneaker_id)
      
      // Return null if sneaker not found (will be filtered out)
      if (!sneaker) return null
      
      // Return the same structure you had before
      return {
        ...sneaker, // All sneaker details (title, price, imageUrl)
        isFavourite: true,
        favouriteID: fav.id, // The ID from Laravel's favourites table
        isAdded: false // Add cart logic if needed
      }
    }).filter(Boolean) // Remove any null entries

  } catch (error) {
    console.error('Error fetching favourites:', error)
  }
}

onMounted(fetchFavourites)
</script>

<template>
  <div>
    <h2 class="text-3xl font-bold mb-8">My Favourites</h2>
    <CardList 
      :items="favourites" 
      :isFavourites="true" 
    />
  </div>
</template>