<script setup lang="ts">
import { ref, onMounted } from 'vue'
import weatherApi from '../api/weatherApi'
import SearchBar from '../components/SearchBar.vue'
import WeatherCard from '../components/WeatherCard.vue'
import FavoritesList from '../components/FavoritesList.vue'

const currentWeatherData = ref<any>(null)
const favorites = ref<any[]>([])
const loading = ref(false)
const error = ref('')

const fetchFavorites = async () => {
  try {
    const response = await weatherApi.getFavorites()
    favorites.value = response.data
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

const handleSelectFavorite = async (fav: any) => {
  loading.value = true
  error.value = ''
  try {
    const response = await weatherApi.getWeather(fav.name)
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
  <div class="home">
    <h1>Prévisions Météo</h1>

    <SearchBar @search="handleSearch" />

    <div v-if="loading" class="status">Chargement...</div>
    <div v-if="error" class="status error">{{ error }}</div>

    <WeatherCard 
      v-if="currentWeatherData && !loading" 
      :weather-data="currentWeatherData"
      @save="handleSaveFavorite"
    />

    <FavoritesList 
      :favorites="favorites" 
      @select="handleSelectFavorite"
      @delete="handleDeleteFavorite"
    />
  </div>
</template>

<style scoped></style>
