<script setup lang="ts">
import { onMounted } from 'vue'
import { useWeatherStore } from '@/stores/weather'
import SearchBar from '../components/SearchBar.vue'
import WeatherCard from '../components/WeatherCard.vue'
import FavoritesList from '../components/FavoritesList.vue'

const weatherStore = useWeatherStore()

onMounted(() => {
  weatherStore.fetchFavorites()
})
</script>

<template>
  <h1>Prévisions Météo</h1>
  <div class="home">
    <aside>
      <SearchBar @search="weatherStore.searchWeather" />
      
      <FavoritesList 
        :favorites="weatherStore.favorites" 
        @select="weatherStore.selectFavorite"
        @delete="weatherStore.deleteFavorite"
      />
    </aside>

    <main>
      <div v-if="weatherStore.loading" class="status">Chargement...</div>
      <div v-if="weatherStore.error" class="status error">{{ weatherStore.error }}</div>

      <WeatherCard 
        v-if="weatherStore.currentWeatherData && !weatherStore.loading" 
        :weather-data="weatherStore.currentWeatherData"
        :is-favorite="weatherStore.isFavorite"
        @toggle="weatherStore.toggleFavorite"
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
