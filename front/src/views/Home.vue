<script setup lang="ts">
import { ref, onMounted } from 'vue'
import weatherApi from '../api/weatherApi'
import type { Favorite } from '@/types/favorite'
import SearchBar from '../components/SearchBar.vue'
import WeatherCard from '../components/WeatherCard.vue'
import FavoritesList from '../components/FavoritesList.vue'

const currentWeatherData = ref<any>(null)
const favorites = ref<Favorite[]>([])
const loading = ref(false)
const error = ref('')

const fetchFavorites = async () => {
  try {
    const response = await weatherApi.getFavorites()
    favorites.value = response
  } catch (err) {
    console.error('Failed to fetch favorites', err)
  }
}

const handleSearch = async (query: string) => {
  loading.value = true
  error.value = ''
  currentWeatherData.value = null

  try {
    const response = await weatherApi.getWeather(query)
    currentWeatherData.value = response.data
  } catch (err: any) {
    error.value = err.response?.data?.error || 'Une erreur est survenue'
  } finally {
    loading.value = false
  }
}

const handleSelectFavorite = async (favorite: Favorite) => {
  loading.value = true
  error.value = ''
  try {
    const response = await weatherApi.getWeather(favorite.name)
    currentWeatherData.value = response.data
  } catch (err: any) {
    error.value = 'Erreur lors du chargement du favori'
  } finally {
    loading.value = false
  }
}

const handleSaveFavorite = async () => {
  if (!currentWeatherData.value) return
  try {
    await weatherApi.addFavorite({
      name: currentWeatherData.value.city,
      latitude: currentWeatherData.value.latitude,
      longitude: currentWeatherData.value.longitude
    })
    await fetchFavorites()
  } catch (err) {
    console.error('Failed to save favorite', err)
  }
}

const handleDeleteFavorite = async (id: number) => {
  try {
    await weatherApi.deleteFavorite(id)
    await fetchFavorites()
  } catch (err) {
    console.error('Failed to delete favorite', err)
  }
}

onMounted(fetchFavorites)
</script>

<template>
  <h1>Prévisions Météo</h1>
  <div class="home">
    <aside>
      <SearchBar @search="handleSearch" />
      
      <FavoritesList 
        :favorites="favorites" 
        @select="handleSelectFavorite"
        @delete="handleDeleteFavorite"
      />
    </aside>

    <main>
      <div v-if="loading" class="status">Chargement...</div>
      <div v-if="error" class="status error">{{ error }}</div>

      <WeatherCard 
        v-if="currentWeatherData && !loading" 
      :weather-data="currentWeatherData"
      :is-favorite="favorites.some(favorite => favorite.name === currentWeatherData.city)"
      @save="handleSaveFavorite"
    />
    </main>
  </div>
</template>

<style scoped>
.home {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

@media (max-width: 768px) {
  .home {
    grid-template-columns: 1fr;
  }
}
</style>
