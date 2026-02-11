<script setup lang="ts">
import { ref } from 'vue'
import weatherApi from '../api/weatherApi'
import SearchBar from '../components/SearchBar.vue'

const currentWeatherData = ref<any>(null)
const loading = ref(false)
const error = ref('')

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
</script>

<template>
  <div class="home">
    <h1>Prévisions Météo</h1>

    <SearchBar @search="handleSearch" />

    <div v-if="loading" class="status">Chargement...</div>
    <div v-if="error" class="status error">{{ error }}</div>

    <div v-if="currentWeatherData">
      <ul>
        <li v-if="currentWeatherData?.city">
          Ville : {{ currentWeatherData?.city }} ({{ currentWeatherData?.country }})
        </li>
        <li v-if="currentWeatherData?.latitude">
          Latitude : {{ currentWeatherData?.latitude }}
        </li>
        <li v-if="currentWeatherData?.longitude">
          Longitude : {{ currentWeatherData?.longitude }}
        </li>
        <li v-if="currentWeatherData?.temperature">
          Température : {{ currentWeatherData?.temperature }}°C
        </li>
        <li v-if="currentWeatherData?.windSpeed">
          Vent : {{ currentWeatherData?.windSpeed }}
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped></style>
